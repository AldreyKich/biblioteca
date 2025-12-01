<?php
/**
 * includes/header.php
 * Cabeçalho padrão do sistema (coloque dentro da pasta `includes/`)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= defined('NOME_BIBLIOTECA') ? NOME_BIBLIOTECA : 'Sistema de Biblioteca' ?></title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background:#f8f9fa; font-family: "Segoe UI", Tahoma, sans-serif; }
        
        .site-navbar { background: linear-gradient(90deg, #e5e5e5, #b5b5b5, #8c8c8c); }
        .site-navbar .navbar-brand, 
        .site-navbar .nav-link { color:#222 !important; }

       
        .site-navbar .nav-link:hover { text-decoration: underline; }
        .main-wrapper { max-width:1200px; margin:18px auto; padding:18px; background:#fff; border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,.05); }
        h1 { color:#667eea; border-bottom:3px solid rgba(102,126,234,0.12); padding-bottom:8px; margin-bottom:18px; }
        .card-stat { border-radius:12px; color:#fff; min-height:120px; display:flex; flex-direction:column; justify-content:center; padding:18px; }
        footer.site-footer { text-align:center; color:#666; margin-top:26px; padding:12px 0; }
        @media (max-width:720px) {
            .main-wrapper { margin:12px; padding:12px; border-radius:8px; }
        }
    </style>
</head>
<body>
<header class="site-navbar sticky-top" role="banner">
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <span class="bg-white text-primary rounded-2 d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;font-weight:700;margin-right:10px;">B</span>
                <strong>
                <span><?= defined('NOME_BIBLIOTECA') ? NOME_BIBLIOTECA : 'Biblioteca Central' ?></span>
                </strong>
             
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Alternar menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="mainNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door-fill"></i> Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="livros.php"><i class="bi bi-journal-bookmark-fill"></i> Livros</a></li>
                    <li class="nav-item"><a class="nav-link" href="clientes.php"><i class="bi bi-people-fill"></i> Clientes</a></li>
                    <li class="nav-item"><a class="nav-link" href="emprestimos.php"><i class="bi bi-card-checklist"></i> Empréstimos</a></li>
                    <li class="nav-item"><a class="nav-link" href="autores.php"><i class="bi bi-pencil-square"></i> Autores</a></li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="relatoriosMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bar-chart-fill"></i> Relatórios
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="relatoriosMenu">
                            
                            <li><a class="dropdown-item" href="relatorios_dinamicos.php"> * Módulo 6 - Relatório</a></li>
                            <li><a class="dropdown-item" href="exportar_excel.php"> * Módulo 6 - Exportar para Excel</a></li>
                            <li><a class="dropdown-item" href="exportar_pdf.php"> * Módulo 6 - Exportar para PDF</a></li>
                            <li><a class="dropdown-item" href="relatorios.php">Relatórios Gerenciais</a></li>


                        </ul>
                    </li>

                    <!--<li class="nav-item"><a class="nav-link" href="relatorios.php"><i class="bi bi-bar-chart-fill"></i> Relatórios</a></li>-->

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="administrativoMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear-fill"></i> Administrativo
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="administrativoMenu">
                            <li><a class="dropdown-item" href="login_cadastrar.php">* Módulo 6 - Cadastrar Usuário</a></li>
                            <li><a class="dropdown-item" href="login_listar.php">* Módulo 6 - Editar Usuário</a></li>
                            <li><a class="dropdown-item" href="auditoria_logins.php">* Módulo 6 - Auditoria de Login</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="main-wrapper" role="main">
