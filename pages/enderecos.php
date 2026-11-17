
<?php
session_start();

// Inicializa a lista de endereços
if (!isset($_SESSION['enderecos'])) {
    $_SESSION['enderecos'] = [];
}

// Adiciona ID aos endereços já cadastrados
foreach ($_SESSION['enderecos'] as $indice => $endereco) {
    if (!isset($endereco['id'])) {
        $_SESSION['enderecos'][$indice]['id'] = $indice + 1;
    }
}

// Função para proteger a exibição dos dados
function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Verifica se está editando
$indiceEdicao = isset($_GET['editar'])
    ? (int) $_GET['editar']
    : null;

$enderecoEdicao = null;

if (
    $indiceEdicao !== null &&
    isset($_SESSION['enderecos'][$indiceEdicao])
) {
    $enderecoEdicao = $_SESSION['enderecos'][$indiceEdicao];
}

// Salva o endereço
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $endereco = [
        'tipo' => trim($_POST['tipo'] ?? ''),
        'cep' => trim($_POST['cep'] ?? ''),
        'rua' => trim($_POST['rua'] ?? ''),
        'numero' => trim($_POST['numero'] ?? ''),
        'bairro' => trim($_POST['bairro'] ?? ''),
        'cidade' => trim($_POST['cidade'] ?? ''),
        'estado' => trim($_POST['estado'] ?? ''),
        'complemento' => trim($_POST['complemento'] ?? '')
    ];

    if (
        $indiceEdicao !== null &&
        isset($_SESSION['enderecos'][$indiceEdicao])
    ) {

        // Mantém o ID original durante a edição
        $endereco['id'] = $_SESSION['enderecos'][$indiceEdicao]['id'];

        $_SESSION['enderecos'][$indiceEdicao] = $endereco;

    } else {

        // Gera um novo ID automaticamente
        $ids = array_column($_SESSION['enderecos'], 'id');

        $endereco['id'] = count($ids) > 0
            ? max($ids) + 1
            : 1;

        $_SESSION['enderecos'][] = $endereco;
    }

    header('Location: enderecos.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Endereços</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="container mt-4">

    <h2 class="mb-4">Cadastro de Endereços</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="card-title mb-3">
                <?= $enderecoEdicao ? 'Editar Endereço' : 'Cadastrar Endereço' ?>
            </h5>

            <form method="POST">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de Endereço</label>

                        <select name="tipo" class="form-select" required>
                            <option value="">Selecione</option>

                            <option value="origem"
                                <?= ($enderecoEdicao['tipo'] ?? '') === 'origem' ? 'selected' : '' ?>>
                                Origem
                            </option>

                            <option value="destino"
                                <?= ($enderecoEdicao['tipo'] ?? '') === 'destino' ? 'selected' : '' ?>>
                                Destino
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">CEP</label>

                        <input type="text" name="cep" class="form-control"
                            value="<?= escapar($enderecoEdicao['cep'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Rua</label>

                        <input type="text" name="rua" class="form-control"
                            value="<?= escapar($enderecoEdicao['rua'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Número</label>

                        <input type="text" name="numero" class="form-control"
                            value="<?= escapar($enderecoEdicao['numero'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label class="form-label">Bairro</label>

                        <input type="text" name="bairro" class="form-control"
                            value="<?= escapar($enderecoEdicao['bairro'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cidade</label>

                        <input type="text" name="cidade" class="form-control"
                            value="<?= escapar($enderecoEdicao['cidade'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Estado</label>

                        <input type="text" name="estado" class="form-control"
                            value="<?= escapar($enderecoEdicao['estado'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Complemento</label>

                        <input type="text" name="complemento" class="form-control"
                            value="<?= escapar($enderecoEdicao['complemento'] ?? '') ?>">
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    <?= $enderecoEdicao ? 'Salvar Alterações' : 'Cadastrar Endereço' ?>
                </button>

                <?php if ($enderecoEdicao): ?>
                    <a href="enderecos.php" class="btn btn-secondary">
                        Cancelar
                    </a>
                <?php endif; ?>

            </form>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="card-title mb-3">Endereços Cadastrados</h5>

            <div class="table-responsive">

                <table class="table table-striped table-hover">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>CEP</th>
                            <th>Rua</th>
                            <th>Número</th>
                            <th>Cidade</th>
                            <th>Estado</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($_SESSION['enderecos']) > 0): ?>

                            <?php foreach ($_SESSION['enderecos'] as $indice => $endereco): ?>

                                <tr>
                                    <td><?= escapar($endereco['id']) ?></td>
                                    <td><?= escapar(ucfirst($endereco['tipo'])) ?></td>
                                    <td><?= escapar($endereco['cep']) ?></td>
                                    <td><?= escapar($endereco['rua']) ?></td>
                                    <td><?= escapar($endereco['numero']) ?></td>
                                    <td><?= escapar($endereco['cidade']) ?></td>
                                    <td><?= escapar($endereco['estado']) ?></td>

                                    <td>
                                        <a href="enderecos.php?editar=<?= $indice ?>"
                                           class="btn btn-warning btn-sm">
                                            Editar
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="8" class="text-center">
                                    Nenhum endereço cadastrado.
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
