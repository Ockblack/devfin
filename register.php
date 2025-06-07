<?php
// ================================
// ARQUIVO: register.php
// Função: Receber dados do formulário de cadastro, validar e inserir usuário no banco
// ================================

// 1. Inclui o arquivo de conexão com o banco
include 'conn.php';

// 2. Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 3. Coleta e limpa os dados do formulário
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // 4. Validações básicas (não deixa campo vazio)
    if (empty($nome) || empty($email) || empty($senha)) {
        // Se faltar algo, mostra mensagem e encerra
        die('Preencha todos os campos!');
    }

    // 5. Valida e-mail (simples, só pra garantir formato)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('E-mail inválido!');
    }

    // 6. Criptografa a senha antes de salvar (bcrypt)
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // 7. Tenta inserir o usuário e trata duplicidade de e-mail
    try {
        // Prepara query pra inserir usuário (evita SQL injection)
        $stmt = $conn->prepare("INSERT INTO users (nome, email, senha_hash) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception('Erro ao preparar a query: ' . $conn->error);
        }

        // Associa os valores às interrogações (sss = string, string, string)
        $stmt->bind_param("sss", $nome, $email, $senhaHash);

        // Executa a query
        $stmt->execute();

        // Se chegar aqui, cadastro foi feito!
       echo 'Usuário cadastrado com sucesso! <a href="login.html">Ir para o login</a> ou <a href="register.html">Cadastrar outro</a>';

        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        // Trata erro de duplicidade de e-mail
        if (strpos($e->getMessage(), 'Duplicate') !== false) {
            echo 'Este e-mail já está cadastrado! <a href="register.html">Tentar outro</a>';
        } else {
            echo 'Erro ao cadastrar: ' . $e->getMessage();
        }
    }

    // Fecha conexão
    $conn->close();
} else {
    // Se alguém tentar acessar direto, redireciona pro formulário
    header('Location: register.html');
    exit;
}
?>