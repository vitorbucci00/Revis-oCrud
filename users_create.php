<?php

require 'db.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    if ($nome === '' || $email === '') {
        $msg = 'Todos os campos são obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Email inválido.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO users (nome,email) VALUES (?,?)');
        $stmt->execute([$nome,$email]);
        $msg = 'Cadastro concluído com sucesso';
        $nome = $email = '';
    }
}
?>
<h2>Cadastro de Usuários</h2>
<?php if ($msg) echo '<p class="msg">'.htmlspecialchars($msg).'</p>'; ?>
<form method="post">
    <label>Nome<br><input type="text" name="nome" value="<?=htmlspecialchars($nome ?? '')?>" required></label><br>
    <label>Email<br><input type="email" name="email" value="<?=htmlspecialchars($email ?? '')?>" required></label><br>
    <button type="submit">Cadastrar</button>
</form>
