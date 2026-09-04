<section id="about" class="relative py-32 overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-neon-blue/20 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-20 reveal">
            <p class="font-orbitron text-neon-blue/70 text-xs tracking-[0.3em] uppercase mb-3"><?= ABOUT['tag'] ?></p>
            <h2 class="font-orbitron text-4xl md:text-5xl font-bold">
                <span class="text-white">About </span>
                <span class="gradient-text">Me</span>
            </h2>
        </div>

        <div class="grid md:grid-cols-2 gap-12 items-center">

            <div class="reveal">
                <div class="glass-card rounded-2xl p-8 relative">
                    <div class="absolute -top-3 -left-3 w-16 h-16 border border-neon-blue/20 rounded-xl flex items-center justify-center bg-nightwing-900/80">
                        <span class="font-orbitron text-neon-blue text-xl font-bold"><?= ABOUT['number'] ?></span>
                    </div>
                    <h3 class="font-orbitron text-xl font-bold text-white mb-4 mt-4"><?= ABOUT['title'] ?></h3>
                    <p class="text-gray-400 leading-relaxed mb-6"><?= ABOUT['paragraph_1'] ?></p>
                    <p class="text-gray-400 leading-relaxed"><?= ABOUT['paragraph_2'] ?></p>

                    <div class="grid grid-cols-3 gap-4 mt-8">
                        <?php foreach (ABOUT['stats'] as $stat): ?>
                            <div class="text-center">
                                <span class="block font-orbitron text-3xl font-bold text-neon-blue"><?= $stat['value'] ?></span>
                                <span class="text-gray-500 text-xs uppercase tracking-wider"><?= $stat['label'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="space-y-6 reveal">
                <?php foreach (ABOUT['features'] as $feature): ?>
                    <div class="glass-card rounded-2xl p-6 flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-neon-blue/10 border border-neon-blue/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-neon-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="<?= $feature['icon_code'] ?>"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-orbitron text-sm font-bold text-white mb-1"><?= $feature['title'] ?></h4>
                            <p class="text-gray-500 text-sm leading-relaxed"><?= $feature['desc'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
