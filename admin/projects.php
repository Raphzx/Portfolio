<?php
require_once __DIR__ . '/header.php';

$projects = db()->query("SELECT * FROM projects ORDER BY sort_order ASC, id ASC")->fetchAll();
?>

<div class="flex items-center justify-between mb-8 flex-wrap gap-4">
    <div>
        <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2">Projects</h1>
        <p class="text-gray-500 text-sm">Kelola daftar proyek portfolio Anda.</p>
    </div>
    <a href="project_form.php" class="neon-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Tambah Project
    </a>
</div>

<?php if (isset($_GET['added'])): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">Project berhasil ditambahkan.</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">Project berhasil diperbarui.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm">Project berhasil dihapus.</div>
<?php endif; ?>

<div class="glass-card rounded-2xl overflow-hidden">
    <?php if (empty($projects)): ?>
        <div class="p-10 text-center">
            <p class="text-gray-500 mb-4">Belum ada proyek.</p>
            <a href="project_form.php" class="text-neon-blue hover:text-cyan-400 transition-colors">Tambah proyek pertama &rarr;</a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-neon-blue/10 text-gray-500 uppercase text-xs tracking-wider">
                        <th class="text-left px-6 py-4 font-medium">#</th>
                        <th class="text-left px-6 py-4 font-medium">Project</th>
                        <th class="text-left px-6 py-4 font-medium hidden md:table-cell">Tags</th>
                        <th class="text-left px-6 py-4 font-medium hidden lg:table-cell">Demo</th>
                        <th class="text-left px-6 py-4 font-medium hidden lg:table-cell">Source</th>
                        <th class="text-right px-6 py-4 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $p): 
                        $tags = json_decode($p['tags'], true) ?: [];
                    ?>
                        <tr class="border-b border-neon-blue/5 table-row">
                            <td class="px-6 py-4 text-gray-600"><?= (int)$p['sort_order'] ?></td>
                            <td class="px-6 py-4 max-w-xs">
                                <div class="flex items-center gap-3">
                                    <?php if (preg_match('/\.(png|jpg|jpeg|gif|webp)$/i', $p['icon_path'])): ?>
                                        <img src="../<?= htmlspecialchars($p['icon_path']) ?>" alt="" class="w-10 h-10 rounded-lg object-cover border border-neon-blue/10">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-lg bg-neon-blue/10 border border-neon-blue/20 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-neon-blue/60" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path d="<?= htmlspecialchars($p['icon_path']) ?>"/></svg>
                                        </div>
                                    <?php endif; ?>
                                    <span class="text-white font-medium truncate" title="<?= htmlspecialchars($p['title']) ?>"><?= htmlspecialchars($p['title']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <div class="flex flex-wrap gap-1">
                                    <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-neon-blue/10 text-neon-blue/80 border border-neon-blue/10"><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                    <?php if (count($tags) > 3): ?>
                                        <span class="px-2 py-0.5 rounded-full text-xs text-gray-500">+<?= count($tags) - 3 ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <?php if (!empty($p['demo_url'])): ?>
                                    <a href="<?= htmlspecialchars($p['demo_url']) ?>" target="_blank" rel="noopener" class="text-neon-blue hover:text-cyan-400 transition-colors flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                        Demo
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-700">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <?php if (!empty($p['source_url'])): ?>
                                    <a href="<?= htmlspecialchars($p['source_url']) ?>" target="_blank" rel="noopener" class="text-neon-blue hover:text-cyan-400 transition-colors flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                        Source
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-700">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="project_form.php?id=<?= (int)$p['id'] ?>" class="px-3 py-1.5 rounded-lg bg-neon-blue/10 border border-neon-blue/20 text-neon-blue hover:bg-neon-blue/20 transition-colors text-xs font-medium">Edit</a>
                                    <a href="#" onclick="confirmDelete(<?= (int)$p['id'] ?>, '<?= htmlspecialchars(addslashes($p['title'])) ?>')" class="px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 transition-colors text-xs font-medium">Hapus</a>
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
function confirmDelete(id, title) {
    if (confirm('Yakin ingin menghapus project "' + title + '"? Aksi ini tidak bisa dibatalkan.')) {
        window.location.href = 'project_delete.php?id=' + id;
    }
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
