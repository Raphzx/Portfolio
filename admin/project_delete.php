<?php
require_once __DIR__ . '/../config/auth.php';

require_auth();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: projects');
    exit;
}

$stmt = db()->prepare("SELECT icon_path FROM projects WHERE id = :id");
$stmt->execute(['id' => $id]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: projects');
    exit;
}

$icon = $project['icon_path'];
if (preg_match('#^assets/img/projects/[a-zA-Z0-9._-]+\.(png|jpg|jpeg|gif|webp)$#i', $icon)) {
    $file = __DIR__ . '/../' . $icon;
    if (is_file($file)) {
        unlink($file);
    }
}

$stmt = db()->prepare("DELETE FROM projects WHERE id = :id");
$stmt->execute(['id' => $id]);

header('Location: projects?deleted=1');
exit;