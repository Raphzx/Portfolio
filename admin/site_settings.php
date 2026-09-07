<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/data_db.php';

require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $data = [
        'title'       => trim($_POST['title'] ?? ''),
        'tagline'     => trim($_POST['tagline'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'author'      => trim($_POST['author'] ?? ''),
        'email'       => trim($_POST['email'] ?? ''),
        'location'    => trim($_POST['location'] ?? ''),
    ];

    $stmt = db()->prepare("UPDATE site_content SET content = :content WHERE section = 'site'");
    $stmt->execute(['content' => json_encode($data)]);
    header('Location: site_settings?saved=1');
    exit;
}

$site = get_section_content('site', [
    'title' => '', 'tagline' => '', 'description' => '', 'author' => '', 'email' => '', 'location' => '',
]);

require_once __DIR__ . '/header.php';
?>

<div class="mb-8">
    <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2">Pengaturan Situs</h1>
    <p class="text-gray-500 text-sm">Kelola informasi umum portfolio Anda.</p>
</div>

<?php if (isset($_GET['saved'])): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">Pengaturan berhasil disimpan.</div>
<?php endif; ?>

<div class="glass-card rounded-2xl p-6 md:p-8 max-w-3xl">
    <form method="POST" action="" class="space-y-6">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Judul Situs *</label>
                <input type="text" name="title" required maxlength="255"
                       value="<?= htmlspecialchars($site['title']) ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                       placeholder="Nama Portfolio">
            </div>
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Tagline</label>
                <input type="text" name="tagline" maxlength="255"
                       value="<?= htmlspecialchars($site['tagline']) ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                       placeholder="Junior Software Developer">
            </div>
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Deskripsi</label>
            <textarea name="description" rows="3"
                      class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600 resize-none"
                      placeholder="Deskripsi singkat untuk SEO"><?= htmlspecialchars($site['description']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Penulis</label>
                <input type="text" name="author" maxlength="255"
                       value="<?= htmlspecialchars($site['author']) ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600">
            </div>
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Email</label>
                <input type="email" name="email" maxlength="255"
                       value="<?= htmlspecialchars($site['email']) ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600">
            </div>
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Lokasi</label>
                <input type="text" name="location" maxlength="255"
                       value="<?= htmlspecialchars($site['location']) ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600">
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-neon-blue/10">
            <button type="submit" class="neon-btn flex-1 px-6 py-3 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
                Simpan Pengaturan
            </button>
            <a href="./" class="flex-1 px-6 py-3 rounded-lg border border-neon-blue/10 text-gray-400 hover:text-neon-blue transition-colors text-center font-orbitron text-sm font-semibold tracking-widest uppercase">
                Kembali
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
