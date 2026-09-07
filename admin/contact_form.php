<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/data_db.php';

require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $data = [
        'tag'      => trim($_POST['tag'] ?? ''),
        'heading'  => trim($_POST['heading'] ?? ''),
        'subtitle' => trim($_POST['subtitle'] ?? ''),
    ];

    $stmt = db()->prepare("UPDATE site_content SET content = :content WHERE section = 'contact'");
    $stmt->execute(['content' => json_encode($data)]);

    db()->exec("DELETE FROM contact_socials");
    $platForms = $_POST['social_platform'] ?? [];
    $urls      = $_POST['social_url'] ?? [];
    $paths     = $_POST['social_path'] ?? [];
    $stmtSocial = db()->prepare("INSERT INTO contact_socials (platform, url, path, sort_order) VALUES (:p, :u, :pt, :o)");
    foreach ($platForms as $i => $p) {
        $platform = trim($p ?? '');
        $url      = trim($urls[$i] ?? '');
        if ($platform !== '' && $url !== '') {
            $stmtSocial->execute([
                'p'  => $platform,
                'u'  => $url,
                'pt' => trim($paths[$i] ?? ''),
                'o'  => $i,
            ]);
        }
    }

    header('Location: contact_form?saved=1');
    exit;
}

$contact = get_section_content('contact', [
    'tag' => '', 'heading' => '', 'subtitle' => '',
]);
$socials = db()->query("SELECT * FROM contact_socials ORDER BY sort_order ASC, id ASC")->fetchAll();

require_once __DIR__ . '/header.php';
?>

<div class="mb-8">
    <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2">Sektion Contact</h1>
    <p class="text-gray-500 text-sm">Kelola teks dan ikon media sosial di bagian kontak.</p>
</div>

<?php if (isset($_GET['saved'])): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">Sektion Contact berhasil disimpan.</div>
<?php endif; ?>

<div class="glass-card rounded-2xl p-6 md:p-8 max-w-4xl">
    <form method="POST" action="" class="space-y-8">
        <?= csrf_field() ?>

        <div>
            <h2 class="font-orbitron text-lg font-bold text-white mb-4">Teks Utama</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-400 text-sm font-medium mb-2">Tag</label>
                    <input type="text" name="tag" maxlength="100"
                           value="<?= htmlspecialchars($contact['tag']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="Get In Touch">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm font-medium mb-2">Heading</label>
                    <input type="text" name="heading" maxlength="100"
                           value="<?= htmlspecialchars($contact['heading']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="Contact Me">
                </div>
            </div>
            <div class="mt-6">
                <label class="block text-gray-400 text-sm font-medium mb-2">Subtitle</label>
                <textarea name="subtitle" rows="2"
                          class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600 resize-none"><?= htmlspecialchars($contact['subtitle']) ?></textarea>
            </div>
        </div>

        <div class="border-t border-neon-blue/10 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-orbitron text-lg font-bold text-white">Media Sosial</h2>
                <button type="button" onclick="addSocial()" class="text-neon-blue hover:text-cyan-400 text-sm transition-colors">+ Tambah Sosial</button>
            </div>
            <div id="socialsWrap" class="space-y-4">
                <?php foreach ($socials as $i => $s): ?>
                    <div class="glass-card rounded-xl p-4 social-row">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <p class="text-gray-500 text-xs uppercase tracking-wider">Sosial <?= $i + 1 ?></p>
                            <button type="button" onclick="this.closest('.social-row').remove()" class="text-red-400 hover:text-red-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <input type="text" name="social_platform[]" value="<?= htmlspecialchars($s['platform']) ?>"
                                   class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600" placeholder="Platform (GitHub)">
                            <input type="url" name="social_url[]" value="<?= htmlspecialchars($s['url']) ?>"
                                   class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600" placeholder="https://...">
                        </div>
                        <div class="mt-3">
                            <input type="text" name="social_path[]" value="<?= htmlspecialchars($s['path']) ?>"
                                   class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600 font-mono text-xs" placeholder="SVG path icon">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-neon-blue/10">
            <button type="submit" class="neon-btn flex-1 px-6 py-3 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
                Simpan Perubahan
            </button>
            <a href="./" class="flex-1 px-6 py-3 rounded-lg border border-neon-blue/10 text-gray-400 hover:text-neon-blue transition-colors text-center font-orbitron text-sm font-semibold tracking-widest uppercase">
                Kembali
            </a>
        </div>
    </form>
</div>

<script>
function addSocial() {
    const wrap = document.getElementById('socialsWrap');
    const div = document.createElement('div');
    div.className = 'glass-card rounded-xl p-4 social-row';
    div.innerHTML =
        '<div class="flex items-start justify-between gap-3 mb-2">' +
        '<p class="text-gray-500 text-xs uppercase tracking-wider">Sosial Baru</p>' +
        '<button type="button" onclick="this.closest(\'.social-row\').remove()" class="text-red-400 hover:text-red-300 transition-colors">' +
        '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></div>' +
        '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">' +
        '<input type="text" name="social_platform[]" class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600" placeholder="Platform (GitHub)">' +
        '<input type="url" name="social_url[]" class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600" placeholder="https://...">' +
        '</div>' +
        '<div class="mt-3">' +
        '<input type="text" name="social_path[]" class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600 font-mono text-xs" placeholder="SVG path icon">' +
        '</div>';
    wrap.appendChild(div);
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
