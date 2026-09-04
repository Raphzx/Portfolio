<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= SITE['description'] ?>">
    <meta name="author" content="<?= SITE['author'] ?>">
    <title><?= SITE['title'] ?></title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2338bdf8' stroke-width='1.5'><path d='M12 3C7 3 3 7 3 12c0 3 1.5 5.5 4 7l1-2c1.5 1 3 1.5 4 1.5s2.5-.5 4-1.5l1 2c2.5-1.5 4-4 4-7 0-5-4-9-9-9z'/><circle cx='8.5' cy='10' r='1.5' fill='%2338bdf8' opacity='0.8'/><circle cx='15.5' cy='10' r='1.5' fill='%2338bdf8' opacity='0.8'/></svg>">

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
<body class="bg-nightwing-950 text-white font-inter">

<?php
for ($i = 0; $i < 30; $i++):
    $left     = rand(0, 100);
    $delay    = rand(0, 10) / 10;
    $duration = rand(8, 20) / 10 * 3;
    $size     = rand(1, 4);
    $opacity  = rand(20, 60) / 100;
?>
    <div class="particle" style="left:<?= $left ?>%;animation:particle <?= $duration ?>s linear <?= $delay ?>s infinite;width:<?= $size ?>px;height:<?= $size ?>px;opacity:<?= $opacity ?>;"></div>
<?php endfor; ?>

<div class="scan-line"></div>
