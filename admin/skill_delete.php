<?php
require_once __DIR__ . '/../config/auth.php';

require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && empty($_GET['id'])) {
    header('Location: skills');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: skills');
    exit;
}

$stmt = db()->prepare("SELECT icon FROM skills WHERE id = :id");
$stmt->execute(['id' => $id]);
$icon = $stmt->fetchColumn();

$del = db()->prepare("DELETE FROM skills WHERE id = :id");
$del->execute(['id' => $id]);

if ($icon && preg_match('/skills\/skill_/', (string)$icon)) {
    $path = __DIR__ . '/../' . $icon;
    if (is_file($path)) {
        @unlink($path);
    }
}

header('Location: skills?deleted=1');
exit;
