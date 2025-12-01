<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/funcoes.php';

// Inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Perfis permitidos
$perfis_permitidos = ['admin', 'bibliotecario'];
$pagina_login = 'login.php';
$pagina_principal = 'index.php';

// Função auxiliar para registrar auditoria de login
function registrarAuditoria($pdo, $id_usuario, $email, $sucesso) {
    $stmt = $pdo->prepare("
        INSERT INTO login_auditoria 
        (id_usuario, email_tentado, data_hora, ip, user_agent, sucesso)
        VALUES (:id_usuario, :email, NOW(), :ip, :ua, :sucesso)
    ");

    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':email'      => $email,
        ':ip'         => $_SERVER['REMOTE_ADDR'],
        ':ua'         => $_SERVER['HTTP_USER_AGENT'],
        ':sucesso'    => $sucesso
    ]);
}

// ===============================
// 1. MÉTODO E VALIDAÇÃO INICIAL
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: {$pagina_login}");
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    redirecionarComMensagem(
        $pagina_login,
        MSG_ERRO,
        'E-mail e senha são obrigatórios.'
    );
}

// ===============================
// 2. BUSCA DO USUÁRIO
// ===============================
try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    $sql = "SELECT id_usuario, nome, email, senha_hash, perfil, ativo 
            FROM usuario 
            WHERE email = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    redirecionarComMensagem(
        $pagina_login,
        MSG_ERRO,
        'Erro ao tentar autenticar. Tente novamente mais tarde.'
    );
}

// ===============================
// 3. VALIDAÇÕES E AUDITORIA
// ===============================

// 3.1 Usuário não encontrado
if (!$usuario) {

    registrarAuditoria($pdo, null, $email, 0);

    redirecionarComMensagem(
        $pagina_login,
        MSG_ERRO,
        'Credenciais inválidas. Verifique seu e-mail e senha.'
    );
}

// 3.2 Senha incorreta
if (!password_verify($senha, $usuario['senha_hash'])) {

    registrarAuditoria($pdo, $usuario['id_usuario'], $email, 0);

    redirecionarComMensagem(
        $pagina_login,
        MSG_ERRO,
        'Credenciais inválidas. Verifique seu e-mail e senha.'
    );
}

// 3.3 Conta inativa
if ($usuario['ativo'] != 1) {

    registrarAuditoria($pdo, $usuario['id_usuario'], $email, 0);

    redirecionarComMensagem(
        $pagina_login,
        MSG_ERRO,
        'Sua conta está inativa. Contate o administrador.'
    );
}

// 3.4 Perfil sem permissão
if (!in_array($usuario['perfil'], $perfis_permitidos)) {

    registrarAuditoria($pdo, $usuario['id_usuario'], $email, 0);

    redirecionarComMensagem(
        $pagina_login,
        MSG_ERRO,
        'Seu perfil não tem permissão para acessar esta área.'
    );
}

// ===============================
// 4. LOGIN COM SUCESSO
// ===============================

registrarAuditoria($pdo, $usuario['id_usuario'], $email, 1);

// Define a sessão
$_SESSION['usuario_logado'] = true;
$_SESSION['user_id']        = $usuario['id_usuario'];
$_SESSION['user_nome']      = $usuario['nome'];
$_SESSION['user_perfil']    = $usuario['perfil'];

// Redireciona
redirecionarComMensagem(
    $pagina_principal,
    MSG_SUCESSO,
    "Bem-vindo(a), {$usuario['nome']}! Seu perfil é '{$usuario['perfil']}'."
);
