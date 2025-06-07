<?php
// ===============================
// Arquivo: conn.php
// Conexão segura com MySQL (XAMPP)
// ===============================

// Configurações do banco de dados
$host = 'localhost';    // Servidor do MySQL (padrão XAMPP)
$user = 'root';         // Usuário do MySQL (padrão XAMPP)
$pass = '';             // Senha do MySQL (em XAMPP é vazio)
$db   = 'devfin';       // Nome do banco que você criou

// Cria conexão com MySQL
$conn = new mysqli($host, $user, $pass, $db);

// Verifica se deu erro
if ($conn->connect_error) {
    // Nunca exiba erro real em produção, aqui é didático!
    die('Erro de conexão: ' . $conn->connect_error);
}

// Força charset utf8mb4 (suporte total a acentos e emoji)
$conn->set_charset('utf8mb4');
?>