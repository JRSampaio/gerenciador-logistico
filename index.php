
<?php
session_start();

// Conta os cadastros realizados no sistema
$totalProdutos = isset($_SESSION['produtos']) ? count($_SESSION['produtos']) : 0;
$totalEnderecos = isset($_SESSION['enderecos']) ? count($_SESSION['enderecos']) : 0;
$totalTransportadores = isset($_SESSION['transportadores']) ? count($_SESSION['transportadores']) : 0;
$totalFretes = isset($_SESSION['fretes']) ? count($_SESSION['fretes']) : 0;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerenciador Logístico</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS do projeto -->
    <link rel="stylesheet" href="css/style.css?v=2">

</head>

<body>

    <!-- Menu de navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <a class="navbar-brand" href="index.php">
                Gerenciador Logístico
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuPrincipal">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Início</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="pages/produtos.php">Produtos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="pages/enderecos.php">Endereços</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="pages/transportadores.php">Transportadores</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="pages/fretes.php">Fretes</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="pages/relatorios.php">Relatórios</a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <main class="container mt-5">

        <h1 class="mb-2">Painel inicial</h1>

        <p class="text-muted">
            Bem-vindo ao Gerenciador Logístico.
        </p>

        <!-- Resumo do sistema -->
        <div class="row mt-4">

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Produtos</h5>
                        <h2><?php echo $totalProdutos; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Endereços</h5>
                        <h2><?php echo $totalEnderecos; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Transportadores</h5>
                        <h2><?php echo $totalTransportadores; ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Fretes</h5>
                        <h2><?php echo $totalFretes; ?></h2>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript do projeto -->
    <script src="js/script.js"></script>

</body>
</html>
