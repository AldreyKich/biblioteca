<?php
// ============================================================================
// CONFIGURAÇÃO DO SISTEMA DE BIBLIOTECA
// Arquivo: config/config.php
// Descrição: Define constantes globais, caminhos e parâmetros do sistema.
// ============================================================================


// ============================================================================
// 🔧 CONFIGURAÇÕES GERAIS
// ============================================================================

// Nome da biblioteca (usado em títulos, cabeçalhos, etc.)
if (!defined('NOME_BIBLIOTECA')) {
    define('NOME_BIBLIOTECA', 'Sistema Completo de Biblioteca - Módulo 6 - PHP e MySQL');
}

// Número de registros exibidos por página (paginação)
if (!defined('REGISTROS_POR_PAGINA')) {
    define('REGISTROS_POR_PAGINA', 10);
}

// Limite máximo de empréstimos por cliente
if (!defined('LIMITE_EMPRESTIMOS_CLIENTE')) {
    define('LIMITE_EMPRESTIMOS_CLIENTE', 3);
}

// Prazo padrão de empréstimo (em dias)
if (!defined('PRAZO_EMPRESTIMO_DIAS')) {
    define('PRAZO_EMPRESTIMO_DIAS', 7);
}

// Valor da multa cobrada por dia de atraso (em reais)
if (!defined('VALOR_MULTA_DIA')) {
    define('VALOR_MULTA_DIA', 2.50);
}


// ============================================================================
// 💬 MENSAGENS DO SISTEMA
// ============================================================================
// Usadas para identificar o tipo de feedback exibido ao usuário.

if (!defined('MSG_SUCESSO')) define('MSG_SUCESSO', 'sucesso');
if (!defined('MSG_ERRO'))    define('MSG_ERRO', 'erro');
if (!defined('MSG_AVISO'))   define('MSG_AVISO', 'aviso');
if (!defined('MSG_INFO'))    define('MSG_INFO', 'info');


// ============================================================================
// 📁 CAMINHOS PADRÕES DO PROJETO
// ============================================================================
// Servem para facilitar includes e require_once em diferentes diretórios.

if (!defined('CAMINHO_BASE')) {
    define('CAMINHO_BASE', __DIR__ . '/../');
}
if (!defined('CAMINHO_INCLUDES')) {
    define('CAMINHO_INCLUDES', CAMINHO_BASE . 'includes/');
}
if (!defined('CAMINHO_TEMPLATES')) {
    define('CAMINHO_TEMPLATES', CAMINHO_BASE . 'templates/');
}

// Versão do sistema (semântico: major.minor.patch)
if (!defined('VERSAO_SISTEMA')) {
    define('VERSAO_SISTEMA', '1.0.0');
}
define('URL_BASE', 'http://localhost/biblioteca');

// ============================================================================
// 🐞 MODO DE DEPURAÇÃO
// ============================================================================
// Quando true, exibe mensagens de erro detalhadas (para desenvolvimento).
// Quando false, oculta detalhes sensíveis (para produção).

if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', true);
}
