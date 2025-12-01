<?php
/**
 * Funções Auxiliares Comuns (Funcoes.php)
 * Inclui funções para manipulação de mensagens e redirecionamento, validações,
 * formatação e utilidades gerais.
 */

// Garante que a sessão está iniciada para usar $_SESSION
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ========================================
// FUNÇÕES DE REDIRECIONAMENTO E MENSAGENS
// ========================================

/**
 * Redireciona para uma URL e armazena uma mensagem de feedback na URL (GET).
 * * @param string $url URL para redirecionar.
 * @param string $tipo Tipo de mensagem (e.g., 'sucesso', 'erro', 'aviso', 'info').
 * @param string $texto Conteúdo da mensagem.
 */
function redirecionarComMensagem(string $url, string $tipo, string $texto): void {
    // Codifica a URL antes de anexar os parâmetros, garantindo caracteres especiais.
    $texto_url = urlencode($texto);
    
    // Anexa os parâmetros de mensagem na URL
    $destino = $url . "?msg_tipo=" . urlencode($tipo) . "&msg=" . $texto_url;
    
    header("Location: $destino");
    exit;
}

/**
 * Obtém a mensagem de feedback da URL.
 * * @return array|null Retorna um array ['tipo' => ..., 'mensagem' => ...] ou null.
 */
function obterMensagem(): ?array {
    // Verifica se há parâmetros de mensagem na URL
    if (isset($_GET['msg_tipo']) && isset($_GET['msg'])) {
        // CORRIGIDO: O array é retornado com a chave 'mensagem' para uso na função verificarExibirMensagens()
        return [
            // Sanitiza o tipo e decodifica a URL para o texto
            'tipo' => htmlspecialchars($_GET['msg_tipo']),
            // Usa 'mensagem' como chave e decodifica o texto
            'mensagem' => htmlspecialchars(urldecode($_GET['msg'])), 
        ];
    }
    
    return null; // Retorna null se não houver mensagem na URL
}

/**
 * Exibe uma mensagem formatada para o usuário (HTML estilizado)
 * * @param string $tipo Tipo da mensagem (sucesso, erro, aviso, info)
 * @param string $mensagem Texto da mensagem (já sanitizado)
 * @return void (imprime HTML diretamente)
 */
function exibirMensagem(string $tipo, string $mensagem): void {
    // Definimos cores e ícones para cada tipo de mensagem
    $cores = [
        'sucesso' => ['bg' => '#d4edda', 'borda' => '#28a745', 'texto' => '#155724', 'icone' => '✓'],
        'erro'    => ['bg' => '#f8d7da', 'borda' => '#dc3545', 'texto' => '#721c24', 'icone' => '✗'],
        'aviso'   => ['bg' => '#fff3cd', 'borda' => '#ffc107', 'texto' => '#856404', 'icone' => '⚠'],
        'info'    => ['bg' => '#d1ecf1', 'borda' => '#17a2b8', 'texto' => '#0c5460', 'icone' => 'ℹ']
    ];
    
    $tipo_limpo = strtolower($tipo);
    $cor = $cores[$tipo_limpo] ?? $cores['info'];

    // Imprime a mensagem com estilos inline para garantir visualização em qualquer ambiente
    echo "<div class='container mt-3' style='max-width: 90%; margin: 15px auto;'>";
    echo "<div style='background-color:{$cor['bg']}; 
                      color:{$cor['texto']}; 
                      padding:15px 20px; 
                      border-left:4px solid {$cor['borda']};
                      border-radius:4px;
                      display:flex;
                      align-items:center;
                      gap:10px;
                      font-family: sans-serif;'>";
    echo "<strong style='font-size:20px;'>{$cor['icone']}</strong>";
    echo "<span>{$mensagem}</span>";
    echo "</div>";
    echo "</div>";
}

/**
 * Verifica se há mensagens na URL (GET) e as exibe.
 * * @return void
 */
function verificarExibirMensagens(): void {
    $mensagem = obterMensagem();
    // A chave 'mensagem' agora está garantida pela função obterMensagem()
    if ($mensagem) {
        // Exibe a mensagem formatada
        exibirMensagem($mensagem['tipo'], $mensagem['mensagem']);
    }
}


// ========================================
// FUNÇÕES DE VALIDAÇÃO E SANITIZAÇÃO
// ========================================

/**
 * Função para limpar e validar inputs (exemplo básico)
 * * @param string $dados O valor a ser limpo.
 * @return string O valor limpo.
 */
function limparInput(string $dados): string {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');
    return $dados;
}

/**
 * Valida um endereço de e-mail
 * * @param string $email E-mail a ser validado
 * @return bool TRUE se válido, FALSE se inválido
 */
function validarEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida um CPF brasileiro (com cálculo de dígitos verificadores)
 * * @param string $cpf CPF a ser validado
 * @return bool TRUE se válido, FALSE se inválido
 */
function validarCPF(string $cpf): bool {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if (strlen($cpf) != 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }
    
    // Validação dos dígitos verificadores
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += (int)$cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    
    return true;
}


// ========================================
// FUNÇÕES DE FORMATAÇÃO
// ========================================

/**
 * Formata uma data do formato MySQL para o formato brasileiro
 * * @param string $data Data no formato Y-m-d (2025-11-06)
 * @return string Data no formato d/m/Y (06/11/2025) ou '-' se vazia
 */
function formatarData(string $data): string {
    if (empty($data) || $data == '0000-00-00') {
        return '-';
    }
    return date('d/m/Y', strtotime($data));
}

/**
 * Formata uma data e hora completa
 * * @param string $dataHora DateTime no formato MySQL
 * @return string Data e hora formatadas ou '-' se vazia
 */
function formatarDataHora(string $dataHora): string {
    if (empty($dataHora)) {
        return '-';
    }
    return date('d/m/Y \à\s H:i', strtotime($dataHora));
}

/**
 * Formata um valor numérico para moeda brasileira
 * * @param float $valor Valor numérico
 * @return string Valor formatado como R$ 0,00
 */
function formatarMoeda(float $valor): string {
    // number_format(número, casas decimais, separador decimal, separador de milhares)
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

/**
 * Formata um telefone no padrão brasileiro
 * * @param string $telefone Telefone sem formatação
 * @return string Telefone formatado (00) 00000-0000 ou (00) 0000-0000
 */
function formatarTelefone(string $telefone): string {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    
    if (strlen($telefone) == 11) {
        // Celular: (00) 00000-0000
        return '(' . substr($telefone, 0, 2) . ') ' . 
               substr($telefone, 2, 5) . '-' . 
               substr($telefone, 7, 4);
    } elseif (strlen($telefone) == 10) {
        // Fixo: (00) 0000-0000
        return '(' . substr($telefone, 0, 2) . ') ' . 
               substr($telefone, 2, 4) . '-' . 
               substr($telefone, 6, 4);
    }
    
    return $telefone;
}

/**
 * Formata um CPF
 * * @param string $cpf CPF sem formatação
 * @return string CPF formatado 000.000.000-00
 */
function formatarCPF(string $cpf): string {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if (strlen($cpf) == 11) {
        return substr($cpf, 0, 3) . '.' . 
               substr($cpf, 3, 3) . '.' . 
               substr($cpf, 6, 3) . '-' . 
               substr($cpf, 9, 2);
    }
    
    return $cpf;
}

// ========================================
// FUNÇÕES DE CÁLCULO
// ========================================

/**
 * Calcula quantos dias de atraso há em uma devolução
 * * @param string $data_prevista Data prevista de devolução (Y-m-d)
 * @return int Número de dias de atraso (0 se não há atraso)
 */
function calcularDiasAtraso(string $data_prevista): int {
    try {
        $hoje = new DateTime(date('Y-m-d'));
        $prevista = new DateTime($data_prevista);
    } catch (Exception $e) {
        return 0; 
    }
    
    if ($hoje > $prevista) {
        $intervalo = $hoje->diff($prevista);
        return $intervalo->days;
    }
    
    return 0; // Não há atraso
}

/**
 * Calcula o valor da multa baseado nos dias de atraso
 * * @param int $dias_atraso Número de dias de atraso
 * @return float Valor da multa
 */
function calcularMulta(int $dias_atraso): float {
    if ($dias_atraso <= 0) {
        return 0.00;
    }
    
    // Assume que VALOR_MULTA_DIA é uma constante definida em config.php ou similar
    $valor_multa_dia = defined('VALOR_MULTA_DIA') ? VALOR_MULTA_DIA : 0.50; 

    return $dias_atraso * $valor_multa_dia;
}

/**
 * Calcula a data de devolução prevista
 * * @param string|null $data_emprestimo Data do empréstimo (Y-m-d). Padrão: hoje.
 * @param int|null $dias_prazo Número de dias de prazo. Padrão: constante do sistema (7).
 * @return string Data prevista de devolução (Y-m-d)
 */
function calcularDataDevolucao(?string $data_emprestimo = null, ?int $dias_prazo = null): string {
    if ($data_emprestimo === null) {
        $data_emprestimo = date('Y-m-d');
    }
    
    // Assume que PRAZO_EMPRESTIMO_DIAS é uma constante definida em config.php ou similar
    $dias_prazo = $dias_prazo ?? (defined('PRAZO_EMPRESTIMO_DIAS') ? PRAZO_EMPRESTIMO_DIAS : 7);
    
    return date('Y-m-d', strtotime($data_emprestimo . " +{$dias_prazo} days"));
}

// ========================================
// FUNÇÕES DE UTILIDADE GERAL
// ========================================

/**
 * Gera um resumo de texto (truncate)
 * * @param string $texto Texto completo
 * @param int $limite Número máximo de caracteres
 * @param string $complemento String a adicionar no final (padrão: "...")
 * @return string Texto resumido
 */
function resumirTexto(string $texto, int $limite = 100, string $complemento = '...'): string {
    if (strlen($texto) <= $limite) {
        return $texto;
    }
    
    return substr($texto, 0, $limite) . $complemento;
}

/**
 * Retorna a classe CSS baseada no status de um empréstimo
 * * @param string $status Status do empréstimo
 * @return string Nome da classe CSS
 */
function obterClasseStatus(string $status): string {
    $classes = [
        'Ativo' => 'status-ativo',
        'Devolvido' => 'status-devolvido',
        'Atrasado' => 'status-atrasado',
        'Cancelado' => 'status-cancelado'
    ];
    
    return $classes[$status] ?? 'status-default';
}

/**
 * Debug melhorado - exibe variáveis de forma legível
 * * @param mixed $variavel Qualquer variável para debug
 * @param bool $die Se TRUE, para a execução após exibir
 * @return void
 * Use apenas em DESENVOLVIMENTO!
 */
function debug($variavel, bool $die = false): void {
    echo '<pre style="background:#f4f4f4; padding:15px; border:1px solid #ddd; margin:10px 0; font-family: monospace; font-size: 14px; white-space: pre-wrap;">';
    print_r($variavel);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}