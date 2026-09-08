<?php
require_once __DIR__ . '/header.php';

$skills = db()->query("SELECT * FROM skills ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<div class="flex items-center justify-between mb-8 flex-wrap gap-4">
    <div>
        <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2">Skills</h1>
        <p class="text-gray-500 text-sm">Manage your skills list.</p>
    </div>
    <a href="skill_form" class="neon-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Add Skill
    </a>
</div>

<?php if (isset($_GET['added'])): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">Skill added successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">Skill updated successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">Skill deleted successfully.</div>
<?php endif; ?>

<div class="glass-card rounded-2xl overflow-hidden">
    <?php if (empty($skills)): ?>
        <div class="p-10 text-center">
            <p class="text-gray-500 mb-4">No skills yet.</p>
            <a href="skill_form" class="text-neon-blue hover:text-cyan-400 transition-colors">Add your first skill &rarr;</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neon-blue/10 text-gray-500 uppercase text-xs tracking-wider">
                        <th class="text-left px-6 py-4 font-medium">#</th>
                        <th class="text-left px-6 py-4 font-medium">Skill</th>
                        <th class="text-left px-6 py-4 font-medium hidden md:table-cell">Level</th>
                        <th class="text-right px-6 py-4 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($skills as $s): ?>
                        <tr class="border-b border-neon-blue/5 table-row">
                            <td class="px-6 py-4 text-gray-600"><?= (int)$s['sort_order'] ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="../<?= htmlspecialchars($s['icon']) ?>" alt="" class="w-8 h-8 object-contain" onerror="this.style.display='none'">
                                    <span class="text-white font-medium"><?= htmlspecialchars($s['name']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <div class="flex items-center gap-3 max-w-xs">
                                    <div class="flex-1 h-2 rounded-full bg-nightwing-800 overflow-hidden">
                                        <div class="h-full rounded-full bg-neon-blue" style="width: <?= (int)$s['level'] ?>%"></div>
                                    </div>
                                    <span class="text-neon-blue font-orbitron text-xs font-bold shrink-0"><?= (int)$s['level'] ?>%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="skill_form?id=<?= (int)$s['id'] ?>" class="px-3 py-1.5 rounded-lg bg-neon-blue/10 border border-neon-blue/20 text-neon-blue hover:bg-neon-blue/20 transition-colors text-xs font-medium">Edit</a>
                                     <a href="#" onclick="confirmDelete(<?= (int)$s['id'] ?>, '<?= htmlspecialchars(addslashes($s['name'])) ?>')" class="px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 transition-colors text-xs font-medium">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm('Are you sure you want to delete skill "' + name + '"? This action cannot be undone.')) {
        window.location.href = 'skill_delete?id=' + id;
    }
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
