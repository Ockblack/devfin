<?php
// ======================================
// ARQUIVO: conn.php
// Função: Conectar ao banco de dados MySQL
// ======================================

// Configurações do banco de dados
$host = 'localhost';      // Endereço do servidor MySQL
$user = 'root';           // Usuário padrão do XAMPP
$pass = '';               // Senha padrão do XAMPP (vazio)
$db   = 'devfin';         // Nome do banco que você criou

// Cria a conexão usando MySQLi
$conn = new mysqli($host, $user, $pass, $db);

// Verifica se deu erro
if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}
// Conexão ok, arquivo pronto pra ser incluído nos outros scripts
?>