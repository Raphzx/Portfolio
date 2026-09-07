<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <a href="#hero" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full border border-neon-blue/30 flex items-center justify-center bg-nightwing-900/80 backdrop-blur-sm group-hover:border-neon-blue/60 group-hover:shadow-[0_0_20px_rgba(56,189,248,0.3)] transition-all duration-300">
                <img src="assets/img/Profile.jpg" alt="Profile" class="w-full h-full object-cover rounded-full" />
            </div>
            <span class="font-orbitron text-lg font-bold tracking-wider gradient-text"><?= htmlspecialchars(SITE['author']) ?></span>
        </a>

        <div class="flex items-center gap-8">
            <?php
            $navLinks = [
                ['label' => 'About',   'href' => '#about'],
                ['label' => 'Skills',  'href' => '#skills'],
                ['label' => 'Projects','href' => '#projects'],
                ['label' => 'Contact', 'href' => '#contact'],
            ];
            foreach ($navLinks as $link): ?>
                <a href="<?= $link['href'] ?>" class="nav-link text-sm text-gray-400 hover:text-neon-blue font-medium tracking-wide uppercase hidden md:block">
                    <?= $link['label'] ?>
                </a>
            <?php endforeach; ?>

            <a href="#contact" class="neon-btn px-5 py-2 rounded-full text-neon-blue font-orbitron text-xs font-semibold tracking-widest uppercase hidden sm:block">
                Hire Me
            </a>
        </div>

        <button id="mobileMenuBtn" class="md:hidden text-gray-400 hover:text-neon-blue transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <div id="mobileMenu" class="hidden md:hidden bg-nightwing-900/95 backdrop-blur-xl border-t border-neon-blue/10 px-6 py-4">
        <div class="flex flex-col gap-4">
            <?php foreach ($navLinks as $link): ?>
                <a href="<?= $link['href'] ?>" class="text-gray-400 hover:text-neon-blue text-sm font-medium tracking-wide uppercase py-2 mobile-link">
                    <?= $link['label'] ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</nav>
