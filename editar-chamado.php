<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$mensagem = '';$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo '<div class="alert alert-danger">ID do chamado inválido.</div>';
    require_once 'includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo     = trim($_POST['titulo'] ?? '');
    $descricao  = trim($_POST['descricao'] ?? '');
    $categoria  =$_POST['categoria'] ?? 'Software';
    $prioridade =$_POST['prioridade'] ?? 'Média';
    $status     =$_POST['status'] ?? 'Aberto';

    $categorias_permitidas  = ['Hardware', 'Software', 'Redes', 'Outros'];$prioridades_permitidas = ['Baixa', 'Média', 'Alta', 'Crítica'];
    $status_permitidos      = ['Aberto', 'Em Andamento', 'Concluído', 'Cancelado'];

    if (empty($titulo) || empty($descricao)) {$mensagem = '<div class="alert alert-warning">Campos obrigatórios vazios.</div>';
    } elseif (!in_array($categoria,$categorias_permitidas) || 
              !in_array($prioridade,$prioridades_permitidas) || 
              !in_array($status, $status_permitidos)) {$mensagem = '<div class="alert alert-danger">Opção inválida selecionada.</div>';
    } else {
        $sql_update = "UPDATE chamados 
                       SET titulo = :titulo, 
                           descricao = :descricao, 
                           categoria = :categoria, 
                           prioridade = :prioridade, 
                           status = :status 
                       WHERE id = :id";

        $stmt_update = $pdo->prepare($sql_update);
        $sucesso =$stmt_update->execute([
            ':titulo'     => $titulo,
            ':descricao'  => $descricao,
            ':categoria'  => $categoria,
            ':prioridade' => $prioridade,
            ':status'     => $status,
            ':id'         => $id
        ]);

        if ($sucesso) {$mensagem = '<div class="alert alert-success">Chamado #' . $id . ' atualizado com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-danger">Erro ao atualizar chamado.</div>';
        }
    }
}

$sql_select = "SELECT c.*, u.nome AS nome_usuario 
               FROM chamados c 
               INNER JOIN usuarios u ON c.usuario_id = u.id 
               WHERE c.id = :id";

$stmt_select = $pdo->prepare($sql_select);
$stmt_select->execute([':id' =>$id]);
$chamado =$stmt_select->fetch();

if (!$chamado) {
    echo '<div class="alert alert-warning">Chamado não encontrado.</div>';
    require_once 'includes/footer.php';
    exit;
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Editar Chamado #<?= $chamado['id'] ?></h2>
            <a href="index.php" class="btn btn-outline-secondary">&larr; Voltar</a>
        </div>

        <?= $mensagem ?>

        <form action="editar-chamado.php?id=<?= $chamado['id'] ?>" method="POST" class="card card-body shadow-sm">
            <div class="mb-3">
                <label class="form-label text-muted">Solicitante</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($chamado['nome_usuario']) ?>" disabled>
            </div>

            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" value="<?= htmlspecialchars($chamado['titulo']) ?>" required>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select name="categoria" id="categoria" class="form-select">
                        <?php foreach (['Hardware', 'Software', 'Redes', 'Outros'] as $cat): ?>
                            <option value="<?= $cat ?>" <?= $chamado['categoria'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="prioridade" class="form-label">Prioridade</label>
                    <select name="prioridade" id="prioridade" class="form-select">
                        <?php foreach (['Baixa', 'Média', 'Alta', 'Crítica'] as $prio): ?>
                            <option value="<?= $prio ?>" <?= $chamado['prioridade'] === $prio ? 'selected' : '' ?>><?= $prio ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <?php foreach (['Aberto', 'Em Andamento', 'Concluído', 'Cancelado'] as $st): ?>
                            <option value="<?= $st ?>" <?= $chamado['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="4" required><?= htmlspecialchars($chamado['descricao']) ?></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>