<?php
/**
 * Listagem de Livros – Layout Moderno e Discreto
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/funcoes.php';
require_once __DIR__ . '/includes/header.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

// Diretório das capas
define('DIRETORIO_CAPAS', 'uploads/capas/');

// PAGINAÇÃO
$por_pagina = REGISTROS_POR_PAGINA;
$pagina_atual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($pagina_atual - 1) * $por_pagina;

// FILTROS
$filtro_busca = $_GET['busca'] ?? '';
$filtro_autor = isset($_GET['autor']) ? (int)$_GET['autor'] : 0;
$filtro_categoria = $_GET['categoria'] ?? '';
$filtro_disponivel = isset($_GET['disponivel']) ? (int)$_GET['disponivel'] : 0;

try {

    // BUSCAR CATEGORIAS
    $stmt = $pdo->query("SELECT DISTINCT categoria FROM livros WHERE categoria IS NOT NULL ORDER BY categoria");
    $categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // BUSCAR AUTORES
    $stmt = $pdo->query("SELECT id, nome FROM autores ORDER BY nome");
    $autores = $stmt->fetchAll();

    // WHERE DINÂMICO
    $where = [];
    $params = [];

    if ($filtro_busca !== '') {
        $where[] = "l.titulo LIKE :busca";
        $params['busca'] = "%$filtro_busca%";
    }

    if ($filtro_autor > 0) {
        $where[] = "l.autor_id = :autor_id";
        $params['autor_id'] = $filtro_autor;
    }

    if ($filtro_categoria !== '') {
        $where[] = "l.categoria = :categoria";
        $params['categoria'] = $filtro_categoria;
    }

    if ($filtro_disponivel == 1) {
        $where[] = "l.quantidade_disponivel > 0";
    }

    $where_sql = count($where) ? " WHERE " . implode(" AND ", $where) : "";

    // CONTAR
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM livros l $where_sql");
    $stmt->execute($params);
    $total_registros = $stmt->fetchColumn();
    $total_paginas = ceil($total_registros / $por_pagina);

    // CONSULTA FINAL
    $sql = "
        SELECT 
            l.*,
            a.nome AS autor_nome
        FROM livros l
        INNER JOIN autores a ON l.autor_id = a.id
        $where_sql
        ORDER BY l.titulo
        LIMIT :limite OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);

    foreach ($params as $k => $v) {
        $stmt->bindValue(":$k", $v);
    }

    $stmt->bindValue(':limite', $por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $livros = $stmt->fetchAll();

?>

<style>
    /* ESTILO MODERNO, DISCRETO E PROFISSIONAL */

    h1 {
        color: #2b2b2b;
        font-weight: 600;
        margin-bottom: 25px;
    }

    .card {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e6e6e6;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
        margin-bottom: 25px;
    }

    .card h3 {
        margin-bottom: 15px;
        font-size: 1.2em;
        color: #333;
        font-weight: 600;
    }

    .form-group label {
        font-weight: 500;
        color: #444;
    }

    input, select {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #cfcfcf;
        border-radius: 6px;
        background: #fafafa;
        transition: all .2s;
    }

    input:focus, select:focus {
        border-color: #999;
        background: #fff;
    }

    .btn {
        padding: 8px 14px;
        border-radius: 6px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: .2s;
    }

    .btn:hover {
        opacity: .85;
    }

    .btn-small {
        padding: 5px 10px;
    }

    .btn-success {
        background: #20a36a;
        color: #fff;
    }

    .btn-warning {
        background: #d8a436;
        color: #fff;
    }

    .btn-danger {
        background: #c64343;
        color: #fff;
    }

    .btn-secondary {
        background: #777;
        color: #fff;
    }

    .badge {
        padding: 3px 6px;
        border-radius: 4px;
        font-size: .8em;
        font-weight: 500;
        color: #fff;
    }

    .badge-success { background: #1c8b57; }
    .badge-danger  { background: #b53737; }
    .badge-info    { background: #3e7db3; }

</style>

<h1>Catálogo de Livros</h1>

<div style="margin-bottom: 25px;">
    <a href="livro_novo.php" class="btn btn-success">
        ➕ Cadastrar Novo Livro
    </a>
    <a href="autor_novo.php" class="btn btn-secondary">
        ➕ Cadastrar Novo Autor
    </a>
</div>

<div class="card">
    <h3>🔍 Filtros de Busca</h3>
    <form method="GET" action="livros.php">

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="busca">Buscar por título:</label>
                    <input type="text" id="busca" name="busca" value="<?= htmlspecialchars($filtro_busca) ?>">
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="autor">Filtrar por autor:</label>
                    <select id="autor" name="autor">
                        <option value="0">Todos os autores</option>
                        <?php foreach ($autores as $autor): ?>
                            <option value="<?= $autor['id'] ?>" <?= $filtro_autor == $autor['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($autor['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="categoria">Categoria:</label>
                    <select id="categoria" name="categoria">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat ?>" <?= $filtro_categoria == $cat ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="disponivel">Disponibilidade:</label>
                    <select id="disponivel" name="disponivel">
                        <option value="0">Todos</option>
                        <option value="1" <?= $filtro_disponivel == 1 ? 'selected' : '' ?>>
                            Apenas disponíveis
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-secondary">Filtrar</button>
        <a href="livros.php" class="btn btn-danger">Limpar</a>
    </form>
</div>

<p style="color:#444;">
    <?php if ($total_registros > 0): ?>
        Exibindo <?= count($livros) ?> de <?= $total_registros ?>
    <?php else: ?>
        Nenhum livro encontrado.
    <?php endif; ?>
</p>

<?php if (count($livros) > 0): ?>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
<?php foreach ($livros as $livro):

    $disponivel = $livro['quantidade_disponivel'] > 0;

    $caminho_capa = $livro['capa_imagem']
        ? DIRETORIO_CAPAS . $livro['capa_imagem']
        : "assets/img/placeholder_livro.png";

?>
    <div class="card" style="display:flex; gap:20px; <?= !$disponivel ? 'opacity:0.8;' : '' ?>">
        
        <div style="width:110px; height:160px; overflow:hidden; border-radius:8px;">
            <img src="<?= $caminho_capa ?>" style="width:100%; height:100%; object-fit:cover;">
        </div>

        <div style="flex:1;">
            <div style="position:absolute; right:20px;">
                <?php if ($disponivel): ?>
                    <span class="badge badge-success">Disponível</span>
                <?php else: ?>
                    <span class="badge badge-danger">Indisponível</span>
                <?php endif; ?>
            </div>

            <h3 style="margin-top:0;"><?= htmlspecialchars($livro['titulo']) ?></h3>

            <p style="color:#555;">
                <strong>Autor:</strong> <?= htmlspecialchars($livro['autor_nome']) ?>
            </p>

            <?php if ($livro['categoria']): ?>
                <span class="badge badge-info">
                    <?= htmlspecialchars($livro['categoria']) ?>
                </span>
            <?php endif; ?>

            <p style="margin-top:10px; color:#444;">
                <strong>Disponíveis:</strong>
                <?= $livro['quantidade_disponivel'] ?> de <?= $livro['quantidade_total'] ?>
            </p>

            <div style="margin-top:10px; display:flex; gap:10px;">
                <a href="livro_editar.php?id=<?= $livro['id'] ?>" class="btn btn-warning btn-small">Editar</a>
                
                <?php if ($disponivel): ?>
                    <a href="emprestimo_novo.php?livro_id=<?= $livro['id'] ?>" class="btn btn-success btn-small">Emprestar</a>
                <?php endif; ?>

                <a href="livro_excluir.php?id=<?= $livro['id'] ?>" class="btn btn-danger btn-small confirm-delete">Excluir</a>
            </div>

        </div>
    </div>

<?php endforeach; ?>
</div>

<?php endif; ?>

<?php
} catch (PDOException $e) {
    exibirMensagem('erro', 'Erro ao carregar livros: ' . $e->getMessage());
}

require_once 'includes/footer.php';
?>
