<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// 1) GET: verificação do webhook pela Meta
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Atenção: parâmetros com ponto (hub.mode) viram underscore (hub_mode) no PHP
    $mode      = $_GET['hub_mode']        ?? null;
    $token     = $_GET['hub_verify_token'] ?? null;
    $challenge = $_GET['hub_challenge']   ?? null;

    if ($mode === 'subscribe' && $token === WEBHOOK_VERIFY_TOKEN) {
        http_response_code(200);
        echo $challenge;
        exit;
    } else {
        http_response_code(403);
        echo "Verificação falhou";
        exit;
    }
}

// 2) POST: mensagens recebidas do WhatsApp
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    // Para debug (opcional):
    // file_put_contents(__DIR__ . '/log_whatsapp.txt', $body . PHP_EOL, FILE_APPEND);

    try {
        if (
            isset($data['entry'][0]['changes'][0]['value']['messages'][0])
        ) {
            $messageData = $data['entry'][0]['changes'][0]['value']['messages'][0];

            $from    = $messageData['from'];                    // telefone do cliente
            $msgBody = $messageData['text']['body'] ?? '';      // texto da mensagem

            // 1) Buscar ou criar contato
            $contato = getOrCreateContato($from);

            // 2) Atualizar data_ultimo_contato
            $pdo = getPDO();
            $stmt = $pdo->prepare("UPDATE contatos SET data_ultimo_contato = NOW() WHERE id = ?");
            $stmt->execute([$contato['id']]);

            // 3) Salvar mensagem do cliente
            salvarMensagem($contato['id'], 'cliente', $msgBody);

            // 4) Montar histórico recente
            $historico = getHistoricoMensagens($contato['id'], 10);

            // 5) Dados prévios do contato para a IA
            $dadosPreviosContato = [
                'nome'                  => $contato['nome'],
                'telefone'              => $contato['telefone'],
                'tipo_contato'          => $contato['tipo_contato'],
                'estagio_funil'         => $contato['estagio_funil'],
                'procedimentos_interesse'=> $contato['procedimentos_interesse'],
                'objetivo_principal'    => $contato['objetivo_principal'],
            ];

            // 6) Chamar a IA
            $respostaIA = gerarRespostaIA($historico, $msgBody, $dadosPreviosContato);

            // 7) Extrair apenas a parte de mensagem para WhatsApp
            $mensagemParaCliente = extrairMensagemWhatsAppDaIA($respostaIA ?? '');

            // 8) Enviar pelo WhatsApp
            enviarMensagemWhatsApp($from, $mensagemParaCliente);

            // 9) Salvar mensagem da clínica
            salvarMensagem($contato['id'], 'clinica', $mensagemParaCliente);
        }

        http_response_code(200);
        echo "EVENT_RECEIVED";
    } catch (Exception $e) {
        error_log('Erro no webhook: ' . $e->getMessage());
        http_response_code(500);
        echo "Erro interno";
    }

    exit;
}

// Se chegar outro método:
http_response_code(404);
echo "Not found";