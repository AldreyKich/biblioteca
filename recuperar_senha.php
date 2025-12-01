<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

// Carregar mensagens (erro/sucesso)
$mensagem = obterMensagem();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .recover-container {
            width: 100%;
            max-width: 420px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="recover-container">
    <div class="card shadow">
        <div class="card-header">
            Recuperação de Senha
        </div>
        <div class="card-body">

            <!-- Mensagem de retorno -->
            <?php if ($mensagem): ?>
                <?php $tipo = ($mensagem['tipo'] == 'sucesso') ? 'alert-success' : 'alert-danger'; ?>
                <div class="alert <?= $tipo ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($mensagem['mensagem']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <p class="text-muted mb-3">
                Informe seu endereço de e-mail. Se estiver cadastrado, enviaremos um link para redefinir sua senha.
            </p>

            <form method="POST" action="recuperar_senha_processa.php">
                <div class="mb-3">
                    <label class="form-label">E-mail cadastrado:</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Enviar link de recuperação
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="login.php" class="text-decoration-none">← Voltar ao login</a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
