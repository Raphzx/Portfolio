<?php
require_once __DIR__ . '/config/auth.php';

if (is_logged_in()) {
    header('Location: admin/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } else {
        $stmt = db()->prepare("SELECT id, username, password FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']   = (int)$user['id'];
            $_SESSION['username']  = $user['username'];
            header('Location: admin/');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - <?= htmlspecialchars($GLOBALS['SITE']['title'] ?? 'Raphzx Portfolio') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nightwing: {
                            950: '#020617',
                            900: '#0a1628',
                            800: '#0f2035',
                            700: '#152a42',
                            600: '#1a3450',
                        },
                        neon: {
                            blue: '#38bdf8',
                            cyan: '#22d3ee',
                            glow: '#0ea5e9',
                        }
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-nightwing-950 text-white font-inter min-h-screen flex items-center justify-center px-6">
    <div class="hero-grid fixed inset-0 pointer-events-none"></div>
    <div class="scan-line"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full border border-neon-blue/30 flex items-center justify-center bg-nightwing-900/80 shadow-[0_0_30px_rgba(56,189,248,0.2)]">
                <svg class="w-8 h-8 text-neon-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
            </div>
            <h1 class="font-orbitron text-2xl font-bold gradient-text">Admin Login</h1>
            <p class="text-gray-500 text-sm mt-2">Sign in to manage your portfolio</p>
        </div>

        <div class="glass-card rounded-2xl p-8">
            <?php if ($error): ?>
                <div class="mb-6 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-5">
                <div>
                    <label class="block text-gray-400 text-sm font-medium mb-2">Username</label>
                    <input type="text" name="username" required
                           class="input-field w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="Enter username">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" required
                           class="input-field w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="Enter password">
                </div>
                <button type="submit"
                        class="neon-btn w-full py-3 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
                    Login
                </button>
            </form>
        </div>

        <div class="text-center mt-8">
            <a href="./" class="text-gray-500 hover:text-neon-blue transition-colors text-sm">&larr; Back to portfolio</a>
        </div>
    </div>
</body>
</html>
