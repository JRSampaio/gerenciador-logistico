
<?php
session_start();

// Inicializa as listas
if (!isset($_SESSION['produtos'])) {
    $_SESSION['produtos'] = [];
}

if (!isset($_SESSION['enderecos'])) {
    $_SESSION['enderecos'] = [];
}

if (!isset($_SESSION['transportadores'])) {
    $_SESSION['transportadores'] = [];
}

if (!isset($_SESSION['fretes'])) {
    $_SESSION['fretes'] = [];
}

// Garante que os registros antigos tenham ID
foreach ($_SESSION['produtos'] as $indice => $produto) {
    if (!isset($produto['id'])) {
        $_SESSION['produtos'][$indice]['id'] = $indice + 1;
    }
}

foreach ($_SESSION['enderecos'] as $indice => $endereco) {
    if (!isset($endereco['id'])) {
        $_SESSION['enderecos'][$indice]['id'] = $indice + 1;
    }
}

foreach ($_SESSION['transportadores'] as $indice => $transportador) {
    if (!isset($transportador['id'])) {
        $_SESSION['transportadores'][$indice]['id'] = $indice + 1;
    }
}

// Função para proteger a exibição dos dados
function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Busca um registro pelo ID
function buscarPorId($lista, $id) {
    foreach ($lista as $registro) {
        if ((string) $registro['id'] === (string) $id) {
            return $registro;
        }
    }

    return null;
}

$mensagemErro = '';

// Processa a geração do frete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $transportadorId = $_POST['transportador'] ?? '';
    $origemId = $_POST['origem'] ?? '';
    $destinoId = $_POST['destino'] ?? '';
    $data = $_POST['data'] ?? '';
    $produtosSelecionados = $_POST['produtos'] ?? [];

    $transportador = buscarPorId(
        $_SESSION['transportadores'],
        $transportadorId
    );

    $origem = buscarPorId(
        $_SESSION['enderecos'],
        $origemId
    );

    $destino = buscarPorId(
        $_SESSION['enderecos'],
        $destinoId
    );

    $produtosDoFrete = [];

    foreach ($produtosSelecionados as $produtoId) {
        $produtoEncontrado = buscarPorId(
            $_SESSION['produtos'],
            $produtoId
        );

        if ($produtoEncontrado !== null) {
            $produtosDoFrete[] = $produtoEncontrado;
        }
    }

    // Verifica os dados obrigatórios
    if (!$transportador || !$origem || !$destino) {
        $mensagemErro = 'Selecione um transportador, uma origem e um destino válidos.';

    } elseif ($origem['tipo'] !== 'origem') {
        $mensagemErro = 'O endereço selecionado não é uma origem válida.';

    } elseif ($destino['tipo'] !== 'destino') {
        $mensagemErro = 'O endereço selecionado não é um destino válido.';

    } elseif ($origemId == $destinoId) {
        $mensagemErro = 'A origem e o destino devem ser diferentes.';

    } elseif (empty($data)) {
        $mensagemErro = 'Informe a data do frete.';

    } elseif (empty($produtosDoFrete)) {
        $mensagemErro = 'Selecione pelo menos um produto.';

    } else {

        // Gera o código do frete
        $codigoFrete = count($_SESSION['fretes']) > 0
            ? max(array_column($_SESSION['fretes'], 'codigo')) + 1
            : 1;

        // Registra o frete com IDs e dados relacionados
        $frete = [
            'codigo' => $codigoFrete,
            'data' => $data,

            'transportador_id' => $transportador['id'],
            'origem_id' => $origem['id'],
            'destino_id' => $destino['id'],

            'produtos_ids' => array_column($produtosDoFrete, 'id'),

            // Mantém os dados para exibição nos relatórios
            'transportador' => $transportador,
            'origem' => $origem,
            'destino' => $destino,
            'produtos' => $produtosDoFrete
        ];

        $_SESSION['fretes'][] = $frete;

        header('Location: fretes.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Geração de Fretes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="container mt-4">

    <h2 class="mb-4">Geração de Fretes</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="card-title mb-3">Cadastrar Novo Frete</h5>

            <?php if ($mensagemErro): ?>
                <div class="alert alert-danger">
                    <?= escapar($mensagemErro) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Transportador</label>

                        <select name="transportador" class="form-select" required>
                            <option value="">Selecione o transportador</option>

                            <?php foreach ($_SESSION['transportadores'] as $transportador): ?>

                                <option value="<?= escapar($transportador['id']) ?>">
                                    ID <?= escapar($transportador['id']) ?> -
                                    <?= escapar($transportador['nome']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Data do Frete</label>

                        <input type="date" name="data" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Endereço de Origem</label>

                        <select name="origem" class="form-select" required>
                            <option value="">Selecione a origem</option>

                            <?php foreach ($_SESSION['enderecos'] as $endereco): ?>

                                <?php if ($endereco['tipo'] === 'origem'): ?>

                                    <option value="<?= escapar($endereco['id']) ?>">
                                        ID <?= escapar($endereco['id']) ?> -
                                        <?= escapar($endereco['rua']) ?>,
                                        <?= escapar($endereco['numero']) ?> -
                                        <?= escapar($endereco['cidade']) ?>
                                    </option>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Endereço de Destino</label>

                        <select name="destino" class="form-select" required>
                            <option value="">Selecione o destino</option>

                            <?php foreach ($_SESSION['enderecos'] as $endereco): ?>

                                <?php if ($endereco['tipo'] === 'destino'): ?>

                                    <option value="<?= escapar($endereco['id']) ?>">
                                        ID <?= escapar($endereco['id']) ?> -
                                        <?= escapar($endereco['rua']) ?>,
                                        <?= escapar($endereco['numero']) ?> -
                                        <?= escapar($endereco['cidade']) ?>
                                    </option>

                                <?php endif; ?>

                            <?php endforeach; ?>

                        </select>
                    </div>

                </div>

                <hr>

                <h5 class="mb-3">Produtos do Frete</h5>

                <?php if (count($_SESSION['produtos']) > 0): ?>

                    <div class="row">

                        <?php foreach ($_SESSION['produtos'] as $produto): ?>

                            <div class="col-md-6 mb-2">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="produtos[]"
                                        value="<?= escapar($produto['id']) ?>"
                                        id="produto<?= escapar($produto['id']) ?>"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="produto<?= escapar($produto['id']) ?>"
                                    >
                                        ID <?= escapar($produto['id']) ?> -
                                        <?= escapar($produto['nome']) ?>
                                        (Quantidade: <?= escapar($produto['quantidade']) ?>)
                                    </label>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <p class="text-muted">
                        Nenhum produto cadastrado. Cadastre produtos antes de gerar um frete.
                    </p>

                <?php endif; ?>

                <button type="submit" class="btn btn-primary mt-3">
                    Gerar Frete
                </button>

            </form>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="card-title mb-3">Fretes Gerados</h5>

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

                        <?php if (count($_SESSION['fretes']) > 0): ?>

                            <?php foreach ($_SESSION['fretes'] as $frete): ?>

                                <tr>

                                    <td><?= escapar($frete['codigo']) ?></td>

                                    <td><?= escapar($frete['data']) ?></td>

                                    <td>
                                        <?= escapar($frete['transportador']['nome']) ?>
                                    </td>

                                    <td>
                                        <?= escapar($frete['origem']['cidade']) ?>
                                    </td>

                                    <td>
                                        <?= escapar($frete['destino']['cidade']) ?>
                                    </td>

                                    <td>
                                        <?php foreach ($frete['produtos'] as $produto): ?>
                                            <?= escapar($produto['nome']) ?><br>
                                        <?php endforeach; ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center">
                                    Nenhum frete gerado.
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
