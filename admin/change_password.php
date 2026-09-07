<?php
require_once __DIR__ . '/../config/auth.php';

require_auth();

$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $current     = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirm     = $_POST['confirm_password'] ?? '';

    $stmt = db()->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $hash = (string)$stmt->fetchColumn();

    if (!password_verify($current, $hash)) {
        $error = 'Password saat ini salah.';
    } elseif (strlen($newPassword) < 8) {
        $error = 'Password baru minimal 8 karakter.';
    } elseif ($newPassword !== $confirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = db()->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->execute(['password' => $newHash, 'id' => $_SESSION['user_id']]);
        $message = 'Password berhasil diubah.';
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="mb-8">
    <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2">Ubah Password</h1>
    <p class="text-gray-500 text-sm">Ganti password login akun admin Anda.</p>
</div>

<?php if ($message !== ''): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error !== ''): ?>
    <div class="mb-6 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="glass-card rounded-2xl p-6 md:p-8 max-w-md">
    <form method="POST" action="" class="space-y-5">
        <?= csrf_field() ?>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Password Saat Ini *</label>
            <input type="password" name="current_password" required
                   class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                   placeholder="Masukkan password lama">
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Password Baru *</label>
            <input type="password" name="new_password" required minlength="8"
                   class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                   placeholder="Minimal 8 karakter">
            <p class="text-gray-700 text-xs mt-1">Minimal 8 karakter.</p>
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Konfirmasi Password Baru *</label>
            <input type="password" name="confirm_password" required minlength="8"
                   class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                   placeholder="Ulangi password baru">
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-2">
            <button type="submit" class="neon-btn flex-1 px-6 py-3 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
                Simpan Password
            </button>
            <a href="./" class="flex-1 px-6 py-3 rounded-lg border border-neon-blue/10 text-gray-400 hover:text-neon-blue transition-colors text-center font-orbitron text-sm font-semibold tracking-widest uppercase">
                Batal
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>