<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$db = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionarComMensagem('login.php', 'erro', 'Requisição inválida.');
}

$token = $_POST['token'] ?? '';
$senha = $_POST['senha'] ?? '';

if (strlen($senha) < 8) {
    redirecionarComMensagem('login.php', 'erro', 'A senha deve ter pelo menos 8 caracteres.');
}

$token_hash = hash('sha256', $token);

// Valida token novamente
$stmt = $db->prepare("
    SELECT id, reset_expira
    FROM usuario
    WHERE reset_token = :token
");
$stmt->execute([':token' => $token_hash]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    redirecionarComMensagem('login.php', 'erro', 'Token inválido.');
}

if (strtotime($usuario['reset_expira']) < time()) {
    redirecionarComMensagem('login.php', 'erro', 'Token expirado.');
}

// Atualiza senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $db->prepare("
    UPDATE usuario 
    SET senha_hash = :hash,
        reset_token = NULL,
        reset_expira = NULL
    WHERE id = :id
");

$stmt->execute([
    ':hash' => $senha_hash,
    ':id' => $usuario['id']
]);

redirecionarComMensagem('login.php', 'sucesso', 'Senha redefinida com sucesso!');
