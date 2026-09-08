<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/data_db.php';

require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $data = [
        'tag'         => trim($_POST['tag'] ?? ''),
        'heading'     => trim($_POST['heading'] ?? ''),
        'number'      => trim($_POST['number'] ?? ''),
        'title'       => trim($_POST['title'] ?? ''),
        'paragraph_1' => trim($_POST['paragraph_1'] ?? ''),
        'paragraph_2' => trim($_POST['paragraph_2'] ?? ''),
    ];

    $stmt = db()->prepare("UPDATE site_content SET content = :content WHERE section = 'about'");
    $stmt->execute(['content' => json_encode($data)]);

    db()->exec("DELETE FROM about_stats");
    $statValues = $_POST['stat_value'] ?? [];
    $statLabels = $_POST['stat_label'] ?? [];
    $stmtStat = db()->prepare("INSERT INTO about_stats (value, label, sort_order) VALUES (:v, :l, :o)");
    foreach ($statValues as $i => $v) {
        $val = trim($v ?? '');
        $lab = trim($statLabels[$i] ?? '');
        if ($val !== '' && $lab !== '') {
            $stmtStat->execute(['v' => $val, 'l' => $lab, 'o' => $i]);
        }
    }

    db()->exec("DELETE FROM about_features");
    $featIcons = $_POST['feat_icon'] ?? [];
    $featTitles = $_POST['feat_title'] ?? [];
    $featDescs = $_POST['feat_desc'] ?? [];
    $stmtFeat = db()->prepare("INSERT INTO about_features (icon_code, title, description, sort_order) VALUES (:i, :t, :d, :o)");
    foreach ($featTitles as $i => $t) {
        $title = trim($t ?? '');
        if ($title !== '') {
            $stmtFeat->execute([
                'i' => trim($featIcons[$i] ?? ''),
                't' => $title,
                'd' => trim($featDescs[$i] ?? ''),
                'o' => $i,
            ]);
        }
    }

    header('Location: about_form?saved=1');
    exit;
}

$about = get_section_content('about', [
    'tag' => '', 'heading' => '', 'number' => '', 'title' => '', 'paragraph_1' => '', 'paragraph_2' => '',
]);
$stats = db()->query("SELECT * FROM about_stats ORDER BY sort_order ASC, id ASC")->fetchAll();
$features = db()->query("SELECT * FROM about_features ORDER BY sort_order ASC, id ASC")->fetchAll();

require_once __DIR__ . '/header.php';
?>

<div class="mb-8">
    <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2">About Section</h1>
    <p class="text-gray-500 text-sm">Manage text, stats, and features in your about section.</p>
</div>

<?php if (isset($_GET['saved'])): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">About section saved successfully.</div>
<?php endif; ?>

<div class="glass-card rounded-2xl p-6 md:p-8 max-w-5xl">
    <form method="POST" action="" class="space-y-8">
        <?= csrf_field() ?>

        <div>
            <h2 class="font-orbitron text-lg font-bold text-white mb-4">Main Text</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-400 text-sm font-medium mb-2">Tag</label>
                    <input type="text" name="tag" maxlength="100"
                           value="<?= htmlspecialchars($about['tag']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="Get To Know Me">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm font-medium mb-2">Heading</label>
                    <input type="text" name="heading" maxlength="100"
                           value="<?= htmlspecialchars($about['heading']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="About Me">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm font-medium mb-2">Number</label>
                    <input type="text" name="number" maxlength="10"
                           value="<?= htmlspecialchars($about['number']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="01">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm font-medium mb-2">Card Title</label>
                    <input type="text" name="title" maxlength="100"
                           value="<?= htmlspecialchars($about['title']) ?>"
                           class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                           placeholder="The Developer">
                </div>
            </div>
            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-gray-400 text-sm font-medium mb-2">Paragraph 1</label>
                    <textarea name="paragraph_1" rows="3"
                              class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600 resize-none"><?= htmlspecialchars($about['paragraph_1']) ?></textarea>
                </div>
                <div>
                    <label class="block text-gray-400 text-sm font-medium mb-2">Paragraph 2</label>
                    <textarea name="paragraph_2" rows="3"
                              class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600 resize-none"><?= htmlspecialchars($about['paragraph_2']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="border-t border-neon-blue/10 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-orbitron text-lg font-bold text-white">Statistics</h2>
                <button type="button" onclick="addStat()" class="text-neon-blue hover:text-cyan-400 text-sm transition-colors">+ Add Statistic</button>
            </div>
            <div id="statsWrap" class="space-y-3">
                <?php foreach ($stats as $i => $s): ?>
                    <div class="flex gap-3 items-center stat-row">
                        <input type="text" name="stat_value[]" value="<?= htmlspecialchars($s['value']) ?>"
                               class="input-field-admin w-24 rounded-lg px-3 py-2 text-white placeholder-gray-600" placeholder="3+">
                        <input type="text" name="stat_label[]" value="<?= htmlspecialchars($s['label']) ?>"
                               class="input-field-admin flex-1 rounded-lg px-3 py-2 text-white placeholder-gray-600" placeholder="Label">
                        <button type="button" onclick="this.closest('.stat-row').remove()" class="text-red-400 hover:text-red-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="border-t border-neon-blue/10 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-orbitron text-lg font-bold text-white">Features</h2>
                <button type="button" onclick="addFeature()" class="text-neon-blue hover:text-cyan-400 text-sm transition-colors">+ Add Feature</button>
            </div>
            <div id="featuresWrap" class="space-y-4">
                <?php foreach ($features as $i => $f): ?>
                    <div class="glass-card rounded-xl p-4 feature-row">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <p class="text-gray-500 text-xs uppercase tracking-wider">Feature <?= $i + 1 ?></p>
                            <button type="button" onclick="this.closest('.feature-row').remove()" class="text-red-400 hover:text-red-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="space-y-2">
                            <input type="text" name="feat_title[]" value="<?= htmlspecialchars($f['title']) ?>"
                                                                       class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600" placeholder="Feature Title">
                            <textarea name="feat_desc[]" rows="2"
                                      class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600 resize-none" placeholder="Feature description"><?= htmlspecialchars($f['description']) ?></textarea>
                            <textarea name="feat_icon[]" rows="2"
                                      class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600 resize-none font-mono text-xs" placeholder="SVG path icon (viewBox 0 0 24 24)"><?= htmlspecialchars($f['icon_code']) ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-neon-blue/10">
            <button type="submit" class="neon-btn flex-1 px-6 py-3 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
                Save Changes
            </button>
            <a href="./" class="flex-1 px-6 py-3 rounded-lg border border-neon-blue/10 text-gray-400 hover:text-neon-blue transition-colors text-center font-orbitron text-sm font-semibold tracking-widest uppercase">
                Back
            </a>
        </div>
    </form>
</div>

<script>
function addStat() {
    const wrap = document.getElementById('statsWrap');
    const div = document.createElement('div');
    div.className = 'flex gap-3 items-center stat-row';
    div.innerHTML =
        '<input type="text" name="stat_value[]" class="input-field-admin w-24 rounded-lg px-3 py-2 text-white placeholder-gray-600" placeholder="3+' + '">' +
        '<input type="text" name="stat_label[]" class="input-field-admin flex-1 rounded-lg px-3 py-2 text-white placeholder-gray-600" placeholder="Label">' +
        '<button type="button" onclick="this.closest(\'.stat-row\').remove()" class="text-red-400 hover:text-red-300 transition-colors">' +
        '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>';
    wrap.appendChild(div);
}

function addFeature() {
    const wrap = document.getElementById('featuresWrap');
    const div = document.createElement('div');
    div.className = 'glass-card rounded-xl p-4 feature-row';
    div.innerHTML =
        '<div class="flex items-start justify-between gap-3 mb-2">' +
        '<p class="text-gray-500 text-xs uppercase tracking-wider">New Feature</p>' +
        '<button type="button" onclick="this.closest(\'.feature-row\').remove()" class="text-red-400 hover:text-red-300 transition-colors">' +
        '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></div>' +
        '<div class="space-y-2">' +
        '<input type="text" name="feat_title[]" class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600" placeholder="Feature Title">' +
        '<textarea name="feat_desc[]" rows="2" class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600 resize-none" placeholder="Feature description"></textarea>' +
        '<textarea name="feat_icon[]" rows="2" class="input-field-admin w-full rounded-lg px-3 py-2 text-white placeholder-gray-600 resize-none font-mono text-xs" placeholder="SVG path icon"></textarea>' +
        '</div>';
    wrap.appendChild(div);
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
