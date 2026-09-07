<?php
require_once __DIR__ . '/../config/database.php';

$projectItems = [];

try {
    $projectItems = db()->query("SELECT * FROM projects ORDER BY sort_order ASC, id ASC")->fetchAll();
} catch (Exception $e) {
    $projectItems = [];
}
?>
<section id="projects" class="relative py-32 overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-neon-blue/20 to-transparent"></div>
    <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-neon-cyan/5 rounded-full blur-[120px]"></div>
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20 reveal">
            <p class="font-orbitron text-neon-blue/70 text-xs tracking-[0.3em] uppercase mb-3">My Work</p>
            <h2 class="font-orbitron text-4xl md:text-5xl font-bold">
                <span class="text-white">Featured </span>
                <span class="gradient-text">Projects</span>
            </h2>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($projectItems)): ?>
                <div class="col-span-full text-center py-16">
                    <p class="text-gray-500">Belum ada project yang ditambahkan.</p>
                </div>
            <?php else: ?>
                <?php foreach ($projectItems as $index => $project): ?>
                    <?php
                    $projectTitle       = $project['title'] ?? '';
                    $projectDesc        = $project['description'] ?? ($project['desc'] ?? '');
                    $projectGradient    = $project['gradient'] ?? 'from-blue-500/20 to-cyan-500/20';
                    $projectIcon        = $project['icon_path'] ?? '';
                    $projectDemo        = $project['demo_url'] ?? '';
                    $projectSource      = $project['source_url'] ?? '';
                    $projectTags        = isset($project['tags']) && is_string($project['tags']) ? (json_decode($project['tags'], true) ?: []) : ($project['tags'] ?? []);
                    ?>
                    <div class="project-card glass-card rounded-2xl overflow-hidden reveal" style="transition-delay: <?= $index * 0.1 ?>s;">
                        <div class="h-48 bg-gradient-to-br <?= htmlspecialchars($projectGradient) ?> relative flex items-center justify-center">
                            <?php if (preg_match('/\.(png|jpg|jpeg|gif|webp)$/i', $projectIcon)): ?>
                                <img src="<?= htmlspecialchars($projectIcon) ?>" alt="<?= htmlspecialchars($projectTitle) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <svg class="w-16 h-16 text-neon-blue/40" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path d="<?= htmlspecialchars($projectIcon) ?>"/>
                                </svg>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-nightwing-950/80 to-transparent"></div>
                        </div>
                        <div class="p-6 relative z-10">
                            <h3 class="font-orbitron text-lg font-bold text-white mb-2"><?= htmlspecialchars($projectTitle) ?></h3>
                            <p class="text-gray-500 text-sm leading-relaxed mb-4"><?= htmlspecialchars($projectDesc) ?></p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <?php foreach ($projectTags as $tag): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-neon-blue/10 text-neon-blue/80 border border-neon-blue/10"><?= htmlspecialchars($tag) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="flex items-center gap-4">
                                <?php if (!empty($projectDemo)): ?>
                                <a href="<?= htmlspecialchars($projectDemo) ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-neon-blue transition-colors text-sm font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.06a4.5 4.5 0 00-6.364-6.364L4.5 8.25l4.5 4.5"/>
                                    </svg>
                                    Live Demo
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($projectSource)): ?>
                                <a href="<?= htmlspecialchars($projectSource) ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-neon-blue transition-colors text-sm font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/>
                                    </svg>
                                    Source
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>