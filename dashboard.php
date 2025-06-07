<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'conn.php';
$nome = $_SESSION['user_nome'] ?? 'Cabra Arretado';

// Pega saldos fake (substitui por consulta real depois)
$saldo = '2.385,12';
$receitas = '4.000,00';
$despesas = '1.614,88';

// Exemplo de transações recentes (depois substitui pelo SELECT do banco)
$ultimas = [
    ['2025-07-01', 'Pix Salário', 'Receita', '+R$ 2.000,00', 'text-green-600'],
    ['2025-07-03', 'Supermercado', 'Alimentação', '-R$ 350,00', 'text-red-600'],
    ['2025-07-04', 'Transporte Uber', 'Transporte', '-R$ 25,00', 'text-red-600'],
    ['2025-07-06', 'Almoço com a galera', 'Lazer', '-R$ 90,00', 'text-red-600'],
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | DevFin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Mini animação (JS nativo, tempero baiano) -->
    <script>
      function animarSaldo() {
        const saldo = document.getElementById('saldo');
        saldo.classList.add('animate-bounce');
        setTimeout(() => saldo.classList.remove('animate-bounce'), 700);
      }
    </script>
</head>
<body class="bg-gradient-to-r from-blue-900 via-cyan-700 to-green-500 min-h-screen">
    <div class="flex">
        <!-- MENU LATERAL -->
        <aside class="w-60 bg-blue-950 min-h-screen text-white p-6 flex flex-col shadow-2xl">
            <div class="text-3xl font-extrabold mb-12 tracking-wide text-yellow-300 drop-shadow">DevFin</div>
            <nav class="flex flex-col gap-5 text-lg">
                <a href="dashboard.php" class="hover:bg-cyan-800 p-3 rounded transition">🏠 Dashboard</a>
                <a href="transacoes.php" class="hover:bg-cyan-800 p-3 rounded transition">💸 Transações</a>
                <a href="contas.php" class="hover:bg-cyan-800 p-3 rounded transition">🏦 Contas</a>
                <a href="orcamentos.php" class="hover:bg-cyan-800 p-3 rounded transition">🎯 Orçamentos</a>
                <a href="logout.php" class="hover:bg-red-700 bg-red-500 mt-12 p-3 rounded transition text-center font-bold">Sair</a>
            </nav>
            <div class="mt-auto text-sm text-blue-200 pt-10">Sistema feito com dendê © <?=date('Y')?></div>
        </aside>
        <!-- CONTEÚDO PRINCIPAL -->
        <main class="flex-1 p-10">
            <div class="flex items-center gap-4 mb-6">
                <img src="https://api.dicebear.com/7.x/bottts/svg?seed=<?=urlencode($nome)?>" alt="Avatar" class="w-14 h-14 rounded-full shadow-lg ring-4 ring-yellow-300">
                <h1 class="text-4xl font-extrabold tracking-tight text-white drop-shadow">Bem-vindo, <span class="text-yellow-300"><?=htmlspecialchars($nome)?></span>!</h1>
            </div>
            <!-- CARDS DE RESUMO -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-7 mb-12">
                <div onclick="animarSaldo()" id="saldo" class="bg-white/90 rounded-2xl shadow-lg p-7 cursor-pointer hover:scale-105 transition-all border-l-8 border-green-500">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-green-700 text-2xl font-bold">Saldo Atual</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-green-600 drop-shadow-lg">R$ <?=$saldo?></div>
                </div>
                <div class="bg-white/90 rounded-2xl shadow-lg p-7 border-l-8 border-blue-500">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-blue-700 text-2xl font-bold">Receitas</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-blue-600 drop-shadow-lg">R$ <?=$receitas?></div>
                </div>
                <div class="bg-white/90 rounded-2xl shadow-lg p-7 border-l-8 border-red-500">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-red-700 text-2xl font-bold">Despesas</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-red-600 drop-shadow-lg">R$ <?=$despesas?></div>
                </div>
            </div>
            <!-- ÚLTIMAS TRANSAÇÕES -->
            <div class="bg-white/90 rounded-2xl shadow-xl p-8">
                <div class="flex justify-between items-center mb-5">
                    <div class="text-2xl font-semibold text-blue-900">Últimas Transações</div>
                    <button onclick="alert('Aqui vai abrir o formulário!')" class="bg-cyan-600 text-white px-6 py-2 rounded-xl shadow-lg font-bold hover:bg-cyan-800 transition-all">+ Nova</button>
                </div>
                <table class="w-full text-base">
                    <thead>
                        <tr class="bg-blue-100 text-blue-900">
                            <th class="text-left py-2 px-4 rounded-tl-lg">Data</th>
                            <th class="text-left py-2 px-4">Descrição</th>
                            <th class="text-left py-2 px-4">Categoria</th>
                            <th class="text-left py-2 px-4 rounded-tr-lg">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimas as $t): ?>
                        <tr class="hover:bg-blue-50 transition-all">
                            <td class="py-2 px-4"><?=htmlspecialchars(date('d/m/Y', strtotime($t[0])))?></td>
                            <td class="py-2 px-4"><?=htmlspecialchars($t[1])?></td>
                            <td class="py-2 px-4"><?=htmlspecialchars($t[2])?></td>
                            <td class="py-2 px-4 font-bold <?=$t[4]?>"><?=$t[3]?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-12 text-center text-white font-semibold drop-shadow">Tá sentindo o tempero? Aqui é DevFin, mainha!</div>
        </main>
    </div>
</body>
</html>