<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/**
 * Enviar mensagem de texto pelo WhatsApp Cloud API
 */
function enviarMensagemWhatsApp($to, $text) {
    $url = WHATSAPP_API_URL . WHATSAPP_PHONE_ID . '/messages';

    $data = [
        'messaging_product' => 'whatsapp',
        'to'                => $to,
        'type'              => 'text',
        'text'              => ['body' => $text],
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . WHATSAPP_TOKEN,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode($data),
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('Erro cURL WhatsApp: ' . curl_error($ch));
    }
    curl_close($ch);

    return $response;
}

/**
 * Buscar ou criar contato por telefone
 */
function getOrCreateContato($telefone) {
    $pdo = getPDO();

    // Aqui você pode padronizar o telefone, se quiser (tirar espaços, etc.)
    $tel = $telefone;

    $stmt = $pdo->prepare("SELECT * FROM contatos WHERE telefone = ?");
    $stmt->execute([$tel]);
    $contato = $stmt->fetch();

    if ($contato) {
        return $contato;
    }

    // Criar novo contato
    $stmt = $pdo->prepare("
        INSERT INTO contatos (telefone, data_primeiro_contato, data_ultimo_contato)
        VALUES (?, NOW(), NOW())
    ");
    $stmt->execute([$tel]);
    $id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("SELECT * FROM contatos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Salvar mensagem no histórico
 */
function salvarMensagem($contatoId, $direcao, $conteudo) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("
        INSERT INTO mensagens (contato_id, direcao, conteudo, data_envio)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$contatoId, $direcao, $conteudo]);
}

/**
 * Obter histórico recente (últimas 10 mensagens)
 */
function getHistoricoMensagens($contatoId, $limite = 10) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("
        SELECT direcao, conteudo
        FROM mensagens
        WHERE contato_id = ?
        ORDER BY data_envio DESC
        LIMIT ?
    ");
    $stmt->bindValue(1, $contatoId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limite, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll();
    $rows = array_reverse($rows); // mais antigas primeiro

    $linhas = [];
    foreach ($rows as $r) {
        $prefixo = $r['direcao'] === 'cliente' ? 'CLIENTE: ' : 'CLINICA: ';
        $linhas[] = $prefixo . $r['conteudo'];
    }
    return implode("\n", $linhas);
}

/**
 * Chamar a IA (OpenAI) para gerar a resposta inteligente
 */
function gerarRespostaIA($historico, $mensagemAtual, $dadosPreviosContato = []) {
    $prompt = "
Você é uma IA de Secretaria/Vendedora especializada em clínicas de saúde e estética.
Siga estas regras:

- Identifique se o contato é novo lead ou recorrente (se tiver histórico).
- Responda como se fosse a atendente da clínica, com linguagem acolhedora e profissional.
- Foco: pré-atendimento, aquecimento do lead e condução ao agendamento quando fizer sentido.
- Não faça diagnóstico nem promessas de resultado.
- Saída SEMPRE neste formato:

RESUMO_DA_SITUACAO:
- [resumo em 2-4 linhas]

PROXIMA_MENSAGEM_PARA_WHATSAPP:
[texto pronto para copiar e colar]

DADOS_DO_CONTATO:
- Nome:
- Telefone:
- Tipo de contato: [novo lead / lead recorrente / cliente]
- Procedimentos de interesse:
- Objetivo principal:
- Estágio do funil:
- Principais objeções/medos:
- Próximo passo recomendado:

[HISTORICO]:
$historico

[MENSAGEM_ATUAL_DO_CLIENTE]:
$mensagemAtual

[DADOS_PREVIOS_CONTATO]:
" . json_encode($dadosPreviosContato, JSON_UNESCAPED_UNICODE);

    $url = 'https://api.openai.com/v1/chat/completions';

    $data = [
        'model'    => 'gpt-4.1-mini',
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.4,
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . OPENAI_API_KEY,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode($data),
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('Erro cURL OpenAI: ' . curl_error($ch));
        curl_close($ch);
        return null;
    }
    curl_close($ch);

    $json = json_decode($response, true);
    if (!isset($json['choices'][0]['message']['content'])) {
        error_log('Resposta inesperada OpenAI: ' . $response);
        return null;
    }

    return $json['choices'][0]['message']['content'];
}

/**
 * Extrair apenas o texto da seção PROXIMA_MENSAGEM_PARA_WHATSAPP
 */
function extrairMensagemWhatsAppDaIA($textoIA) {
    $padrao = '/PROXIMA_MENSAGEM_PARA_WHATSAPP:\s*([\s\S]*?)\nDADOS_DO_CONTATO:/u';
    if (preg_match($padrao, $textoIA, $matches)) {
        return trim($matches[1]);
    }
    return "Oi, tudo bem? Recebemos sua mensagem e já vamos te atender certinho.";
}