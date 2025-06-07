<?php
// ================================
// ARQUIVO: dashboard.php
// Função: Tela inicial do usuário logado
// ================================

// 1. Inicia sessão
session_start();

// 2. Garante que só entra quem tá logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// 3. Pega o nome do usuário logado
$nome = $_SESSION['user_nome'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | DevFin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container {
            max-width: 480px;
            margin: 60px auto;
            background: #fff;
            padding: 32px 38px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            text-align: center;
        }
        h2 { margin-bottom: 18px; }
        .user { color: #278ea5; font-weight: bold; }
        .logout-btn {
            display: inline-block;
            margin-top: 24px;
            padding: 10px 30px;
            background: #eb2d2d;
            color: #fff;
            border: none;
            border-radius: 7px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
        }
        .logout-btn:hover { background: #a51c1c; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bem-vindo, <span class="user"><?= htmlspecialchars($nome) ?></span>!</h2>
        <p>Você está logado no sistema DevFin.</p>
        <a href="logout.php" class="logout-btn">Sair</a>
    </div>
</body>
</html>