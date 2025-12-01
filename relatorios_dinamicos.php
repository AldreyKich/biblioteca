<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/funcoes.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

// Filtros
$data_inicio = $_GET['data_inicio'] ?? date('Y-m-01');
$data_fim = $_GET['data_fim'] ?? date('Y-m-t');
$status = $_GET['status'] ?? 'todos';

// SELECT com joins
$sql = "SELECT 
            e.*,
            c.nome AS cliente,
            l.titulo AS livro
        FROM emprestimos e
        INNER JOIN clientes c ON e.cliente_id = c.id
        INNER JOIN livros l ON e.livro_id = l.id
        WHERE e.data_emprestimo BETWEEN :data_inicio AND :data_fim";

if ($status != 'todos') {
    $sql .= " AND e.status = :status";
}

$sql .= " ORDER BY e.data_emprestimo DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':data_inicio', $data_inicio);
$stmt->bindValue(':data_fim', $data_fim);

if ($status != 'todos') {
    $stmt->bindValue(':status', $status);
}

$stmt->execute();
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estatísticas
$total = count($emprestimos);
$ativos = array_filter($emprestimos, fn($e) => $e['status'] === 'Ativo');
$total_ativos = count($ativos);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatórios Dinâmicos</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<h3>Relatório de Empréstimos</h3>

<p><strong>Total:</strong> <?= $total ?>  
 | <strong>Ativos:</strong> <?= $total_ativos ?></p>

 <form method="GET" class="row g-3 mb-4">

    <div class="col-md-3">
        <label class="form-label">Data Início</label>
        <input type="date" name="data_inicio" class="form-control"
               value="<?= $data_inicio ?>">
    </div>

    <div class="col-md-3">
        <label class="form-label">Data Fim</label>
        <input type="date" name="data_fim" class="form-control"
               value="<?= $data_fim ?>">
    </div>

    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="todos" <?= ($status == 'todos') ? 'selected' : '' ?>>Todos</option>
            <option value="Ativo" <?= ($status == 'Ativo') ? 'selected' : '' ?>>Ativo</option>
            <option value="Devolvido" <?= ($status == 'Devolvido') ? 'selected' : '' ?>>Devolvido</option>
        </select>
    </div>

    <!--<div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-50">Filtrar</button>
       
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </div>-->

    <div>
        <button class="btn btn-primary btn-sm">Filtrar</button>
        <a href="index.php" class="btn btn-primary btn-sm">Voltar</a>

</div>


</form>


<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Livro</th>
            <th>Data Empréstimo</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($emprestimos as $emp): ?>
            <tr>
                <td><?= $emp['id'] ?></td>
                <td><?= $emp['cliente'] ?></td>
                <td><?= $emp['livro'] ?></td>
                <td><?= date('d/m/Y', strtotime($emp['data_emprestimo'])) ?></td>
                <td><?= $emp['status'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
