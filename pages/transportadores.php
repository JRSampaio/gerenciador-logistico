
<?php
session_start();

// Inicializa a lista de transportadores
if (!isset($_SESSION['transportadores'])) {
    $_SESSION['transportadores'] = [];
}

// Adiciona ID aos transportadores já cadastrados
foreach ($_SESSION['transportadores'] as $indice => $transportador) {
    if (!isset($transportador['id'])) {
        $_SESSION['transportadores'][$indice]['id'] = $indice + 1;
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

$transportadorEdicao = null;

if (
    $indiceEdicao !== null &&
    isset($_SESSION['transportadores'][$indiceEdicao])
) {
    $transportadorEdicao = $_SESSION['transportadores'][$indiceEdicao];
}

// Salva o transportador
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $transportador = [
        'nome' => trim($_POST['nome'] ?? ''),
        'documento' => trim($_POST['documento'] ?? ''),
        'telefone' => trim($_POST['telefone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'endereco' => trim($_POST['endereco'] ?? '')
    ];

    if (
        $indiceEdicao !== null &&
        isset($_SESSION['transportadores'][$indiceEdicao])
    ) {

        // Mantém o ID original durante a edição
        $transportador['id'] = $_SESSION['transportadores'][$indiceEdicao]['id'];

        $_SESSION['transportadores'][$indiceEdicao] = $transportador;

    } else {

        // Gera um novo ID automaticamente
        $ids = array_column($_SESSION['transportadores'], 'id');

        $transportador['id'] = count($ids) > 0
            ? max($ids) + 1
            : 1;

        $_SESSION['transportadores'][] = $transportador;
    }

    header('Location: transportadores.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Transportadores</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="container mt-4">

    <h2 class="mb-4">Cadastro de Transportadores</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="card-title mb-3">
                <?= $transportadorEdicao ? 'Editar Transportador' : 'Cadastrar Transportador' ?>
            </h5>

            <form method="POST">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome</label>

                        <input type="text" name="nome" class="form-control"
                            value="<?= escapar($transportadorEdicao['nome'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">CPF/CNPJ</label>

                        <input type="text" name="documento" class="form-control"
                            value="<?= escapar($transportadorEdicao['documento'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telefone</label>

                        <input type="text" name="telefone" class="form-control"
                            value="<?= escapar($transportadorEdicao['telefone'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">E-mail</label>

                        <input type="email" name="email" class="form-control"
                            value="<?= escapar($transportadorEdicao['email'] ?? '') ?>">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Endereço</label>

                        <input type="text" name="endereco" class="form-control"
                            value="<?= escapar($transportadorEdicao['endereco'] ?? '') ?>" required>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    <?= $transportadorEdicao ? 'Salvar Alterações' : 'Cadastrar Transportador' ?>
                </button>

                <?php if ($transportadorEdicao): ?>
                    <a href="transportadores.php" class="btn btn-secondary">
                        Cancelar
                    </a>
                <?php endif; ?>

            </form>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="card-title mb-3">Transportadores Cadastrados</h5>

            <div class="table-responsive">

                <table class="table table-striped table-hover">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CPF/CNPJ</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th>Endereço</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($_SESSION['transportadores']) > 0): ?>

                            <?php foreach ($_SESSION['transportadores'] as $indice => $transportador): ?>

                                <tr>
                                    <td><?= escapar($transportador['id']) ?></td>
                                    <td><?= escapar($transportador['nome']) ?></td>
                                    <td><?= escapar($transportador['documento']) ?></td>
                                    <td><?= escapar($transportador['telefone']) ?></td>
                                    <td><?= escapar($transportador['email']) ?></td>
                                    <td><?= escapar($transportador['endereco']) ?></td>

                                    <td>
                                        <a href="transportadores.php?editar=<?= $indice ?>"
                                           class="btn btn-warning btn-sm">
                                            Editar
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center">
                                    Nenhum transportador cadastrado.
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
