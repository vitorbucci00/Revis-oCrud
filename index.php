<?php
require 'db.php';

$view = $_GET['view'] ?? 'home';

function header_html() {
    echo '<!doctype html><html><head><meta charset="utf-8"><title>ToDo List</title>';
    echo '<link rel="stylesheet" href="styles.css">';
    echo '</head><body>';
    echo '<nav><a href="?view=home">Home</a> | <a href="?view=users">Cadastro Usuários</a> | <a href="?view=tasks">Cadastro Tarefas</a> | <a href="?view=manage">Gerenciar Tarefas</a></nav>';
}

function footer_html() { echo '</body></html>'; }

header_html();

if ($view === 'home') {
    echo '<h1>Sistema de Gerenciamento de Tarefas</h1>';
    echo '<p>Use o menu para navegar.</p>';
} elseif ($view === 'users') {
    include 'users_create.php';
} elseif ($view === 'tasks') {
    include 'tasks_create.php';
} elseif ($view === 'manage') {
    include 'tasks_manage.php';
} else {
    echo '<p>View não encontrada.</p>';
}

footer_html();
?>
