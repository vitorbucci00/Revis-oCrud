<?php
require 'db.php';
$msg = '';
// Obter usuários para o select
$users = $pdo->query('SELECT id,nome FROM users ORDER BY nome')->fetchAll();

// Editar tarefa se for passado ?edit=ID
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_task = $pdo->prepare('SELECT * FROM tasks WHERE id=?');
    $edit_task->execute([$edit_id]);
    $task = $edit_task->fetch();
    if ($task) {
        $user_id = $task['user_id'];
        $descricao = $task['descricao'];
        $setor = $task['setor'];
        $prioridade = $task['prioridade'];
        $edit_mode = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id'] ?? 0);
    $descricao = trim($_POST['descricao'] ?? '');
    $setor = trim($_POST['setor'] ?? '');
    $prioridade = $_POST['prioridade'] ?? '';
    if ($user_id <= 0 || $descricao === '' || $setor === '' || $prioridade === '') {
        $msg = 'Todos os campos são obrigatórios.';
    } else {
        if (isset($_POST['edit_id'])) {
            $edit_id = intval($_POST['edit_id']);
            $stmt = $pdo->prepare('UPDATE tasks SET user_id=?, descricao=?, setor=?, prioridade=? WHERE id=?');
            $stmt->execute([$user_id,$descricao,$setor,$prioridade,$edit_id]);
            $msg = 'Tarefa atualizada com sucesso';
            unset($edit_mode);
        } else {
            $stmt = $pdo->prepare('INSERT INTO tasks (user_id,descricao,setor,prioridade) VALUES (?,?,?,?)');
            $stmt->execute([$user_id,$descricao,$setor,$prioridade]);
            $msg = 'Tarefa cadastrada com sucesso';
        }
        // limpar
        $descricao = $setor = '';
        $prioridade = '';
    }
}
?>
<h2><?=isset($edit_mode)?'Editar':'Cadastro de'?> Tarefas</h2>
<?php if ($msg) echo '<p class="msg">'.htmlspecialchars($msg).'</p>'; ?>
<form method="post">
    <?php if (isset($edit_mode)): ?><input type="hidden" name="edit_id" value="<?=htmlspecialchars($edit_id)?>"><?php endif; ?>
    <label>Usuário<br>
    <select name="user_id" required>
        <option value="">-- selecione --</option>
        <?php foreach ($users as $u): ?>
            <option value="<?=$u['id']?>" <?= (isset($user_id) && $user_id==$u['id'])?'selected':''?>><?=htmlspecialchars($u['nome'])?></option>
        <?php endforeach; ?>
    </select>
    </label><br>
    <label>Descrição<br><textarea name="descricao" required><?=htmlspecialchars($descricao ?? '')?></textarea></label><br>
    <label>Setor<br><input type="text" name="setor" value="<?=htmlspecialchars($setor ?? '')?>" required></label><br>
    <label>Prioridade<br>
        <select name="prioridade" required>
            <option value="">-- selecione --</option>
            <option value="baixa" <?= (isset($prioridade) && $prioridade=='baixa')?'selected':''?>>baixa</option>
            <option value="média" <?= (isset($prioridade) && $prioridade=='média')?'selected':''?>>média</option>
            <option value="alta" <?= (isset($prioridade) && $prioridade=='alta')?'selected':''?>>alta</option>
        </select>
    </label><br>
    <button type="submit"><?=isset($edit_mode)?'Salvar Alterações':'Cadastrar Tarefa'?></button>
</form>
