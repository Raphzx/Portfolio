<?php
require_once __DIR__ . '/auth.php';
require_auth();

$pdo = db();

$pdo->exec("CREATE TABLE IF NOT EXISTS site_content (
    section VARCHAR(50) PRIMARY KEY,
    content JSON NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    level INT NOT NULL DEFAULT 0,
    icon VARCHAR(500) NOT NULL,
    sort_order INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS about_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value VARCHAR(50) NOT NULL,
    label VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS about_features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    icon_code TEXT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    sort_order INT DEFAULT 0
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS contact_socials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(100) NOT NULL,
    url VARCHAR(500) NOT NULL,
    path TEXT NOT NULL,
    sort_order INT DEFAULT 0
)");

$existing = $pdo->query("SELECT COUNT(*) FROM site_content")->fetchColumn();
if ((int)$existing === 0) {
    $stmt = $pdo->prepare("INSERT INTO site_content (section, content) VALUES (:section, :content)");

    $stmt->execute(['section' => 'site', 'content' => json_encode([
        'title'       => 'Raphzx Portfolio',
        'tagline'     => 'Junior Software Developer',
        'description' => 'Crafting exceptional web applications with clean code, modern architecture, and pixel-perfect design.',
        'author'      => 'Raphzx',
        'email'       => 'raphwahyudi@gmail.com',
        'location'    => 'Banjarmasin, Indonesia',
    ])]);

    $stmt->execute(['section' => 'hero', 'content' => json_encode([
        'greeting'   => '< Junior Software Developer />',
        'title_1'    => 'I Build',
        'title_2'    => 'Digital',
        'title_3'    => 'Experiences',
        'subtitle'   => 'Building functional and responsive web applications with clean code.',
        'cta_1_text' => 'View Projects',
        'cta_1_href' => '#projects',
        'cta_2_text' => 'Contact Me',
        'cta_2_href' => '#contact',
    ])]);

    $stmt->execute(['section' => 'about', 'content' => json_encode([
        'tag'         => 'Get To Know Me',
        'heading'     => 'About Me',
        'number'      => '01',
        'title'       => 'The Developer',
        'paragraph_1' => 'Vocational high school students at SMK ISFI Banjarmasin who are focused and dedicated to full-stack web development. Used to building efficient, modern, and functional web applications with a clean interface',
        'paragraph_2' => 'Actively developing various school-based web projects and management systems. Combining technical coding skills with creative problem-solving to come up with the best solutions.',
    ])]);

    $stmt->execute(['section' => 'contact', 'content' => json_encode([
        'tag'       => 'Get In Touch',
        'heading'   => 'Contact Me',
        'subtitle'  => 'Have a question or want to discuss a project? Drop a message below.',
    ])]);

    $skillsData = [
        ['name' => 'JavaScript', 'level' => 45, 'icon' => 'assets/logo/javascript.png', 'sort_order' => 1],
        ['name' => 'React / Next.js', 'level' => 30, 'icon' => 'assets/logo/react.png', 'sort_order' => 2],
        ['name' => 'Node.js', 'level' => 23, 'icon' => 'assets/logo/nodejs.png', 'sort_order' => 3],
        ['name' => 'PHP / Laravel', 'level' => 90, 'icon' => 'assets/logo/laravel.png', 'sort_order' => 4],
        ['name' => 'Python', 'level' => 50, 'icon' => 'assets/logo/python.png', 'sort_order' => 5],
        ['name' => 'Database MySql', 'level' => 85, 'icon' => 'assets/logo/mysql.png', 'sort_order' => 6],
        ['name' => 'Tailwind CSS', 'level' => 70, 'icon' => 'assets/logo/tailwind.png', 'sort_order' => 7],
        ['name' => 'UI/UX Design', 'level' => 82, 'icon' => 'assets/logo/ux-design.png', 'sort_order' => 8],
        ['name' => 'HTML5 / CSS3', 'level' => 98, 'icon' => 'assets/logo/html-css3.png', 'sort_order' => 9],
    ];
    $stmtSkill = $pdo->prepare("INSERT INTO skills (name, level, icon, sort_order) VALUES (:name, :level, :icon, :sort_order)");
    foreach ($skillsData as $s) {
        $stmtSkill->execute($s);
    }

    $statsData = [
        ['value' => '3+', 'label' => 'Years Exp', 'sort_order' => 1],
        ['value' => '3', 'label' => 'Projects', 'sort_order' => 2],
        ['value' => '0', 'label' => 'Clients', 'sort_order' => 3],
    ];
    $stmtStat = $pdo->prepare("INSERT INTO about_stats (value, label, sort_order) VALUES (:value, :label, :sort_order)");
    foreach ($statsData as $s) {
        $stmtStat->execute($s);
    }

    $featuresData = [
        ['icon_code' => 'M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5', 'title' => 'Clean Code', 'description' => 'Writing structured, readable, and easy-to-maintain code.', 'sort_order' => 1],
        ['icon_code' => 'M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42', 'title' => 'UI/UX Design', 'description' => 'Designing clean, responsive, and user-friendly interfaces.', 'sort_order' => 2],
        ['icon_code' => 'M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z', 'title' => 'Performance', 'description' => 'Ensuring fast loading times and smooth overall web application performance.', 'sort_order' => 3],
    ];
    $stmtFeat = $pdo->prepare("INSERT INTO about_features (icon_code, title, description, sort_order) VALUES (:icon_code, :title, :description, :sort_order)");
    foreach ($featuresData as $f) {
        $stmtFeat->execute($f);
    }

    $socialsData = [
        ['platform' => 'GitHub', 'url' => 'https://github.com/Raphzx', 'path' => 'M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z', 'sort_order' => 1],
        ['platform' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/rafi-hafidz-wahyudi/', 'path' => 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z', 'sort_order' => 2],
        ['platform' => 'Twitter', 'url' => 'https://x.com/Raphzx_', 'path' => 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z', 'sort_order' => 3],
    ];
    $stmtSocial = $pdo->prepare("INSERT INTO contact_socials (platform, url, path, sort_order) VALUES (:platform, :url, :path, :sort_order)");
    foreach ($socialsData as $s) {
        $stmtSocial->execute($s);
    }
}

header('Location: ../admin/?setup=1');
exit;
