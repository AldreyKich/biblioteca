<?php
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
    $sql = "
        SELECT
            (SELECT COUNT(*) FROM livros) AS total_livros,
            (SELECT SUM(quantidade_total) FROM livros) AS total_exemplares,
            (SELECT SUM(quantidade_disponivel) FROM livros) AS exemplares_disponiveis,
            (SELECT COUNT(*) FROM clientes WHERE status = 'Ativo') AS total_clientes,
            (SELECT COUNT(*) FROM autores) AS total_autores,
            (SELECT COUNT(*) FROM emprestimos WHERE status = 'Ativo') AS emprestimos_ativos,
            (SELECT COUNT(*) FROM emprestimos WHERE status = 'Ativo' AND data_devolucao_prevista < CURDATE()) AS emprestimos_atrasados
    ";
    $stmt = $pdo->query($sql);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $stats = [
        'total_livros' => 0,
        'total_exemplares' => 0,
        'exemplares_disponiveis' => 0,
        'total_clientes' => 0,
        'total_autores' => 0,
        'emprestimos_ativos' => 0,
        'emprestimos_atrasados' => 0
    ];
    error_log('Erro SQL index.php: ' . $e->getMessage());
}
?>

<style>
    /* ---- Estilo mais profissional e discreto ---- */

    h1 {
        font-weight: 600;
        color: #2c3e50;
    }

    .card-stat {
        border-radius: 12px;
        padding: 20px;
        background: #f8f9fa;
        border: 1px solid #e3e6ea;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all .2s;
    }
    .card-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .card-stat .fs-1 {
        color: #2c3e50;
    }
    .site-footer {
        margin-top: 40px;
        padding: 15px 0;
        font-size: 0.9rem;
        color: #6c757d;
        text-align: center;
    }
    .table-hover tbody tr:hover {
        background: #f6f7f9;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- menu lateral já existente -->
        <div class="d-none d-md-block col-md-3 col-lg-2"></div>

        <!-- Conteúdo principal -->
        <div class="col-12 col-md-9 col-lg-10">
            <h1>Bem-vindo ao Sistema de Biblioteca</h1>
            <p class="text-muted">Gerencie livros, clientes e empréstimos de forma prática e organizada.</p>

            <?php if (!empty($stats['emprestimos_atrasados']) && $stats['emprestimos_atrasados'] > 0): ?>
                <div class="alert alert-warning border-start border-4 border-danger">
                    <strong>⚠ Atenção:</strong>
                    Existem <strong><?= intval($stats['emprestimos_atrasados']) ?></strong> empréstimos atrasados.
                    <a href="emprestimos.php?filtro=atrasados" class="ms-2">Ver detalhes</a>
                </div>
            <?php endif; ?>

            <!-- Cards estatísticos -->
            <div class="row g-3 my-3">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card-stat">
                        <div class="fs-1 fw-bold"><?= number_format($stats['total_livros'] ?? 0) ?></div>
                        <div class="small text-muted">Títulos cadastrados</div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card-stat">
                        <div class="fs-1 fw-bold"><?= number_format($stats['exemplares_disponiveis'] ?? 0) ?></div>
                        <div class="small text-muted">Exemplares disponíveis</div>
                        <div class="small text-muted opacity-75">de <?= number_format($stats['total_exemplares'] ?? 0) ?></div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card-stat">
                        <div class="fs-1 fw-bold"><?= number_format($stats['total_clientes'] ?? 0) ?></div>
                        <div class="small text-muted">Clientes ativos</div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card-stat">
                        <div class="fs-1 fw-bold"><?= number_format($stats['emprestimos_ativos'] ?? 0) ?></div>
                        <div class="small text-muted">Empréstimos em andamento</div>
                    </div>
                </div>
            </div>

            <!-- Ações rápidas -->
            <div class="bg-white p-3 rounded shadow-sm border my-3">
                <h5 class="mb-2"> Ações Rápidas</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="emprestimo_novo.php" class="btn btn-primary btn-sm">➕ Novo Empréstimo</a>
                    <a href="cliente_novo.php" class="btn btn-outline-primary btn-sm">➕ Cadastrar Cliente</a>
                    <a href="livro_novo.php" class="btn btn-outline-secondary btn-sm">➕ Cadastrar Livro</a>
                    <a href="autor_novo.php" class="btn btn-outline-secondary btn-sm">➕ Cadastrar Autor</a>
                </div>
            </div>

            <!-- Últimos Livros -->
            <section class="my-3">
                <h5 class="mb-2"> Últimos Livros Cadastrados</h5>

                <?php
                try {
                    $sql2 = "
                        SELECT l.id, l.titulo, a.nome AS autor, l.ano_publicacao,
                               l.quantidade_disponivel, l.quantidade_total
                        FROM livros l
                        INNER JOIN autores a ON l.autor_id = a.id
                        ORDER BY l.id DESC
                        LIMIT 5
                    ";
                    $stmt2 = $pdo->query($sql2);
                    $ultimos_livros = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $ultimos_livros = [];
                }
                ?>

                <?php if (count($ultimos_livros) > 0): ?>
                    <div class="table-responsive rounded shadow-sm">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Autor</th>
                                    <th>Ano</th>
                                    <th>Disponibilidade</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($ultimos_livros as $livro): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($livro['titulo']) ?></strong></td>
                                    <td><?= htmlspecialchars($livro['autor']) ?></td>
                                    <td><?= htmlspecialchars($livro['ano_publicacao']) ?></td>
                                    <td>
                                        <?php if ($livro['quantidade_disponivel'] > 0): ?>
                                            <span class="badge bg-success">
                                                <?= $livro['quantidade_disponivel'] ?>
                                                / <?= $livro['quantidade_total'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Indisponível</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="livro_detalhes.php?id=<?= $livro['id'] ?>"
                                           class="btn btn-sm btn-outline-primary">Ver</a>

                                        <?php if ($livro['quantidade_disponivel'] > 0): ?>
                                            <a href="emprestimo_novo.php?livro_id=<?= $livro['id'] ?>"
                                               class="btn btn-sm btn-primary">Emprestar</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Nenhum livro cadastrado ainda.</p>
                <?php endif; ?>
            </section>

            <!-- Top 5 mais emprestados -->
            <section class="my-4">
                <h5 class="mb-2"> Top 5 Livros Mais Emprestados</h5>

                <?php
                try {
                    $sql3 = "
                        SELECT l.id, l.titulo, a.nome AS autor, COUNT(e.id) AS total_emprestimos
                        FROM livros l
                        INNER JOIN autores a ON l.autor_id = a.id
                        LEFT JOIN emprestimos e ON l.id = e.livro_id
                        GROUP BY l.id
                        HAVING total_emprestimos > 0
                        ORDER BY total_emprestimos DESC
                        LIMIT 5
                    ";
                    $stmt3 = $pdo->query($sql3);
                    $top_livros = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $top_livros = [];
                }
                ?>

                <?php if (count($top_livros) > 0): ?>
                    <div class="table-responsive rounded shadow-sm">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:80px;" class="text-center">Posição</th>
                                    <th>Título</th>
                                    <th>Autor</th>
                                    <th class="text-center">Empréstimos</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $pos = 1; foreach ($top_livros as $livro): ?>
                                <tr>
                                    <td class="text-center fw-bold">#<?= $pos ?></td>
                                    <td><strong><?= htmlspecialchars($livro['titulo']) ?></strong></td>
                                    <td><?= htmlspecialchars($livro['autor']) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">
                                            <?= $livro['total_emprestimos'] ?> vezes
                                        </span>
                                    </td>
                                </tr>
                            <?php $pos++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Nenhum empréstimo ainda.</p>
                <?php endif; ?>
            </section>

            <footer class="site-footer">
                &copy; <?= date('Y') ?> - Sistema de Biblioteca. Todos os direitos reservados.
            </footer>

        </div>
    </div>
</div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
