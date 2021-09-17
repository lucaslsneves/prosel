<?php
// Inicia sessões
session_start();

// Verifica se existe os dados da sessão de login
if(!isset($_SESSION["id_usuario"]) || !isset($_SESSION["login_usuario"]) || $_SESSION["id_usuario"] == null || $_SESSION["login_usuario"] == null)
{
// Usuário não logado! Redireciona para a página de login
header("Location: admin.php");
exit;
}
?>