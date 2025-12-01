<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'includes/funcoes.php';

// Inicia sessão e verifica login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php");
    exit;
}

$db = Database::getInstance();
$pdo = $db->getConnection();

// =========================
// FILTROS
// =========================
$data_inicio = $_GET['data_inicio'] ?? date('Y-m-01');
$data_fim    = $_GET['data_fim'] ?? date('Y-m-t');
$sucesso     = $_GET['sucesso'] ?? 'todos';

// =========================
// EXPORTAR CSV
// =========================
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=auditoria_logins_' . date('Y-m-d_H-i-s') . '.csv');

    $output = fopen("php://output", "w");

    fputcsv($output, ['ID', 'Usuário', 'E-mail de Acesso', 'Data/Hora', 'IP', 'User Agent', 'Sucesso']);

    $sqlCSV = "
        SELECT a.*, u.nome AS nome_usuario
        FROM login_auditoria a
        LEFT JOIN usuario u ON u.id_usuario = a.id_usuario
        WHERE a.data_hora BETWEEN :inicio AND :fim
    ";

    if ($sucesso !== 'todos') {
        $sqlCSV .= " AND a.sucesso = :sucesso ";
    }

    $sqlCSV .= " ORDER BY a.data_hora DESC ";

    $stmtCSV = $pdo->prepare($sqlCSV);

    $stmtCSV->bindValue(':inicio', $data_inicio . " 00:00:00");
    $stmtCSV->bindValue(':fim', $data_fim . " 23:59:59");
    if ($sucesso !== 'todos') $stmtCSV->bindValue(':sucesso', $sucesso);

    $stmtCSV->execute();

    while ($row = $stmtCSV->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $row['id'],
            $row['nome_usuario'],
            $row['email_tentado'],
            $row['data_hora'],
            $row['ip'],
            $row['user_agent'],
            $row['sucesso'] ? 'Sim' : 'Não'
        ]);
    }

    fclose($output);
    exit;
}

// =========================
// CONSULTA PRINCIPAL
// =========================
$sql = "
    SELECT a.*, u.nome AS nome_usuario
    FROM login_auditoria a
    LEFT JOIN usuario u ON u.id_usuario = a.id_usuario
    WHERE a.data_hora BETWEEN :inicio AND :fim
";

if ($sucesso !== 'todos') {
    $sql .= " AND a.sucesso = :sucesso ";
}

$sql .= " ORDER BY a.data_hora DESC ";

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':inicio', $data_inicio . " 00:00:00");
$stmt->bindValue(':fim', $data_fim . " 23:59:59");
if ($sucesso !== 'todos') $stmt->bindValue(':sucesso', $sucesso);

$stmt->execute();
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Auditoria de Logins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">

    <h3 class="mb-4">📌 Auditoria de Logins</h3>

    <!-- Formulário de Filtros -->
    <form class="row g-3 mb-4" method="GET">

        <div class="col-md-3">
            <label class="form-label">Data Início</label>
            <input type="date" name="data_inicio" value="<?= htmlspecialchars($data_inicio) ?>" class="form-control" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Data Fim</label>
            <input type="date" name="data_fim" value="<?= htmlspecialchars($data_fim) ?>" class="form-control" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="sucesso" class="form-select">
                <option value="todos" <?= $sucesso=="todos"?"selected":"" ?>>Todos</option>
                <option value="1" <?= $sucesso=="1"?"selected":"" ?>>Somente Sucessos</option>
                <option value="0" <?= $sucesso=="0"?"selected":"" ?>>Somente Falhas</option>
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>

    </form>

    <!-- Botão Exportar CSV -->
    <div class="mb-3">
        <a href="?data_inicio=<?= $data_inicio ?>&data_fim=<?= $data_fim ?>&sucesso=<?= $sucesso ?>&exportar=csv"
           class="btn btn-success">
            Exportar CSV
        </a>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </div>

    <!-- Tabela -->
    <div class="table-responsive shadow-sm">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>E-mail de Acesso</th>
                    <th>Data/Hora</th>
                    <th>IP</th>
                    <th>Navegador</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= $r['nome_usuario'] ?: '<i>(não encontrado)</i>' ?></td>
                        <td><?= $r['email_tentado'] ?></td>
                        <td><?= $r['data_hora'] ?></td>
                        <td><?= $r['ip'] ?></td>
                        <td><?= substr($r['user_agent'], 0, 50) ?>...</td>
                        <td>
                            <?php if ($r['sucesso']): ?>
                                <span class="badge bg-success">Sucesso</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Falha</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($registros) === 0): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Nenhum registro encontrado.</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

</body>
</html>
