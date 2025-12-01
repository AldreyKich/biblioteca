<?php
/**
 * Listagem de Autores
 

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/funcoes.php';
require_once __DIR__ . '/includes/header.php';
*/

// index.php (arquivo principal)
require_once __DIR__ . '/includes/autenticacao.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/funcoes.php';

// inclui o header
require_once __DIR__ . '/includes/header.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

try {
    // BUSCAR AUTORES + TOTAL DE LIVROS
    $sql = "
        SELECT 
            a.*,
            COUNT(l.id) AS total_livros
        FROM autores a
        LEFT JOIN livros l ON a.id = l.autor_id
        GROUP BY a.id
        ORDER BY a.nome
    ";
    
    $stmt = $pdo->query($sql);
    $autores = $stmt->fetchAll();

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

<div class="container mt-4">
    
    <h1>Autores Cadastrados</h1>

    <div style="margin-bottom: 25px;">
    <a href="autor_novo.php" class="btn btn-success">
         ➕ Cadastrar Novo Autor
    </a>

    </div>

    <?php if (count($autores) > 0): ?>
        
        <p class="text-muted mb-4">
            Total de <?= count($autores) ?> autor(es) cadastrado(s)
        </p>

        <!-- GRID COM CARTÕES -->
        <div class="row g-4">
        <?php foreach ($autores as $autor): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-body">

                        <h4 class="card-title text-primary mb-2">
                            <?= htmlspecialchars($autor['nome']) ?>
                        </h4>

                        <?php if ($autor['nacionalidade']): ?>
                            <p class="mb-1 text-muted">
                                <strong>🌍 Nacionalidade:</strong> 
                                <?= htmlspecialchars($autor['nacionalidade']) ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($autor['data_nascimento']): ?>
                            <p class="mb-1 text-muted">
                                <strong>📅 Nascimento:</strong> 
                                <?= formatarData($autor['data_nascimento']) ?>
                            </p>
                        <?php endif; ?>

                        <p class="mt-2">
                            <span class="badge bg-info text-dark">
                                📚 <?= $autor['total_livros'] ?> livro(s)
                            </span>
                        </p>

                        <?php if ($autor['biografia']): ?>
                        <div class="bg-light p-3 rounded mt-3">
                            <strong>Biografia:</strong>
                            <p class="small text-muted mt-1 mb-0">
                                <?= resumirTexto($autor['biografia'], 150) ?>
                            </p>
                        </div>
                        <?php endif; ?>

                        <!-- AÇÕES (botões em coluna) -->
                        <div class="d-flex flex-column gap-2 mt-4">

                            <a href="autor_livros.php?id=<?= $autor['id'] ?>" 
                               class="btn btn-info w-100">
                                📚 Ver Livros
                            </a>

                            <a href="autor_editar.php?id=<?= $autor['id'] ?>" 
                               class="btn btn-warning text-dark w-100">
                                ✏️ Editar
                            </a>

                            <?php if ($autor['total_livros'] == 0): ?>
                                <a href="autor_excluir.php?id=<?= $autor['id'] ?>" 
                                   class="btn btn-danger w-100"
                                   onclick="return confirm('Excluir o autor <?= htmlspecialchars($autor['nome']) ?>?')">
                                    🗑️ Excluir
                                </a>
                            <?php else: ?>
                                <button class="btn btn-danger w-100" disabled
                                        title="Não é possível excluir autor com livros cadastrados"
                                        style="opacity: .6; cursor: not-allowed;">
                                    🗑️ Excluir
                                </button>
                            <?php endif; ?>

                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

    <?php else: ?>

        <div class="alert alert-info">
            <strong>ℹ️ Nenhum autor cadastrado.</strong><br>
            Comece <a href="autor_novo.php">cadastrando um novo autor</a>.
        </div>

    <?php endif; ?>

</div>

<?php

} catch (PDOException $e) {
    exibirMensagem('erro', 'Erro ao carregar autores: ' . $e->getMessage());
}

require_once 'includes/footer.php';
?>
