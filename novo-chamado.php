<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {$usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
    $titulo     = trim($_POST['titulo'] ?? '');
    $descricao  = trim($_POST['descricao'] ?? '');
    $categoria  =$_POST['categoria'] ?? 'Software';
    $prioridade =$_POST['prioridade'] ?? 'Média';

    if ($usuario_id && !empty($titulo) && !empty($descricao)) {$sql = "INSERT INTO chamados (usuario_id, titulo, descricao, categoria, prioridade) 
                VALUES (:usuario_id, :titulo, :descricao, :categoria, :prioridade)";
        
        $stmt = $pdo->prepare($sql);
        $sucesso =$stmt->execute([
            ':usuario_id' => $usuario_id,
            ':titulo'     => $titulo,
            ':descricao'  => $descricao,
            ':categoria'  => $categoria,
            ':prioridade' => $prioridade
        ]);

        if ($sucesso) {$mensagem = '<div class="alert alert-success">Chamado aberto com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-danger">Erro ao cadastrar chamado.</div>';
        }
    } else {
        $mensagem = '<div class="alert alert-warning">Preencha todos os campos obrigatórios.</div>';
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2 class="mb-3">Abrir Novo Chamado</h2>
        <?= $mensagem ?>

        <form action="novo-chamado.php" method="POST" class="card card-body shadow-sm">
            <div class="mb-3">
                <label for="usuario_id" class="form-label">Código do Usuário Solicitante (ID)</label>
                <input type="number" name="usuario_id" id="usuario_id" class="form-control" value="1" required>
                <small class="text-muted">Usuário inicial de teste criado no banco: ID 1</small>
            </div>

            <div class="mb-3">
                <label for="titulo" class="form-label">Título do Problema</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required placeholder="Ex: Impressora não responde na rede">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select name="categoria" id="categoria" class="form-select">
                        <option value="Hardware">Hardware</option>
                        <option value="Software" selected>Software</option>
                        <option value="Redes">Redes</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="prioridade" class="form-label">Prioridade</label>
                    <select name="prioridade" id="prioridade" class="form-select">
                        <option value="Baixa">Baixa</option>
                        <option value="Média" selected>Média</option>
                        <option value="Alta">Alta</option>
                        <option value="Crítica">Crítica</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição Detalhada</label>
                <textarea name="descricao" id="descricao" class="form-control" rows="4" required></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="index.php" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary">Registrar Chamado</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>