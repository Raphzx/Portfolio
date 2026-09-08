<?php
require_once __DIR__ . '/header.php';

$totalProjects = (int)db()->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$withDemo      = (int)db()->query("SELECT COUNT(*) FROM projects WHERE demo_url IS NOT NULL AND demo_url <> ''")->fetchColumn();
$withSource    = (int)db()->query("SELECT COUNT(*) FROM projects WHERE source_url IS NOT NULL AND source_url <> ''")->fetchColumn();
$totalSkills   = (int)db()->query("SELECT COUNT(*) FROM skills")->fetchColumn();
$totalSocials  = (int)db()->query("SELECT COUNT(*) FROM contact_socials")->fetchColumn();
$recent        = db()->query("SELECT title, updated_at FROM projects ORDER BY updated_at DESC LIMIT 5")->fetchAll();
?>

<div class="mb-8">
    <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2">Dashboard</h1>
    <p class="text-gray-500 text-sm">Welcome back, <span class="text-neon-blue"><?= htmlspecialchars($_SESSION['username']) ?></span>. Manage your portfolio here.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-neon-blue/10 border border-neon-blue/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-neon-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider">Total Projects</p>
                <p class="font-orbitron text-3xl font-bold text-neon-blue"><?= $totalProjects ?></p>
            </div>
        </div>
    </div>
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-neon-blue/10 border border-neon-blue/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-neon-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider">Has Demo</p>
                <p class="font-orbitron text-3xl font-bold text-neon-blue"><?= $withDemo ?></p>
            </div>
        </div>
    </div>
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-neon-blue/10 border border-neon-blue/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-neon-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wider">Has Source</p>
                <p class="font-orbitron text-3xl font-bold text-neon-blue"><?= $withSource ?></p>
            </div>
        </div>
    </div>
</div>

<div class="glass-card rounded-2xl p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-orbitron text-lg font-bold text-white">Recent Projects</h2>
        <a href="projects" class="text-neon-blue hover:text-cyan-400 text-sm transition-colors">View all &rarr;</a>
    </div>
    <?php if (empty($recent)): ?>
        <p class="text-gray-500 text-sm">No projects yet. <a href="project_form" class="text-neon-blue">Add your first project &rarr;</a></p>
    <?php else: ?>
        <div class="space-y-2">
            <?php foreach ($recent as $r): ?>
                <div class="flex items-center justify-between gap-4 px-4 py-3 rounded-lg bg-nightwing-900/40 border border-neon-blue/5 table-row">
                    <span class="text-gray-300 text-sm min-w-0 truncate"><?= htmlspecialchars($r['title']) ?></span>
                    <span class="text-gray-600 text-xs shrink-0 whitespace-nowrap"><?= htmlspecialchars(date('d M Y, H:i', strtotime($r['updated_at']))) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="glass-card rounded-2xl p-6">
    <h2 class="font-orbitron text-lg font-bold text-white mb-4">Quick Actions</h2>
    <div class="flex flex-wrap gap-4">
        <a href="project_form" class="neon-btn inline-flex items-center gap-2 px-6 py-3 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Project
        </a>
        <a href="skill_form" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-neon-blue/20 text-gray-400 hover:text-neon-blue transition-colors font-orbitron text-sm font-semibold tracking-widest uppercase">
            Add Skill
        </a>
        <a href="site_settings" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-neon-blue/20 text-gray-400 hover:text-neon-blue transition-colors font-orbitron text-sm font-semibold tracking-widest uppercase">
            Site Settings
        </a>
        <a href="../" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-neon-blue/20 text-gray-400 hover:text-neon-blue transition-colors font-orbitron text-sm font-semibold tracking-widest uppercase">
            Open Portfolio
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
