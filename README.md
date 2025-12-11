# Landing Page – IA Secretária / Vendedora para WhatsApp

Este projeto é uma **landing page em página única (single page)** feita em **HTML + Tailwind CSS via CDN + JavaScript puro**, pensada para vender um serviço de **IA de secretária/vendedora para WhatsApp**, com foco em **clínicas e consultórios de saúde e estética**.

A página já vem pronta com copy de vendas em português, seções organizadas e pequenos recursos de interação (menu mobile, rolagem suave, FAQ em acordeão, etc.).

---

## 1. Estrutura do projeto

O projeto é bem simples, composto por:

- `index.html` – arquivo principal, contendo **todo o HTML, CSS (Tailwind via CDN) e JavaScript** necessários.
- `README.md` – este arquivo de explicação e guia de uso.

Não há build step, bundler ou dependências de Node; basta abrir o `index.html` em um navegador moderno.

---

## 2. O que a landing page já faz

A página foi desenhada para você vender um serviço de **IA que atua como secretária/vendedora no WhatsApp**, oferecendo:

- **Hero / Início**
  - Headline focada em donos de clínicas: transformar o WhatsApp em uma secretária que vende 24h por dia.
  - Subtítulo explicando pré-atendimento, aquecimento de leads e agenda cheia.
  - Botão de chamada para ação (CTA) para agendar demonstração.

- **Seção de Benefícios**
  - Mais consultas agendadas (atendimento 24/7).
  - Menos tempo da equipe presa no WhatsApp.
  - Atendimento profissional e padronizado.
  - Organização e histórico de pacientes.
  - Foco e cuidado com a área de saúde/estética (sem diagnóstico, sem promessas exageradas).

- **Como funciona**
  - 4 etapas claras: Diagnóstico, Configuração, Testes e Acompanhamento.
  - Explicações simples voltadas para tomadores de decisão (donos de clínicas, médicos, gestores).

- **Para quem é**
  - Clínicas de estética.
  - Consultórios médicos (dermato, nutro, emagrecimento, etc.).
  - Clínicas odontológicas.
  - Spas, depilação, micropigmentação e outros negócios de estética.

- **Planos / Formato de contratação**
  - Exemplo de **Plano Essencial** com preço base (para 1 clínica / 1 número de WhatsApp).
  - Exemplo de **Plano Growth** para redes e grupos de clínicas.
  - Lista do que está incluso em qualquer formato (diagnóstico, criação da persona da IA, fluxos, etc.).
  - Textos pensados como modelo de copy; você pode (e deve) adaptar preços e condições.

- **FAQ (Dúvidas frequentes)**
  - Acordeões com JavaScript (abre e fecha) respondendo:
    - Se a IA substitui a secretária.
    - Questões de segurança em saúde/estética.
    - Integração com agenda.
    - Tempo para ver resultados.
    - O que a clínica precisa ter pronto para começar.

- **Contato / CTA final**
  - Bloco com texto de convite para conversa.
  - Botão que leva diretamente para o WhatsApp (link pronto para ser trocado pelo seu número real).
  - Formulário simples que hoje usa `mailto:` para enviar dados por e-mail (pode ser substituído por integração com CRM ou backend próprio).

---

## 3. Tecnologias utilizadas

- **HTML5** – Estrutura da página.
- **Tailwind CSS via CDN** – Estilização rápida, responsiva e moderna:
  - Importado diretamente por `<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>`.
- **JavaScript puro (inline no `index.html`)** para:
  - Navegação suave para seções internas.
  - Menu mobile (abrir/fechar com ícone hamburguer).
  - Acordeão do FAQ (abre/fecha respostas e gira o ícone).
  - Efeito de sombra no cabeçalho ao rolar a página.
  - Alerta no envio do formulário avisando que o comportamento é apenas um modelo.

Não é necessário instalar nada para visualizar ou testar a página.

---

## 4. Como rodar localmente

1. **Clonar ou baixar** este repositório.
2. Abrir o arquivo `index.html` diretamente no navegador:
   - Clique duas vezes no arquivo, ou
   - Arraste-o para uma janela do navegador, ou
   - Sirva com um servidor simples (opcional), por exemplo:

   ```bash
   # Se tiver Python instalado
   python -m http.server 8000
   # depois acesse http://localhost:8000 no navegador
   ```

Por ser um projeto estático, não há necessidade de servidor backend para a visualização.

---

## 5. O que você deve personalizar

### 5.1. Marca e posicionamento

No `index.html`, personalize:

- **Título da página** (`<title>...</title>`).
- Nome/sigla da solução no canto superior esquerdo (onde aparece "Secretária IA").
- Pequenos textos de identidade: nome da solução, slogan, menções à sua empresa.

### 5.2. Contatos reais

1. **WhatsApp**

   Procure no `index.html` por algo como:

   ```html
   href="https://wa.me/5500000000000"
   ```

   Substitua pelo seu número real no formato internacional (sem `+` e sem separadores). Exemplos:

   - Brasil, São Paulo: `https://wa.me/5511999999999`
   - Brasil, outro DDD: `https://wa.me/5551999999999`

2. **E-mail do formulário**

   Procure no `index.html` pela tag `<form ...>` com o atributo `action`:

   ```html
   action="mailto:contato@suaempresa.com"
   ```

   Troque `contato@suaempresa.com` pelo seu e-mail comercial.

   > Observação: `mailto:` abre o aplicativo de e-mail do visitante. Em produção, é mais profissional integrar com um **backend**, um **serviço de formulários** (Formspree, Getform, etc.) ou um **CRM**.

### 5.3. Preços e planos

Na seção **Planos**, você verá preços e descrições de exemplo, como:

- Plano Essencial: `R$ 997/mês` (valor de referência).
- Texto "a partir de", condições, número de clínicas, etc.

Adapte:

- **Valores** e forma de cobrança (mensal, trimestral, setup inicial, etc.).
- **Benefícios** de cada plano conforme seu modelo de entrega.
- Se necessário, crie planos adicionais (por exemplo, "Plano Pro" ou "Plano Enterprise").

### 5.4. Textos (copy) para combinar com você

A estrutura está pronta, mas é recomendável ajustar a linguagem para combinar com:

- Seu público principal (mais estética? mais médicos? mais odontologia?).
- Seu estilo de comunicação (mais técnico, mais simples, mais agressivo em vendas, etc.).

Sugestão: comece pelo **Hero**, **Benefícios** e **Planos**, pois são os pontos de maior impacto na conversão.

---

## 6. Publicação (deploy)

Como é um projeto estático, você pode publicar facilmente em vários serviços gratuitos.

### 6.1. GitHub Pages

1. Crie um repositório no GitHub e envie (`push`) os arquivos.
2. No GitHub, vá em **Settings → Pages**.
3. Selecione o branch (ex.: `main` ou `master`) e a pasta raiz (`/`).
4. Salve e aguarde alguns minutos.
5. O GitHub fornecerá uma URL pública, algo como `https://seu-usuario.github.io/seu-repo`.

### 6.2. Vercel

1. Crie uma conta em https://vercel.com.
2. Importe o repositório do GitHub.
3. Vercel detecta automaticamente que é um projeto estático.
4. Clique em **Deploy** e use a URL gerada.

### 6.3. Netlify

1. Crie uma conta em https://www.netlify.com.
2. Faça o deploy arrastando e soltando a pasta do projeto no painel, ou conecte seu repositório Git.
3. Configure o site e use a URL gerada.

Todos esses serviços são suficientes para esta landing page.

---

## 7. Ideias de evolução

Se quiser transformar este site em algo mais avançado com o tempo, alguns caminhos possíveis:

1. **Integração real com formulários/CRM**
   - Substituir o `mailto:` por um endpoint backend (Node, Python, etc.) ou serviço de formulários.
   - Salvar leads em uma planilha, banco de dados ou CRM (HubSpot, Pipedrive, etc.).

2. **Teste A/B de textos**
   - Testar duas versões de headline, benefícios ou planos para ver qual converte melhor.

3. **Provas sociais**
   - Adicionar depoimentos de clínicas reais.
   - Exibir números: "X mensagens automatizadas por mês", "Y% mais agendamentos", etc.

4. **Blog ou materiais ricos**
   - Criar páginas com conteúdos educativos para donos de clínicas (melhorias de atendimento, organização de agenda, etc.), gerando tráfego orgânico.

---

## 8. Suporte e personalização

Este projeto foi construído como **modelo base** para vender um serviço de IA de secretária/vendedora focado em saúde e estética. Você é livre para:

- Adaptar textos, seções e estrutura.
- Traduzir para outro idioma, se quiser atingir outros mercados.
- Integrar com sua stack técnica de IA e WhatsApp.

Se você já tiver definido:

- O **nome** do seu produto/serviço.
- O **público principal** (ex.: só clínicas de estética; só odontologia; só dermatologia, etc.).
- A **faixa de preço** que quer praticar.

pode usar este README como checklist para ajustar o site e usá-lo imediatamente como sua landing page de vendas.