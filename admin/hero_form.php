<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/data_db.php';

require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $data = [
        'greeting'   => trim($_POST['greeting'] ?? ''),
        'title_1'    => trim($_POST['title_1'] ?? ''),
        'title_2'    => trim($_POST['title_2'] ?? ''),
        'title_3'    => trim($_POST['title_3'] ?? ''),
        'subtitle'   => trim($_POST['subtitle'] ?? ''),
        'cta_1_text' => trim($_POST['cta_1_text'] ?? ''),
        'cta_1_href' => trim($_POST['cta_1_href'] ?? ''),
        'cta_2_text' => trim($_POST['cta_2_text'] ?? ''),
        'cta_2_href' => trim($_POST['cta_2_href'] ?? ''),
    ];

    $stmt = db()->prepare("UPDATE site_content SET content = :content WHERE section = 'hero'");
    $stmt->execute(['content' => json_encode($data)]);
    header('Location: hero_form?saved=1');
    exit;
}

$hero = get_section_content('hero', [
    'greeting' => '', 'title_1' => '', 'title_2' => '', 'title_3' => '',
    'subtitle' => '', 'cta_1_text' => '', 'cta_1_href' => '', 'cta_2_text' => '', 'cta_2_href' => '',
]);

require_once __DIR__ . '/header.php';
?>

<div class="mb-8">
    <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2">Sektion Hero</h1>
    <p class="text-gray-500 text-sm">Kelola teks yang tampil di bagian paling atas portfolio.</p>
</div>

<?php if (isset($_GET['saved'])): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">Sektion Hero berhasil disimpan.</div>
<?php endif; ?>

<div class="glass-card rounded-2xl p-6 md:p-8 max-w-3xl">
    <form method="POST" action="" class="space-y-6">
        <?= csrf_field() ?>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Greeting</label>
            <input type="text" name="greeting" maxlength="255"
                   value="<?= htmlspecialchars($hero['greeting']) ?>"
                   class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600 font-mono text-sm"
                   placeholder="< Junior Software Developer />">
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-3">Judul (3 baris)</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-600 text-xs mb-1">Baris 1</p>
                    <input type="text" name="title_1" maxlength="100"
                           value="<?= htmlspecialchars($hero['title_1']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600">
                </div>
                <div>
                    <p class="text-gray-600 text-xs mb-1">Baris 2 (gradient)</p>
                    <input type="text" name="title_2" maxlength="100"
                           value="<?= htmlspecialchars($hero['title_2']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600">
                </div>
                <div>
                    <p class="text-gray-600 text-xs mb-1">Baris 3</p>
                    <input type="text" name="title_3" maxlength="100"
                           value="<?= htmlspecialchars($hero['title_3']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Subtitle</label>
            <textarea name="subtitle" rows="2"
                      class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600 resize-none"
                      placeholder="Deskripsi singkat hero"><?= htmlspecialchars($hero['subtitle']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="glass-card rounded-xl p-4">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-3">Tombol 1</p>
                <div class="space-y-3">
                    <input type="text" name="cta_1_text" maxlength="100"
                           value="<?= htmlspecialchars($hero['cta_1_text']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="View Projects">
                    <input type="text" name="cta_1_href" maxlength="255"
                           value="<?= htmlspecialchars($hero['cta_1_href']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="#projects">
                </div>
            </div>
            <div class="glass-card rounded-xl p-4">
                <p class="text-gray-500 text-xs uppercase tracking-wider mb-3">Tombol 2</p>
                <div class="space-y-3">
                    <input type="text" name="cta_2_text" maxlength="100"
                           value="<?= htmlspecialchars($hero['cta_2_text']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="Contact Me">
                    <input type="text" name="cta_2_href" maxlength="255"
                           value="<?= htmlspecialchars($hero['cta_2_href']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="#contact">
                </div>
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

<?php require_once __DIR__ . '/footer.php'; ?>
