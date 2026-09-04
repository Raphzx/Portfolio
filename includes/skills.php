<section id="skills" class="relative py-32 overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-neon-blue/20 to-transparent"></div>
    <div class="absolute top-1/2 right-0 w-96 h-96 bg-neon-blue/5 rounded-full blur-[150px]"></div>

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-20 reveal">
            <p class="font-orbitron text-neon-blue/70 text-xs tracking-[0.3em] uppercase mb-3"><?= SKILLS['tag'] ?></p>
            <h2 class="font-orbitron text-4xl md:text-5xl font-bold">
                <span class="text-white">Technical </span>
                <span class="gradient-text">Skills</span>
            </h2>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach (SKILLS['items'] as $index => $skill): ?>
                <div class="glass-card rounded-2xl p-6 reveal" style="transition-delay: <?= $index * 0.08 ?>s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <img src="<?= $skill['icon'] ?>" alt="<?= $skill['name'] ?>" class="w-7 h-7 object-contain">

                            <span class="font-orbitron text-sm font-semibold text-white"><?= $skill['name'] ?></span>
                        </div>
                        <span class="font-orbitron text-sm text-neon-blue font-bold"><?= $skill['level'] ?>%</span>
                    </div>
                    <div class="skill-bar">
                        <div class="skill-bar-fill" style="width: 0%;" data-width="<?= $skill['level'] ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>