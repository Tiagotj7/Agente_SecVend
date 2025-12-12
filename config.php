<?php
// WhatsApp Cloud API
define('WHATSAPP_TOKEN',      'SEU_TOKEN_DE_ACESSO');     // copie da Meta
define('WHATSAPP_PHONE_ID',   'SEU_PHONE_NUMBER_ID');     // ID do número
define('WHATSAPP_API_URL',    'https://graph.facebook.com/v20.0/');

// Token que você vai usar para validar o webhook na Meta
define('WEBHOOK_VERIFY_TOKEN', 'MEU_TOKEN_SECRETO_WEBHOOK');

// Banco de dados (XAMPP padrão)
define('DB_HOST',     'localhost');
define('DB_NAME',     'db_agente');
define('DB_USER',     'root');
define('DB_PASSWORD', ''); // em XAMPP geralmente é vazio

// OpenAI (IA) – substitua pela sua chave
define('OPENAI_API_KEY', 'SUA_CHAVE_OPENAI');