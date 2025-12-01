<?php
/**
 * Script de Autenticação de Acesso.
 * * Deve ser incluído no topo de todas as páginas restritas.
 * 1. Garante que a sessão está ativa.
 * 2. Verifica se o usuário está logado.
 * 3. Verifica se o perfil do usuário possui permissão de acesso.
 *
 * @author Módulo 6 - Banco de Dados II
 * @version 1.0 (Controle de Acesso por Sessão e Perfil)
 */

// Inclui funções e configurações
require_once 'config/config.php';
require_once 'includes/funcoes.php';

// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ========================================================
// 1. DEFINIÇÃO DE PERFIS PERMITIDOS
// ========================================================
// Por padrão, esta página (e as que a incluírem) só será acessível por:
$perfis_permitidos = ['admin', 'bibliotecario'];
$pagina_login = 'login.php';

// ========================================================
// 2. VERIFICAÇÃO DE LOGIN
// ========================================================

// Verifica se a variável de sessão de login está definida e é verdadeira
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    redirecionarComMensagem(
        $pagina_login,
        MSG_ERRO,
        'Você precisa fazer login para acessar esta página.'
    );
}

// ========================================================
// 3. VERIFICAÇÃO DE PERFIL
// ========================================================

$perfil_usuario = $_SESSION['user_perfil'] ?? '';

if (!in_array($perfil_usuario, $perfis_permitidos)) {
    // Tenta obter o nome do usuário para uma mensagem mais amigável
    $nome_usuario = $_SESSION['user_nome'] ?? 'Usuário';

    redirecionarComMensagem(
        $pagina_login,
        MSG_ERRO,
        "{$nome_usuario}, seu perfil ('{$perfil_usuario}') não tem permissão para esta área."
    );
}

// Se o script chegar aqui, o usuário está autenticado e autorizado.
// Nenhuma saída (output) deve ser gerada por este arquivo, apenas redirecionamentos.
?>