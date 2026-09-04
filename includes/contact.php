<section id="contact" class="relative py-32 overflow-hidden">
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-neon-blue/20 to-transparent"></div>
    <div class="absolute top-1/3 left-1/3 w-96 h-96 bg-neon-blue/5 rounded-full blur-[150px]"></div>

    <div class="max-w-4xl mx-auto px-6">
        <div class="text-center mb-20 reveal">
            <p class="font-orbitron text-neon-blue/70 text-xs tracking-[0.3em] uppercase mb-3"><?= CONTACT['tag'] ?></p>
            <h2 class="font-orbitron text-4xl md:text-5xl font-bold">
                <span class="text-white">Contact </span>
                <span class="gradient-text">Me</span>
            </h2>
            <p class="text-gray-500 mt-4 max-w-lg mx-auto"><?= CONTACT['subtitle'] ?></p>
        </div>

        <div class="grid md:grid-cols-5 gap-12">
            <div class="md:col-span-2 space-y-6 reveal">
                <div class="glass-card rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-neon-blue/10 border border-neon-blue/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-neon-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider">Email</p>
                            <p class="text-white text-sm font-medium"><?= SITE['email'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="glass-card rounded-2xl p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-neon-blue/10 border border-neon-blue/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-neon-blue" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider">Location</p>
                            <p class="text-white text-sm font-medium"><?= SITE['location'] ?></p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <?php foreach (CONTACT['socials'] as $social): ?>
                        <a href="<?= $social['url'] ?>" class="w-11 h-11 rounded-xl border border-gray-800 flex items-center justify-center text-gray-500 hover:text-neon-blue hover:border-neon-blue/30 hover:bg-neon-blue/5 transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="<?= $social['path'] ?>"/>
                            </svg>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="md:col-span-3 reveal">
                <form class="glass-card rounded-2xl p-8 space-y-6" action="includes/send.php" method="POST">

                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-orbitron text-xs text-gray-500 uppercase tracking-wider mb-2">
                                <?= CONTACT['form']['name']['label'] ?>
                            </label>
                            <input type="<?= CONTACT['form']['name']['type'] ?>"
                                   name="name" required
                                   placeholder="<?= CONTACT['form']['name']['placeholder'] ?>"
                                   class="input-field w-full px-4 py-3 rounded-xl text-white text-sm placeholder:text-gray-600 font-inter">
                        </div>
                        <div>
                            <label class="block font-orbitron text-xs text-gray-500 uppercase tracking-wider mb-2">
                                <?= CONTACT['form']['email']['label'] ?>
                            </label>
                            <input type="<?= CONTACT['form']['email']['type'] ?>"
                                   name="email" required
                                   placeholder="<?= CONTACT['form']['email']['placeholder'] ?>"
                                   class="input-field w-full px-4 py-3 rounded-xl text-white text-sm placeholder:text-gray-600 font-inter">
                        </div>
                    </div>
                    <div>
                        <label class="block font-orbitron text-xs text-gray-500 uppercase tracking-wider mb-2">
                            <?= CONTACT['form']['subject']['label'] ?>
                        </label>
                        <input type="<?= CONTACT['form']['subject']['type'] ?>"
                               name="subject" required
                               placeholder="<?= CONTACT['form']['subject']['placeholder'] ?>"
                               class="input-field w-full px-4 py-3 rounded-xl text-white text-sm placeholder:text-gray-600 font-inter">
                    </div>
                    <div>
                        <label class="block font-orbitron text-xs text-gray-500 uppercase tracking-wider mb-2">
                            <?= CONTACT['form']['message']['label'] ?>
                        </label>
                        <textarea rows="5"
                                  name="message" required
                                  placeholder="<?= CONTACT['form']['message']['placeholder'] ?>"
                                  class="input-field w-full px-4 py-3 rounded-xl text-white text-sm placeholder:text-gray-600 font-inter resize-none"></textarea>
                    </div>
                    <button type="submit" class="neon-btn w-full py-4 rounded-xl font-orbitron text-sm font-bold tracking-widest uppercase text-neon-blue">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>