
<?php
session_start();

// Inicializa a lista de fretes
if (!isset($_SESSION['fretes'])) {
    $_SESSION['fretes'] = [];
}

// Função para proteger a exibição dos dados
function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Recebe a data informada
$dataFiltro = $_GET['data'] ?? '';

// Filtra os fretes pela data
$fretesFiltrados = [];

foreach ($_SESSION['fretes'] as $frete) {

    if (
        $dataFiltro === '' ||
        ($frete['data'] ?? '') === $dataFiltro
    ) {
        $fretesFiltrados[] = $frete;
    }
}

// Gera o arquivo CSV para download
if (isset($_GET['baixar'])) {

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="relatorio_fretes.csv"');

    $arquivo = fopen('php://output', 'w');

    // Compatibilidade com programas de planilhas
    fwrite($arquivo, "\xEF\xBB\xBF");

    // Cabeçalho do relatório
    fputcsv($arquivo, [
        'Código do Frete',
        'Data',
        'Transportador',
        'Cidade de Origem',
        'Cidade de Destino',
        'Produtos'
    ], ';');

    foreach ($fretesFiltrados as $frete) {

        $nomesProdutos = [];

        foreach ($frete['produtos'] ?? [] as $produto) {
            $nomesProdutos[] = $produto['nome'] ?? '';
        }

        fputcsv($arquivo, [
            $frete['codigo'] ?? '',
            $frete['data'] ?? '',
            $frete['transportador']['nome'] ?? '',
            $frete['origem']['cidade'] ?? '',
            $frete['destino']['cidade'] ?? '',
            implode(', ', $nomesProdutos)
        ], ';');
    }

    fclose($arquivo);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Relatórios de Fretes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="container mt-4">

    <h2 class="mb-4">Relatórios de Fretes</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="card-title mb-3">Consultar Fretes por Data</h5>

            <form method="GET">

                <div class="row align-items-end">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Data do Frete</label>

                        <input
                            type="date"
                            name="data"
                            class="form-control"
                            value="<?= escapar($dataFiltro) ?>"
                        >
                    </div>

                    <div class="col-md-6 mb-3">

                        <button type="submit" class="btn btn-primary">
                            Consultar
                        </button>

                        <a href="relatorios.php" class="btn btn-secondary">
                            Limpar
                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="card-title mb-0">Resultado do Relatório</h5>

                <a
                    href="relatorios.php?baixar=1&data=<?= urlencode($dataFiltro) ?>"
                    class="btn btn-success"
                >
                    Baixar Relatório CSV
                </a>

            </div>

            <p>
                Total de fretes encontrados:
                <strong><?= count($fretesFiltrados) ?></strong>
            </p>

            <div class="table-responsive">

                <table class="table table-striped table-hover">

                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Data</th>
                            <th>Transportador</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Produtos</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($fretesFiltrados) > 0): ?>

                            <?php foreach ($fretesFiltrados as $frete): ?>

                                <tr>

                                    <td><?= escapar($frete['codigo'] ?? '') ?></td>

                                    <td><?= escapar($frete['data'] ?? '') ?></td>

                                    <td>
                                        <?= escapar($frete['transportador']['nome'] ?? '') ?>
                                    </td>

                                    <td>
                                        <?= escapar($frete['origem']['cidade'] ?? '') ?>
                                    </td>

                                    <td>
                                        <?= escapar($frete['destino']['cidade'] ?? '') ?>
                                    </td>

                                    <td>
                                        <?php foreach ($frete['produtos'] ?? [] as $produto): ?>
                                            <?= escapar($produto['nome'] ?? '') ?><br>
                                        <?php endforeach; ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center">
                                    Nenhum frete encontrado para esta consulta.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <a href="../index.php" class="btn btn-secondary mt-3">
        Voltar ao Início
    </a>

</div>

</body>
</html>
