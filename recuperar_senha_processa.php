<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$db = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionarComMensagem('recuperar_senha.php', 'erro', 'Requisição inválida.');
}

$email = limparInput($_POST['email']);

if (!validarEmail($email)) {
    redirecionarComMensagem('recuperar_senha.php', 'erro', 'E-mail inválido.');
}

$stmt = $db->prepare("SELECT id_usuario FROM usuario WHERE email = :email AND ativo = 1");
$stmt->execute([':email' => $email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    redirecionarComMensagem('recuperar_senha.php', 'erro', 'E-mail não encontrado.');
}

// 🔐 Gerar token único
$token = bin2hex(random_bytes(32)); // 64 caracteres
$token_hash = hash('sha256', $token);

// Expira em 1 hora
$expira = date('Y-m-d H:i:s', time() + 3600);

// Atualiza no banco
$stmt = $db->prepare("
    UPDATE usuario 
    SET reset_token = :token, reset_expira = :expira
    WHERE id_usuario     = :id
");

$stmt->execute([
    ':token' => $token_hash,
    ':expira' => $expira,
    ':id' => $usuario['id_usuario']
]);

// 🔗 Link enviado ao usuário
$link = URL_BASE . "/redefinir_senha.php?token=" . $token;

// Simples envio de e-mail
$assunto = "Recuperação de Senha";
$mensagem = "Clique no link para redefinir sua senha:\n\n$link\n\nEste link expira em 1 hora.";

mail($email, $assunto, $mensagem);

redirecionarComMensagem(
    'login.php',
    'sucesso',
    'Um link de recuperação foi enviado para seu e-mail.'
);
