<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/funcoes.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

// Buscar registros do banco
$sql = "SELECT
  e.id AS Emprestimos,
  c.nome AS Cliente,
  l.titulo AS Titulo,
  e.data_emprestimo,
  e.status
FROM emprestimos e
JOIN clientes c ON e.cliente_id = c.id
JOIN livros l ON e.livro_id = l.id;";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// IMPORTANTE: limpar qualquer saída anterior
ob_clean();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=relatorio_' . date('Y-m-d_H-i-s') . '.csv');

$output = fopen('php://output', 'w');

// BOM para UTF-8 (corrige acentuação no Excel)
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Cabeçalho
fputcsv($output, ['Emprestimo', 'Cliente', 'Livro', 'Data', 'Status'], ';');

// Dados
foreach ($emprestimos as $emp) {
    fputcsv($output, [
        $emp['Emprestimos'],
        $emp['Cliente'],            // <-- CORRIGIDO
        $emp['Titulo'],
        date('d/m/Y', strtotime($emp['data_emprestimo'])),
        $emp['status']
    ], ';');
}

fclose($output);
exit;
?>
