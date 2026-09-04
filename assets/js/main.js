/**
 * Nightwing Portfolio - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {

    // =============================
    // Hero Entrance Animations
    // =============================
    document.querySelectorAll('#hero .animate-slide-up, #hero .animate-fade-in').forEach(el => {
        el.style.opacity = '0';
        setTimeout(() => { el.style.opacity = '1'; }, 100);
    });


    // =============================
    // Navbar Scroll Effect
    // =============================
    const navbar = document.getElementById('navbar');

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 50) {
            navbar.classList.add(
                'bg-nightwing-950/80', 'backdrop-blur-xl',
                'border-b', 'border-neon-blue/10',
                'shadow-lg', 'shadow-black/20'
            );
        } else {
            navbar.classList.remove(
                'bg-nightwing-950/80', 'backdrop-blur-xl',
                'border-b', 'border-neon-blue/10',
                'shadow-lg', 'shadow-black/20'
            );
        }
    });


    // =============================
    // Mobile Menu Toggle
    // =============================
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu    = document.getElementById('mobileMenu');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    document.querySelectorAll('.mobile-link').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    });


    // =============================
    // Scroll Reveal (IntersectionObserver)
    // =============================
    const revealElements = document.querySelectorAll('.reveal');

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    revealElements.forEach(el => revealObserver.observe(el));


    // =============================
    // Skill Bar Animation
    // =============================
    const skillBars = document.querySelectorAll('.skill-bar-fill');

    const skillObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const width = entry.target.getAttribute('data-width');
                entry.target.style.width = width;
                skillObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    skillBars.forEach(bar => skillObserver.observe(bar));


    // =============================
    // 3D Magnetic Tilt on Cards
    // =============================
    document.querySelectorAll('.project-card, .glass-card').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect    = card.getBoundingClientRect();
            const x       = e.clientX - rect.left;
            const y       = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;

            card.style.transform =
                `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px) scale(1.02)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform =
                'perspective(1000px) rotateX(0) rotateY(0) translateY(0) scale(1)';
        });
    });


    // =============================
    // Smooth Scroll for Anchor Links
    // =============================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });


    // =============================
    // Contact Form Handler
    // =============================
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const btn          = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<span class="animate-pulse">Sending...</span>';
            btn.disabled  = true;

            setTimeout(() => {
                btn.innerHTML = '\u2713 Message Sent!';
                btn.classList.add('bg-neon-blue/20', 'border', 'border-neon-blue/30');

                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled  = false;
                    btn.classList.remove('bg-neon-blue/20', 'border', 'border-neon-blue/30');
                    contactForm.reset();
                }, 2000);
            }, 1500);
        });
    }

});
