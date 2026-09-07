<?php
require_once __DIR__ . '/../config/auth.php';

require_auth();

$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Raphzx Portfolio</title>
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
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .sidebar-link { transition: all 0.3s ease; }
        .sidebar-link:hover, .sidebar-link.active { color: #38bdf8; background: rgba(56,189,248,0.1); }
        .sidebar-link-logout { color: #f87171; }
        .sidebar-link-logout:hover, .sidebar-link-logout.active { color: #f87171; background: rgba(239,68,68,0.12); }
        .input-field-admin {
            background: rgba(15, 32, 53, 0.5);
            border: 1px solid rgba(56, 189, 248, 0.15);
            transition: all 0.4s ease;
        }
        .input-field-admin:focus {
            outline: none;
            border-color: rgba(56, 189, 248, 0.5);
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.1);
            background: rgba(15, 32, 53, 0.7);
        }
        select.input-field-admin option { background: #0a1628; }
        .table-row:hover { background: rgba(56, 189, 248, 0.05); }
        .btn-danger { transition: all 0.3s ease; }
        .btn-danger:hover { background: rgba(239,68,68,0.2); border-color: rgba(239,68,68,0.5); }
    </style>
</head>
<body class="bg-nightwing-950 text-white font-inter min-h-screen">
<div class="flex min-h-screen">

    <!-- Mobile top bar -->
    <div class="md:hidden fixed top-0 left-0 right-0 z-50 bg-nightwing-900/95 backdrop-blur-xl border-b border-neon-blue/10 px-4 py-3 flex items-center justify-between">
        <button id="sidebarToggle" class="text-gray-400 hover:text-neon-blue transition-colors" aria-label="Buka menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
        </button>
        <a href="index.php" class="font-orbitron text-sm font-bold gradient-text">Admin Panel</a>
        <a href="../logout.php" class="text-red-400 text-xs px-3 py-1.5 rounded-lg border border-red-500/30 hover:bg-red-500/10 transition-colors">Logout</a>
    </div>

    <!-- Backdrop untuk mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 z-30 bg-black/60 backdrop-blur-sm hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-nightwing-900/95 backdrop-blur-xl border-r border-neon-blue/10 flex flex-col fixed inset-y-0 left-0 z-40 md:z-40 -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-6 border-b border-neon-blue/10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full border border-neon-blue/30 flex items-center justify-center bg-nightwing-800">
                <svg class="w-5 h-5 text-neon-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="font-orbitron text-sm font-bold gradient-text">Admin Panel</h1>
                <p class="text-gray-600 text-xs truncate"><?= htmlspecialchars($_SESSION['username']) ?></p>
            </div>
            <button id="sidebarClose" class="md:hidden text-gray-500 hover:text-neon-blue transition-colors" aria-label="Tutup menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="index.php" class="sidebar-link <?= $current_page === 'index.php' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                Dashboard
            </a>
            <a href="projects.php" class="sidebar-link <?= in_array($current_page, ['projects.php', 'project_form.php']) ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/></svg>
                Projects
            </a>
            <a href="change_password.php" class="sidebar-link <?= $current_page === 'change_password.php' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                Ubah Password
            </a>
            <a href="../index.php" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-sm text-gray-400 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                Lihat Portfolio
            </a>
            <a href="../logout.php" class="sidebar-link sidebar-link-logout flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                Logout
            </a>
        </nav>
        <div class="p-4 border-t border-neon-blue/10">
            <p class="text-gray-700 text-xs font-orbitron text-center">© <?= date('Y') ?> Raphzx</p>
        </div>
    </aside>

    <div class="flex-1 md:ml-64 pt-14 md:pt-0">
        <main class="p-6 md:p-10">

<script>
(function () {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebarOverlay');
    var toggle  = document.getElementById('sidebarToggle');
    var closeBtn = document.getElementById('sidebarClose');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    toggle.addEventListener('click', openSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    document.querySelectorAll('#sidebar a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < 768) {
                closeSidebar();
            }
        });
    });
})();
</script>