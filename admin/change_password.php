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
        $error = 'Current password is incorrect.';
    } elseif (strlen($newPassword) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($newPassword !== $confirm) {
        $error = 'Password confirmation does not match.';
    } else {
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = db()->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->execute(['password' => $newHash, 'id' => $_SESSION['user_id']]);
        $message = 'Password changed successfully.';
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="mb-8">
    <h1 class="font-orbitron text-2xl md:text-3xl font-bold text-white mb-2">Change Password</h1>
    <p class="text-gray-500 text-sm">Update your admin account login password.</p>
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
            <label class="block text-gray-400 text-sm font-medium mb-2">Current Password *</label>
            <input type="password" name="current_password" required
                   class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                   placeholder="Enter current password">
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">New Password *</label>
            <input type="password" name="new_password" required minlength="8"
                   class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                   placeholder="Minimum 8 characters">
            <p class="text-gray-700 text-xs mt-1">Minimum 8 characters.</p>
        </div>

        <div>
            <label class="block text-gray-400 text-sm font-medium mb-2">Confirm New Password *</label>
            <input type="password" name="confirm_password" required minlength="8"
                   class="input-field-admin w-full rounded-lg px-4 py-3 text-white placeholder-gray-600"
                   placeholder="Re-enter new password">
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-2">
            <button type="submit" class="neon-btn flex-1 px-6 py-3 rounded-lg font-orbitron text-sm font-semibold tracking-widest uppercase text-neon-blue border border-neon-blue/30">
                Save Password
            </button>
            <a href="./" class="flex-1 px-6 py-3 rounded-lg border border-neon-blue/10 text-gray-400 hover:text-neon-blue transition-colors text-center font-orbitron text-sm font-semibold tracking-widest uppercase">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>