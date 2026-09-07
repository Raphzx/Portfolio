<?php
require_once __DIR__ . '/../config/auth.php';

require_auth();

$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$skill  = null;

if ($editId > 0) {
    $stmt = db()->prepare("SELECT * FROM skills WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $editId]);
    $skill = $stmt->fetch();
    if (!$skill) {
        header('Location: skills');
        exit;
    }
}

function upload_skill_image(array $file): string
{
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return '';
    if ((int)$file['size'] > 2 * 1024 * 1024) return '';

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) return '';

    $dir = __DIR__ . '/../assets/logo/skills';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $filename = uniqid('skill_', true) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $filename)) return '';

    return 'assets/logo/skills/' . $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $id    = (int)($_POST['id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
    $level = max(0, min(100, (int)($_POST['level'] ?? 0)));
    $icon  = trim($_POST['icon_path'] ?? '');
    $sortOrder = max(0, (int)($_POST['sort_order'] ?? 0));
    $errorUrl = $id > 0 ? "skill_form?id=$id&error=" : 'skill_form?error=';

    if ($name === '') {
        header('Location: ' . $errorUrl . urlencode('Nama skill wajib diisi.'));
        exit;
    }

    if (!empty($_FILES['icon_image']['name'])) {
        $uploaded = upload_skill_image($_FILES['icon_image']);
        if ($uploaded === '') {
            header('Location: ' . $errorUrl . urlencode('Gagal mengupload gambar. Format harus jpg/png/gif/webp/svg dan maks 2MB.'));
            exit;
        }
        $icon = $uploaded;
    } elseif ($icon === '') {
        $icon = $skill['icon'] ?? 'assets/logo/html-css3.png';
    }

    if ($id > 0) {
        $stmt = db()->prepare("UPDATE skills SET name=:name, level=:level, icon=:icon, sort_order=:sort_order WHERE id=:id");
        $stmt->execute(['name' => $name, 'level' => $level, 'icon' => $icon, 'sort_order' => $sortOrder, 'id' => $id]);
        header('Location: skills?updated=1');
    } else {
        $stmt = db()->prepare("INSERT INTO skills (name, level, icon, sort_order) VALUES (:name, :level, :icon, :sort_order)");
        $stmt->execute(['name' => $name, 'level' => $level, 'icon' => $icon, 'sort_order' => $sortOrder]);
        header('Location: skills?added=1');
    }
    exit;
}

require_once __DIR__ . '/header.php';
?>

<div class="mb-8">
    <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2"><?= $editId ? 'Edit Skill' : 'Tambah Skill' ?></h1>
    <p class="text-gray-500 text-sm"><?= $editId ? 'Perbarui detail skill di bawah ini.' : 'Isi detail skill baru.' ?></p>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] !== ''): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<div class="glass-card rounded-2xl p-6 md:p-8 max-w-2xl">
    <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="id" value="<?= $editId ?>">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Nama Skill *</label>
                <input type="text" name="name" required maxlength="255"
                       value="<?= htmlspecialchars($skill['name'] ?? '') ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                       placeholder="Contoh: JavaScript">
            </div>
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Level (0-100%)</label>
                <input type="number" name="level" min="0" max="100"
                       value="<?= (int)($skill['level'] ?? 0) ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600">
            </div>
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Ikon / Logo</label>
            <div class="flex items-center gap-4 mb-3">
                <?php if (!empty($skill['icon'])): ?>
                    <img src="../<?= htmlspecialchars($skill['icon']) ?>" alt="" class="w-12 h-12 object-contain border border-neon-blue/10 rounded-lg p-1 bg-nightwing-900/40">
                <?php endif; ?>
                <input type="text" name="icon_path" maxlength="500"
                       value="<?= htmlspecialchars($skill['icon'] ?? '') ?>"
                       class="input-field-admin flex-1 rounded-lg px-4 py-3 text-white placeholder-gray-600"
                       placeholder="assets/logo/html-css3.png">
            </div>
            <label class="flex flex-col items-center justify-center w-full h-32 rounded-xl border-2 border-dashed border-neon-blue/20 hover:border-neon-blue/40 transition-colors cursor-pointer bg-nightwing-900/40">
                <input type="file" name="icon_image" accept=".jpg,.jpeg,.png,.gif,.webp,.svg" class="hidden">
                <svg class="w-7 h-7 text-neon-blue/50 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                <span class="text-gray-500 text-sm">Upload gambar logo (jpg, png, svg - maks 2MB)</span>
            </label>
            <p class="text-gray-700 text-xs mt-2">Atau isi kolom di atas dengan path gambar/ikon yang sudah ada.</p>
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Sort Order</label>
            <input type="number" name="sort_order" min="0"
                   value="<?= (int)($skill['sort_order'] ?? 0) ?>"
                   class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600">
            <p class="text-gray-700 text-xs mt-1">Angka kecil tampil lebih dulu.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-neon-blue/10">
            <button type="submit" class="neon-btn flex-1 px-6 py-3 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
                <?= $editId ? 'Simpan Perubahan' : 'Tambah Skill' ?>
            </button>
            <a href="skills" class="flex-1 px-6 py-3 rounded-lg border border-neon-blue/10 text-gray-400 hover:text-neon-blue transition-colors text-center font-orbitron text-sm font-semibold tracking-widest uppercase">
                Batal
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
