<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$sql = "SELECT c.id, c.titulo, c.categoria, c.prioridade, c.status, c.criado_em, u.nome AS nome_usuario
        FROM chamados c
        INNER JOIN usuarios u ON c.usuario_id = u.id
        ORDER BY c.criado_em DESC";

$stmt = $pdo->query($sql);
$chamados =$stmt->fetchAll();
?>

<?php if (isset($_GET['mensagem']) &&$_GET['mensagem'] === 'deletado'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Chamado removido com sucesso!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Painel de Chamados</h2>
    <a href="novo-chamado.php" class="btn btn-success">+ Abrir Novo Chamado</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#ID</th>
                        <th>Título</th>
                        <th>Solicitante</th>
                        <th>Categoria</th>
                        <th>Prioridade</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($chamados)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Nenhum chamado registrado no momento.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($chamados as$c): ?>
                            <tr>
                                <td>#<?= $c['id'] ?></td>
                                <td><strong><?= htmlspecialchars($c['titulo']) ?></strong></td>
                                <td><?= htmlspecialchars($c['nome_usuario']) ?></td>
                                <td><?= $c['categoria'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $c['prioridade'] === 'Crítica' \vert{}\vert{} $c['prioridade'] === 'Alta' ? 'danger' : 'info' ?>">
                                        <?= $c['prioridade'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= $c['status'] ?></span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?></td>
                                <td class="text-end">
                                    <a href="editar-chamado.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                    <a href="deletar-chamado.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>