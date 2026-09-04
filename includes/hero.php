<section id="hero" class="relative min-h-screen flex items-center justify-center hero-grid overflow-hidden">
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-neon-blue/5 rounded-full blur-[120px] animate-float"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-neon-cyan/5 rounded-full blur-[100px] animate-float-delay"></div>
    <div class="relative z-10 text-center max-w-5xl mx-auto px-6">
        <div class="mb-8 animate-fade-in opacity-0">
            <svg class="w-32 h-32 mx-auto owl-symbol" viewBox="0 0 120 120" fill="none">
                <defs>
                    <linearGradient id="wingGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#38bdf8;stop-opacity:1" />
                        <stop offset="50%" style="stop-color:#22d3ee;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#0ea5e9;stop-opacity:1" />
                    </linearGradient>
                    <filter id="glow">
                        <feGaussianBlur stdDeviation="3" result="blur"/>
                        <feMerge>
                            <feMergeNode in="blur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
                <path d="M60 15 L15 50 L25 55 L20 75 L35 65 L45 85 L50 70 L60 90 L70 70 L75 85 L85 65 L100 75 L95 55 L105 50 Z"
                      fill="url(#wingGrad)" filter="url(#glow)" opacity="0.9"/>
                <ellipse cx="42" cy="45" rx="6" ry="5" fill="#020617"/>
                <ellipse cx="78" cy="45" rx="6" ry="5" fill="#020617"/>
                <ellipse cx="43" cy="44" rx="2.5" ry="2" fill="#38bdf8" opacity="0.9"/>
                <ellipse cx="79" cy="44" rx="2.5" ry="2" fill="#38bdf8" opacity="0.9"/>
            </svg>
        </div>

        <p class="font-orbitron text-neon-blue/70 text-sm tracking-[0.3em] uppercase mb-4 animate-slide-up opacity-0" style="animation-delay: 0.1s;">
            <?= HERO['greeting'] ?>
        </p>

        <h1 class="font-orbitron text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black mb-6 leading-tight animate-slide-up opacity-0" style="animation-delay: 0.2s;">
            <span class="block text-white"><?= HERO['title_1'] ?></span>
            <span class="gradient-text animate-glow-pulse"><?= HERO['title_2'] ?></span>
            <span class="block text-white"><?= HERO['title_3'] ?></span>
        </h1>

        <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed animate-slide-up opacity-0" style="animation-delay: 0.4s;">
            <?= HERO['subtitle'] ?>
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up opacity-0" style="animation-delay: 0.6s;">
            <a href="<?= HERO['cta_1']['href'] ?>" class="neon-btn px-8 py-4 rounded-full font-orbitron text-sm font-bold tracking-widest uppercase text-neon-blue">
                <?= HERO['cta_1']['text'] ?>
            </a>
            <a href="<?= HERO['cta_2']['href'] ?>" class="px-8 py-4 rounded-full border border-gray-700 text-gray-400 hover:text-white hover:border-gray-500 font-orbitron text-sm font-bold tracking-widest uppercase transition-all duration-300 hover:bg-white/5">
                <?= HERO['cta_2']['text'] ?>
            </a>
        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-gray-700 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-neon-blue rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </div>
</section>
