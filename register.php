<?php
session_start();
require_once 'conn.php';

$sucesso = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Validação simples
    if (!$nome || !$email || !$senha) {
        $erro = 'Preencha todos os campos!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido!';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres!';
    } else {
        // Checa duplicidade
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erro = 'E-mail já cadastrado!';
        } else {
            // Criptografa a senha
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (nome, email, senha_hash) VALUES (?, ?, ?)";
            $stmt2 = $conn->prepare($sql);
            $stmt2->bind_param('sss', $nome, $email, $hash);
            if ($stmt2->execute()) {
                $sucesso = 'Cadastro realizado com sucesso!';

                // // Descomente para logar automaticamente após cadastro:
                // $_SESSION['user_id'] = $stmt2->insert_id;
                // $_SESSION['user_nome'] = $nome;
                // header('Location: dashboard.php');
                // exit;

            } else {
                $erro = 'Erro ao cadastrar. Tente novamente!';
            }
            $stmt2->close();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - DevFin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-900 via-cyan-700 to-green-500 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-10 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-blue-900 mb-2 drop-shadow-lg">DevFin</h1>
            <p class="text-gray-500 text-lg">Cadastre-se e controle sua grana com tempero baiano!</p>
        </div>
        <?php if ($erro): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-6 animate-pulse">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php elseif ($sucesso): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-6 animate-bounce">
                <?= htmlspecialchars($sucesso) ?>
            </div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
            <label class="block mb-2 text-blue-900 font-semibold" for="nome">Nome</label>
            <input type="text" id="nome" name="nome" class="w-full border-2 border-blue-300 rounded-lg p-3 mb-5 focus:outline-none focus:ring-2 focus:ring-blue-600" required>

            <label class="block mb-2 text-blue-900 font-semibold" for="email">E-mail</label>
            <input type="email" id="email" name="email" class="w-full border-2 border-blue-300 rounded-lg p-3 mb-5 focus:outline-none focus:ring-2 focus:ring-blue-600" required>

            <label class="block mb-2 text-blue-900 font-semibold" for="senha">Senha</label>
            <input type="password" id="senha" name="senha" class="w-full border-2 border-blue-300 rounded-lg p-3 mb-7 focus:outline-none focus:ring-2 focus:ring-blue-600" required minlength="6">

            <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-lg font-bold text-lg hover:bg-green-600 transition-all shadow-lg hover:scale-105">Cadastrar</button>
        </form>
        <div class="mt-6 text-center">
            <a href="login.php" class="text-cyan-700 hover:underline font-bold">Já tem conta? Faça login!</a>
        </div>
    </div>
</body>
</html>