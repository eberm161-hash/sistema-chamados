<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$mensagem = '';$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo '<div class="alert alert-danger">ID do chamado inválido.</div>';
    require_once 'includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {$id_confirmado = filter_input(INPUT_POST, 'id_confirmado', FILTER_VALIDATE_INT);

    if ($id_confirmado &&$id_confirmado === $id) {$sql_delete = "DELETE FROM chamados WHERE id = :id";
        $stmt_delete =$pdo->prepare($sql_delete);$sucesso = $stmt_delete->execute([':id' =>$id_confirmado]);

        if ($sucesso) {
            header("Location: index.php?mensagem=deletado");
            exit;
        } else {
            $mensagem = '<div class="alert alert-danger">Erro ao excluir o chamado.</div>';
        }
    }
}

$sql_select = "SELECT c.id, c.titulo, c.criado_em, u.nome AS nome_usuario 
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
    <div class="col-md-6">
        <div class="card border-danger shadow-sm">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title h5 mb-0">Confirmar Exclusão</h3>
            </div>
            <div class="card-body">
                <?= $mensagem ?>
                <p>Deseja realmente excluir permanentemente o chamado abaixo?</p>
                
                <div class="bg-light p-3 rounded mb-3 border">
                    <p class="mb-1"><strong>ID:</strong> #<?= $chamado['id'] ?></p>
                    <p class="mb-1"><strong>Título:</strong> <?= htmlspecialchars($chamado['titulo']) ?></p>
                    <p class="mb-0"><strong>Solicitante:</strong> <?= htmlspecialchars($chamado['nome_usuario']) ?></p>
                </div>

                <form action="deletar-chamado.php?id=<?= $chamado['id'] ?>" method="POST" class="d-flex justify-content-end gap-2">
                    <input type="hidden" name="id_confirmado" value="<?= $chamado['id'] ?>">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>