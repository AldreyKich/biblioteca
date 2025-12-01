<?php
/**
 * Listagem de Clientes (Visual Moderno e Discreto)
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/funcoes.php';
require_once __DIR__ . '/includes/header.php';

// Conexão
$db = Database::getInstance();
$pdo = $db->getConnection();

// ========================================
// PAGINAÇÃO
// ========================================
if (!defined('REGISTROS_POR_PAGINA')) {
    define('REGISTROS_POR_PAGINA', 10);
}

$por_pagina = REGISTROS_POR_PAGINA;
$pagina_atual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $por_pagina;


// ========================================
// FILTROS
// ========================================
$filtro_busca = isset($_GET['busca']) ? limparInput($_GET['busca']) : '';
$filtro_status = isset($_GET['status']) ? limparInput($_GET['status']) : '';

try {

    $where_clauses = [];
    $params = [];

    if (!empty($filtro_busca)) {
        $where_clauses[] = "(c.nome LIKE :busca OR c.email LIKE :busca)";
        $params['busca'] = "%$filtro_busca%";
    }

    if (!empty($filtro_status)) {
        $where_clauses[] = "c.status = :status";
        $params['status'] = $filtro_status;
    }

    $where_sql = count($where_clauses) > 0 ? " WHERE " . implode(" AND ", $where_clauses) : "";

    // Contagem
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes c $where_sql");
    $stmt->execute($params);
    $total_registros = $stmt->fetchColumn();
    $total_paginas = ceil($total_registros / $por_pagina);

    // Consulta
    $sql = "
        SELECT 
            c.*,
            (SELECT COUNT(*) FROM emprestimos WHERE cliente_id = c.id AND status = 'Ativo') AS emprestimos_ativos,
            (SELECT COUNT(*) FROM emprestimos WHERE cliente_id = c.id AND status = 'Ativo' AND data_devolucao_prevista < CURDATE()) AS emprestimos_atrasados,
            (SELECT COUNT(*) FROM emprestimos WHERE cliente_id = c.id) AS total_emprestimos
        FROM clientes c
        $where_sql
        ORDER BY c.nome
        LIMIT :limite OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $val) {
        $stmt->bindValue(":$key", $val);
    }

    $stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $clientes = $stmt->fetchAll();
?>

<!-- ========================================
     ESTILOS DO NOVO VISUAL DISCRETO
     ======================================== -->
<style>
    h1.page-title {
        font-size: 26px;
        font-weight: 600;
        color: #333;
        margin-bottom: 25px;
    }

    h1 {
        color: #2b2b2b;
        font-weight: 600;
        margin-bottom: 25px;
    }

    .card-modern {
        background: #ffffff;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid #e5e5e5;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .card-modern h3 {
        font-size: 18px;
        color: #444;
        margin-bottom: 20px;
        border-left: 4px solid #6c63ff;
        padding-left: 10px;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        border-radius: 10px;
        overflow: hidden;
    }

    .modern-table thead {
        background: #f3f4f6;
        color: #333;
    }

    .modern-table th, .modern-table td {
        padding: 14px;
        border-bottom: 1px solid #eee;
    }

    .modern-table tr:hover {
        background: #fafafa;
    }

    .btn-modern {
        border-radius: 6px;
        padding: 7px 14px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        display: inline-block;
        width: 100%;
        text-align: center;
    }

    .btn-normal {
        border-radius: 6px;
        padding: 7px 14px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }

    .btn-green { background: #4CAF50; color: white; }
    .btn-blue { background: #4f6ef7; color: white; }
    .btn-orange { background: #FFA500; color: white; }
    .btn-red { background: #E74C3C; color: white; }
    .btn-gray { background: #777; color: white; }

    /* NOVO: deixa os botões um embaixo do outro */
    .action-buttons a {
        display: block;
        width: 100%;
        margin: 4px 0;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }
    .badge-success { background:#4CAF50; color:white; }
    .badge-warning { background:#f1c40f; color:white; }
    .badge-danger { background:#E74C3C; color:white; }
    .badge-info { background:#3498db; color:white; }
</style>

<!-- TÍTULO -->
<h1>Gerenciamento de Clientes</h1>

<!-- BOTÃO NOVO CLIENTE -->
<div style="margin-bottom: 25px;">
    <a href="cliente_novo.php" class="btn btn-success">
        ➕ Cadastrar Novo Cliente
    </a>
</div>

<!-- ========================================
     FILTROS
     ======================================== -->
<div class="card-modern">
    <h3>🔍 Filtros de Busca</h3>
    <form method="GET" action="clientes.php">
        <div class="row">
            <div class="col">
                <label>Nome ou e-mail:</label>
                <input type="text" class="form-control"
                    name="busca"
                    value="<?= htmlspecialchars($filtro_busca) ?>"
                    placeholder="Digite para filtrar...">
            </div>

            <div class="col">
                <label>Status:</label>
                <select name="status" class="form-control">
                    <option value="">Todos</option>
                    <option value="Ativo" <?= $filtro_status=='Ativo'?'selected':'' ?>>Ativo</option>
                    <option value="Inativo" <?= $filtro_status=='Inativo'?'selected':'' ?>>Inativo</option>
                    <option value="Bloqueado" <?= $filtro_status=='Bloqueado'?'selected':'' ?>>Bloqueado</option>
                </select>
            </div>
        </div>

        <br>

        <button class="btn btn-primary btn-sm">Filtrar</button>
        <a href="clientes.php" class="btn btn-primary btn-sm btn-gray">Limpar</a>
    </form>
</div>

<!-- INFO DA LISTA -->
<p style="color:#555;">
    <?= $total_registros > 0 ? "Exibindo ".count($clientes)." de $total_registros clientes" : "Nenhum cliente encontrado" ?>
</p>

<!-- ========================================
     TABELA
     ======================================== -->
<?php if (count($clientes) > 0): ?>
<div class="table-responsive">
<table class="modern-table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Status</th>
            <th>Empréstimos</th>
            <th style="text-align:center; width:250px;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientes as $c): ?>
        <tr>
            <td>
                <strong><?= htmlspecialchars($c['nome']) ?></strong><br>
                <?php if ($c['cpf']): ?>
                <small style="color:#888;">CPF: <?= formatarCPF($c['cpf']) ?></small>
                <?php endif; ?>
            </td>

            <td>
                <a href="mailto:<?= $c['email'] ?>" style="color:#4f6ef7;">
                    <?= htmlspecialchars($c['email']) ?>
                </a>
            </td>

            <td><?= formatarTelefone($c['telefone']) ?></td>

            <td>
                <?php
                $badge = [
                    "Ativo" => "badge-success",
                    "Inativo" => "badge-warning",
                    "Bloqueado" => "badge-danger"
                ];
                ?>
                <span class="badge <?= $badge[$c['status']] ?? 'badge-info' ?>">
                    <?= $c['status'] ?>
                </span>
            </td>

            <td>
                <?php if ($c['emprestimos_ativos']): ?>
                    <span class="badge badge-info"><?= $c['emprestimos_ativos'] ?> ativo(s)</span><br>
                <?php endif; ?>

                <?php if ($c['emprestimos_atrasados']): ?>
                    <span class="badge badge-danger"><?= $c['emprestimos_atrasados'] ?> atrasado(s)</span><br>
                <?php endif; ?>

                <small style="color:#888;">Total: <?= $c['total_emprestimos'] ?></small>
            </td>

            <td align="center">
                <div class="action-buttons">

                    <a href="cliente_editar.php?id=<?= $c['id'] ?>" class="btn-modern btn-orange">
                        ✏️ Editar
                    </a>

                    <a href="cliente_emprestimos.php?id=<?= $c['id'] ?>" class="btn-modern btn-blue">
                        📋 Empréstimos
                    </a>

                    <a href="cliente_excluir.php?id=<?= $c['id'] ?>" class="btn-modern btn-red confirm-delete">
                        🗑️ Excluir
                    </a>

                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<!-- ========================================
     PAGINAÇÃO
     ======================================== -->
<?php if ($total_paginas > 1): ?>
<div style="text-align:center; margin:25px 0;">
    <?php for ($i=1; $i <= $total_paginas; $i++): ?>
        <?php if ($i == $pagina_atual): ?>
            <span class="btn-modern btn-blue"><?= $i ?></span>
        <?php else: ?>
            <a href="?pagina=<?= $i ?>&busca=<?= urlencode($filtro_busca) ?>&status=<?= urlencode($filtro_status) ?>"
               class="btn-modern btn-gray"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php else: ?>
<div class="card-modern" style="text-align:center;">
    <strong>Nenhum cliente encontrado.</strong><br><br>
    <a href="cliente_novo.php" class="btn-modern btn-green">Cadastrar Novo Cliente</a>
</div>
<?php endif; ?>

<?php

} catch (PDOException $e) {
    exibirMensagem('erro', 'Erro ao carregar clientes: ' . $e->getMessage());
}

require_once 'includes/footer.php';
?>
