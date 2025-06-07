<?php
// =====================================
// ARQUIVO: login.php
// Função: Receber login, validar e iniciar sessão do usuário
// =====================================

// 1. Inicia sessão para salvar login
session_start();

// 2. Inclui a conexão com o banco
include 'conn.php';

// 3. Checa se veio formulário via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // 4. Valida campos
    if (empty($email) || empty($senha)) {
        die('Preencha todos os campos! <a href="login.html">Voltar</a>');
    }

    // 5. Busca o usuário pelo email
    $stmt = $conn->prepare("SELECT id, nome, senha_hash FROM users WHERE email = ?");
    if (!$stmt) {
        die('Erro na query: ' . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // 6. Se achou usuário, confere senha
    if ($user = $result->fetch_assoc()) {
        if (password_verify($senha, $user['senha_hash'])) {
            // 7. Login ok: salva infos na sessão e redireciona pro dashboard
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            header('Location: dashboard.php');
            exit;
        } else {
            // Senha errada
            echo 'Senha incorreta! <a href="login.html">Tentar de novo</a>';
        }
    } else {
        // Usuário não encontrado
        echo 'Usuário não encontrado! <a href="login.html">Tentar de novo</a>';
    }

    $stmt->close();
    $conn->close();
} else {
    // Se tentar acessar direto, redireciona pro form
    header('Location: login.html');
    exit;
}
?>