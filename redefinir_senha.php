<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$db = Database::getInstance()->getConnection();

$token = $_GET['token'] ?? '';

if (!$token) {
    redirecionarComMensagem('login.php', 'erro', 'Token inválido.');
}

$token_hash = hash('sha256', $token);

$stmt = $db->prepare("
    SELECT id, reset_expira
    FROM usuario
    WHERE reset_token = :token
");

$stmt->execute([':token' => $token_hash]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    redirecionarComMensagem('login.php', 'erro', 'Token inválido ou já utilizado.');
}

if (strtotime($usuario['reset_expira']) < time()) {
    redirecionarComMensagem('login.php', 'erro', 'Token expirado.');
}
?>

<h2>Redefinir Senha</h2>

<form method="POST" action="redefinir_senha_processa.php">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

    <label>Nova Senha:</label>
    <input type="password" name="senha" required minlength="8" class="form-control">

    <button type="submit" class="btn btn-success mt-3">Salvar Nova Senha</button>
</form>
