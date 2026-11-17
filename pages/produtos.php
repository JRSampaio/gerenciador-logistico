
<?php
session_start();

// Inicializa a lista de produtos
if (!isset($_SESSION['produtos'])) {
    $_SESSION['produtos'] = [];
}

// Adiciona ID aos produtos cadastrados anteriormente
foreach ($_SESSION['produtos'] as $indice => $produto) {
    if (!isset($produto['id'])) {
        $_SESSION['produtos'][$indice]['id'] = $indice + 1;
    }
}

// Função para proteger a exibição dos dados
function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Verifica se está editando um produto
$indiceEdicao = isset($_GET['editar'])
    ? (int) $_GET['editar']
    : null;

$produtoEdicao = null;

if (
    $indiceEdicao !== null &&
    isset($_SESSION['produtos'][$indiceEdicao])
) {
    $produtoEdicao = $_SESSION['produtos'][$indiceEdicao];
}

// Salva o produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $produto = [
        'codigo' => trim($_POST['codigo'] ?? ''),
        'nome' => trim($_POST['nome'] ?? ''),
        'descricao' => trim($_POST['descricao'] ?? ''),
        'quantidade' => (int) ($_POST['quantidade'] ?? 0),
        'peso' => trim($_POST['peso'] ?? ''),
        'dimensoes' => trim($_POST['dimensoes'] ?? '')
    ];

    if ($indiceEdicao !== null && isset($_SESSION['produtos'][$indiceEdicao])) {

        // Mantém o ID original durante a edição
        $produto['id'] = $_SESSION['produtos'][$indiceEdicao]['id'];

        $_SESSION['produtos'][$indiceEdicao] = $produto;

    } else {

        // Gera um novo ID automaticamente
        $ids = array_column($_SESSION['produtos'], 'id');

        $produto['id'] = count($ids) > 0
            ? max($ids) + 1
            : 1;

        $_SESSION['produtos'][] = $produto;
    }

    header('Location: produtos.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Produtos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="container mt-4">

    <h2 class="mb-4">Cadastro de Produtos</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="card-title mb-3">
                <?= $produtoEdicao ? 'Editar Produto' : 'Cadastrar Produto' ?>
            </h5>

            <form method="POST">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control"
                            value="<?= escapar($produtoEdicao['codigo'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Nome do Produto</label>
                        <input type="text" name="nome" class="form-control"
                            value="<?= escapar($produtoEdicao['nome'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Descrição</label>
                        <input type="text" name="descricao" class="form-control"
                            value="<?= escapar($produtoEdicao['descricao'] ?? '') ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Quantidade</label>
                        <input type="number" name="quantidade" class="form-control"
                            min="0"
                            value="<?= escapar($produtoEdicao['quantidade'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Peso</label>
                        <input type="text" name="peso" class="form-control"
                            value="<?= escapar($produtoEdicao['peso'] ?? '') ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Dimensões</label>
                        <input type="text" name="dimensoes" class="form-control"
                            value="<?= escapar($produtoEdicao['dimensoes'] ?? '') ?>">
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    <?= $produtoEdicao ? 'Salvar Alterações' : 'Cadastrar Produto' ?>
                </button>

                <?php if ($produtoEdicao): ?>
                    <a href="produtos.php" class="btn btn-secondary">
                        Cancelar
                    </a>
                <?php endif; ?>

            </form>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="card-title mb-3">Produtos Cadastrados</h5>

            <div class="table-responsive">

                <table class="table table-striped table-hover">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Quantidade</th>
                            <th>Peso</th>
                            <th>Dimensões</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($_SESSION['produtos']) > 0): ?>

                            <?php foreach ($_SESSION['produtos'] as $indice => $produto): ?>

                                <tr>
                                    <td><?= escapar($produto['id']) ?></td>
                                    <td><?= escapar($produto['codigo']) ?></td>
                                    <td><?= escapar($produto['nome']) ?></td>
                                    <td><?= escapar($produto['quantidade']) ?></td>
                                    <td><?= escapar($produto['peso']) ?></td>
                                    <td><?= escapar($produto['dimensoes']) ?></td>

                                    <td>
                                        <a href="produtos.php?editar=<?= $indice ?>"
                                           class="btn btn-warning btn-sm">
                                            Editar
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center">
                                    Nenhum produto cadastrado.
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
