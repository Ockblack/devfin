<?php
// ARQUIVO: logout.php
session_start();
session_unset(); // Limpa variáveis
session_destroy(); // Destroi sessão
header('Location: login.html'); // Volta pro login
exit;
?>