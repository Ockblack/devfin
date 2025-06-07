<?php
session_start();
require_once 'conn.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Consulta o usuário
    $sql = "SELECT id, nome, senha_hash FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nome, $hash);
        $stmt->fetch();
        if (password_verify($senha, $hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_nome'] = $nome;
            header('Location: dashboard.php');
            exit;
        } else {
            $erro = 'Senha incorreta. Vixe, tente de novo!';
        }
    } else {
        $erro = 'Usuário não encontrado. Vai cadastrar, mainha!';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - DevFin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-900 via-cyan-700 to-green-500 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-10 w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-blue-900 mb-2 drop-shadow-lg">DevFin</h1>
            <p class="text-gray-500 text-lg">Entre pra ver sua vida financeira temperada!</p>
        </div>
        <?php if ($erro): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-6 animate-pulse">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
            <label class="block mb-2 text-blue-900 font-semibold" for="email">E-mail</label>
            <input type="email" id="email" name="email" class="w-full border-2 border-blue-300 rounded-lg p-3 mb-5 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            <label class="block mb-2 text-blue-900 font-semibold" for="senha">Senha</label>
            <input type="password" id="senha" name="senha" class="w-full border-2 border-blue-300 rounded-lg p-3 mb-7 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            <button type="submit" class="w-full bg-blue-900 text-white py-3 rounded-lg font-bold text-lg hover:bg-green-600 transition-all shadow-lg hover:scale-105">Entrar</button>
        </form>
        <div class="mt-6 text-center">
            <a href="register.php" class="text-cyan-700 hover:underline font-bold">Não tem conta? Cadastre-se!</a>
        </div>
    </div>
</body>
</html>