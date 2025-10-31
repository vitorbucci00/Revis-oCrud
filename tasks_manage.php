<?php
require 'db.php';

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: ?view=manage'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_task'])) {
    $id = intval($_POST['id']);
    $prioridade = $_POST['prioridade'];
    $status = $_POST['status'];
    $descricao = trim($_POST['descricao']);
    $setor = trim($_POST['setor']);
    $user_id = intval($_POST['user_id']);
    if ($descricao === '' || $setor === '' || $prioridade=='' || $status=='' || $user_id<=0) {
        $error = 'Todos os campos são obrigatórios.';
    } else {
        $stmt = $pdo->prepare('UPDATE tasks SET descricao=?,setor=?,prioridade=?,status=?,user_id=? WHERE id=?');
        $stmt->execute([$descricao,$setor,$prioridade,$status,$user_id,$id]);
        header('Location: ?view=manage'); exit;
    }
}


$tasks = $pdo->query('SELECT t.*, u.nome AS usuario_nome FROM tasks t JOIN users u ON u.id=t.user_id ORDER BY data_cadastro DESC')->fetchAll();
$colunas = ['a fazer'=>[], 'fazendo'=>[], 'pronto'=>[]];
foreach ($tasks as $t) $colunas[$t['status']][] = $t;
?>
<h2>Gerenciar Tarefas</h2>
<?php if (!empty($error)) echo '<p class="err">'.htmlspecialchars($error).'</p>'; ?>
<div class="board">
    <?php foreach ($colunas as $status => $lista): ?>
        <div class="col">
            <h3><?=htmlspecialchars(strtoupper($status))?></h3>
            <?php foreach ($lista as $task): ?>
                <div class="card">
                    <p><strong><?=htmlspecialchars($task['descricao'])?></strong></p>
                    <p>Setor: <?=htmlspecialchars($task['setor'])?> | Prioridade: <?=htmlspecialchars($task['prioridade'])?></p>
                    <p>Usuário: <?=htmlspecialchars($task['usuario_nome'])?></p>
                    <p>Data: <?=htmlspecialchars($task['data_cadastro'])?></p>
                    <p>
                        <a href="?view=tasks&edit=<?=$task['id']?>">Editar</a> |
                        <a href="?view=manage&delete=<?=$task['id']?>" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </p>
                    <form method="post" style="margin-top:8px;">
                        <input type="hidden" name="id" value="<?=$task['id']?>">
                        <input type="hidden" name="update_task" value="1">
                        <label>Prioridade:
                            <select name="prioridade">
                                <option value="baixa" <?=($task['prioridade']=='baixa')?'selected':''?>>baixa</option>
                                <option value="média" <?=($task['prioridade']=='média')?'selected':''?>>média</option>
                                <option value="alta" <?=($task['prioridade']=='alta')?'selected':''?>>alta</option>
                            </select>
                        </label>
                        <label>Status:
                            <select name="status">
                                <option value="a fazer" <?=($task['status']=='a fazer')?'selected':''?>>a fazer</option>
                                <option value="fazendo" <?=($task['status']=='fazendo')?'selected':''?>>fazendo</option>
                                <option value="pronto" <?=($task['status']=='pronto')?'selected':''?>>pronto</option>
                            </select>
                        </label>
                        <button type="submit">Atualizar</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>
