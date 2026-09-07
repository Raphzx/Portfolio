<?php
require_once __DIR__ . '/../config/auth.php';

require_auth();

$editId  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$project = null;

if ($editId > 0) {
    $stmt = db()->prepare("SELECT * FROM projects WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $editId]);
    $project = $stmt->fetch();
    if (!$project) {
        header('Location: projects');
        exit;
    }
}

function svg_placeholder(): string
{
    return 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
}

function get_old_icon(int $id): string
{
    $stmt = db()->prepare("SELECT icon_path FROM projects WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return (string)$stmt->fetchColumn();
}

function upload_project_image(array $file): string
{
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return '';
    if ((int)$file['size'] > 2 * 1024 * 1024) return '';

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) return '';

    $dir = __DIR__ . '/../assets/img/projects';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $filename = uniqid('proj_', true) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $filename)) return '';

    return 'assets/img/projects/' . $filename;
}

$gradients = [
    'from-blue-500/20 to-cyan-500/20',
    'from-purple-500/20 to-blue-500/20',
    'from-cyan-500/20 to-teal-500/20',
    'from-emerald-500/20 to-blue-500/20',
    'from-pink-500/20 to-blue-500/20',
    'from-amber-500/20 to-orange-500/20',
    'from-indigo-500/20 to-purple-500/20',
    'from-rose-500/20 to-red-500/20',
    'from-green-500/20 to-emerald-500/20',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $id          = (int)($_POST['id'] ?? 0);
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $rawTags     = trim($_POST['tags'] ?? '');
    $gradient    = trim($_POST['gradient'] ?? '');
    $preIconType = $_POST['icon_type'] ?? 'image';
    $svgPath     = trim($_POST['icon_svg'] ?? '');
    $demoUrl     = trim($_POST['demo_url'] ?? '');
    $sourceUrl   = trim($_POST['source_url'] ?? '');
    $sortOrder   = max(0, (int)($_POST['sort_order'] ?? 0));
    $errorUrl    = $id > 0 ? "project_form?id=$id&error=" : 'project_form?error=';

    if ($title === '' || $description === '') {
        header('Location: ' . $errorUrl . urlencode('Judul dan deskripsi wajib diisi.'));
        exit;
    }

    $tagArr  = array_values(array_filter(array_map('trim', explode(',', $rawTags)), 'strlen'));
    $tagJson = json_encode($tagArr);

    if (!in_array($gradient, $gradients, true)) {
        $gradient = 'from-blue-500/20 to-cyan-500/20';
    }

    if ($preIconType === 'image') {
        $newImage = '';
        if (!empty($_FILES['icon_image']['name'])) {
            $newImage = upload_project_image($_FILES['icon_image']);
            if ($newImage === '') {
                header('Location: ' . $errorUrl . urlencode('Gagal mengupload gambar. Format harus jpg/png/gif/webp dan maks 2MB.'));
                exit;
            }
        }
        $iconPath = $newImage !== '' ? $newImage : ($id > 0 ? get_old_icon($id) : svg_placeholder());
    } else {
        $iconPath = $svgPath !== '' ? trim(strip_tags($svgPath)) : svg_placeholder();
    }

    if ($id > 0) {
        $stmt = db()->prepare("UPDATE projects SET title=:title, description=:description, tags=:tags, gradient=:gradient, icon_path=:icon_path, demo_url=:demo_url, source_url=:source_url, sort_order=:sort_order WHERE id=:id");
        $stmt->execute([
            'title' => $title, 'description' => $description, 'tags' => $tagJson,
            'gradient' => $gradient, 'icon_path' => $iconPath, 'demo_url' => $demoUrl,
            'source_url' => $sourceUrl, 'sort_order' => $sortOrder, 'id' => $id,
        ]);
        header('Location: projects?updated=1');
    } else {
        $stmt = db()->prepare("INSERT INTO projects (title, description, tags, gradient, icon_path, demo_url, source_url, sort_order) VALUES (:title, :description, :tags, :gradient, :icon_path, :demo_url, :source_url, :sort_order)");
        $stmt->execute([
            'title' => $title, 'description' => $description, 'tags' => $tagJson,
            'gradient' => $gradient, 'icon_path' => $iconPath, 'demo_url' => $demoUrl,
            'source_url' => $sourceUrl, 'sort_order' => $sortOrder,
        ]);
        header('Location: projects?added=1');
    }
    exit;
}

$tags     = $project ? (json_decode($project['tags'], true) ?: []) : [];
$gradient = $project['gradient'] ?? 'from-blue-500/20 to-cyan-500/20';
$iconPath = $project['icon_path'] ?? '';
$isImage  = preg_match('/\.(png|jpg|jpeg|gif|webp)$/i', $iconPath) || ($iconPath === '' && $editId === 0);

require_once __DIR__ . '/header.php';
?>

<div class="mb-8">
    <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2"><?= $editId ? 'Edit Project' : 'Tambah Project' ?></h1>
    <p class="text-gray-500 text-sm"><?= $editId ? 'Perbarui detail proyek di bawah ini.' : 'Isi detail proyek baru untuk ditampilkan di portfolio.' ?></p>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] !== ''): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<div class="glass-card rounded-2xl p-6 md:p-8 max-w-3xl">
    <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="id" value="<?= $editId ?>">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Judul Project *</label>
                <input type="text" name="title" required maxlength="255"
                       value="<?= htmlspecialchars($project['title'] ?? '') ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                       placeholder="Contoh: PointTrackID">
            </div>
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Sort Order</label>
                <input type="number" name="sort_order" min="0"
                       value="<?= (int)($project['sort_order'] ?? 0) ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                       placeholder="0">
                <p class="text-gray-700 text-xs mt-1">Angka kecil tampil lebih dulu.</p>
            </div>
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Deskripsi *</label>
            <textarea name="description" required rows="4"
                      class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600 resize-none"
                      placeholder="Jelaskan project Anda..."><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Tags</label>
            <input type="text" name="tags" maxlength="500"
                   value="<?= htmlspecialchars(implode(', ', $tags)) ?>"
                   class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                   placeholder="Pisahkan dengan koma. Contoh: HTML5, CSS3, MySQL">
            <p class="text-gray-700 text-xs mt-1">Pisahkan antar tag dengan koma (,).</p>
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Gradient Warna</label>
            <select name="gradient" class="input-field-admin w-full rounded-lg px-4 py-3 text-white">
                <?php foreach ($gradients as $g): ?>
                    <option value="<?= htmlspecialchars($g) ?>" <?= $gradient === $g ? 'selected' : '' ?>><?= $g ?></option>
                <?php endforeach; ?>
            </select>
            <div class="flex flex-wrap gap-2 mt-2">
                <?php foreach ($gradients as $g): ?>
                    <div class="gradient-swatch w-8 h-8 rounded-lg bg-gradient-to-br <?= htmlspecialchars($g) ?> border <?= $gradient === $g ? 'border-neon-blue ring-2 ring-neon-blue/30' : 'border-neon-blue/10' ?> cursor-pointer" data-g="<?= htmlspecialchars($g) ?>" onclick="selectGradient('<?= htmlspecialchars($g) ?>')"></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-3">Icon / Gambar</label>
            <div class="flex gap-6 mb-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="icon_type" value="image" <?= $isImage ? 'checked' : '' ?> class="text-neon-blue">
                    <span class="text-gray-300 text-sm">Upload Gambar</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="icon_type" value="svg" <?= !$isImage ? 'checked' : '' ?> class="text-neon-blue">
                    <span class="text-gray-300 text-sm">SVG Path</span>
                </label>
            </div>

            <div id="imageUploadWrap" class="space-y-2">
                <label class="flex flex-col items-center justify-center w-full h-40 rounded-xl border-2 border-dashed border-neon-blue/20 hover:border-neon-blue/40 transition-colors cursor-pointer bg-nightwing-900/40">
                    <input type="file" name="icon_image" id="iconImageInput" accept=".jpg,.jpeg,.png,.gif,.webp" class="hidden">
                    <svg class="w-8 h-8 text-neon-blue/50 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    <span class="text-gray-500 text-sm">Klik untuk upload gambar (jpg, png, gif, webp - maks 2MB)</span>
                </label>
                <p id="fileName" class="text-neon-blue text-xs font-medium hidden"></p>
                <?php if ($editId > 0 && $isImage && $iconPath !== '' && preg_match('/\.(png|jpg|jpeg|gif|webp)$/i', $iconPath)): ?>
                    <div class="flex items-center gap-3">
                        <img src="../<?= htmlspecialchars($iconPath) ?>" alt="Current" class="w-16 h-16 rounded-lg object-cover border border-neon-blue/10">
                        <span class="text-gray-600 text-xs">Gambar saat ini: <?= htmlspecialchars($iconPath) ?>. Upload untuk mengganti.</span>
                    </div>
                <?php endif; ?>
            </div>

            <div id="svgWrap" class="space-y-2 <?= $isImage ? 'hidden' : '' ?>">
                <label class="block text-gray-400 text-sm font-medium mb-2">SVG Path (viewBox 0 0 24 24)</label>
                <textarea name="icon_svg" id="svgInputText" rows="3"
                          class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600 resize-none font-mono text-xs"
                          placeholder="Paste SVG path di sini..."><?= !$isImage ? htmlspecialchars($iconPath) : '' ?></textarea>
                <div class="flex items-center gap-4">
                    <svg class="w-10 h-10 text-neon-blue/60 shrink-0" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path id="svgPreviewPath" d="<?= !$isImage ? htmlspecialchars($iconPath) : '' ?>"/></svg>
                    <span class="text-gray-600 text-xs">Pratinjau ikon.</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Demo URL</label>
                <input type="url" name="demo_url" maxlength="500"
                       value="<?= htmlspecialchars($project['demo_url'] ?? '') ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                       placeholder="https://contoh.com/">
            </div>
            <div>
                <label class="block text-gray-400 text-sm font-medium mb-2">Source URL</label>
                <input type="url" name="source_url" maxlength="500"
                       value="<?= htmlspecialchars($project['source_url'] ?? '') ?>"
                       class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                       placeholder="https://github.com/...">
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-neon-blue/10">
            <button type="submit" class="neon-btn flex-1 px-6 py-3 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
                <?= $editId ? 'Simpan Perubahan' : 'Tambah Project' ?>
            </button>
            <a href="projects" class="flex-1 px-6 py-3 rounded-lg border border-neon-blue/10 text-gray-400 hover:text-neon-blue transition-colors text-center font-orbitron text-sm font-semibold tracking-widest uppercase">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
(function () {
    var imageRadio = document.querySelector('input[name="icon_type"][value="image"]');
    var svgRadio   = document.querySelector('input[name="icon_type"][value="svg"]');
    var imageWrap  = document.getElementById('imageUploadWrap');
    var svgWrap    = document.getElementById('svgWrap');
    var fileInput  = document.getElementById('iconImageInput');
    var svgInput   = document.getElementById('svgInputText');
    var preview    = document.getElementById('svgPreviewPath');
    var fileName   = document.getElementById('fileName');

    function sync() {
        var isImage = imageRadio.checked;
        imageWrap.classList.toggle('hidden', !isImage);
        svgWrap.classList.toggle('hidden', isImage);
        fileInput.disabled = !isImage;
        svgInput.disabled  = isImage;
        if (!isImage) preview.setAttribute('d', svgInput.value || '');
    }

    imageRadio.addEventListener('change', sync);
    svgRadio.addEventListener('change', sync);
    svgInput.addEventListener('input', function () {
        preview.setAttribute('d', svgInput.value || '');
    });
    fileInput.addEventListener('change', function () {
        if (fileInput.files.length > 0) {
            fileName.textContent = 'Dipilih: ' + fileInput.files[0].name;
            fileName.classList.remove('hidden');
        }
    });
    sync();
})();

function selectGradient(g) {
    var sel = document.querySelector('select[name="gradient"]');
    sel.value = g;
    sel.dispatchEvent(new Event('change'));
}

(function () {
    var gradPicker = document.querySelector('select[name="gradient"]');
    gradPicker.addEventListener('change', function () {
        var value = gradPicker.value;
        document.querySelectorAll('.gradient-swatch').forEach(function (sw) {
            var swatch = sw.getAttribute('data-g');
            sw.classList.toggle('border-neon-blue', swatch === value);
            sw.classList.toggle('ring-2', swatch === value);
            sw.classList.toggle('ring-neon-blue/30', swatch === value);
            sw.classList.toggle('border-neon-blue/10', swatch !== value);
        });
    });
})();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>