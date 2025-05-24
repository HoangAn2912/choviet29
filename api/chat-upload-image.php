<?php
header("Content-Type: application/json");
$targetDir = __DIR__ . '/../img/';
$allowedTypes = ['image/jpeg', 'image/png'];
$uploaded = [];

foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
    $type = $_FILES['images']['type'][$i];
    if (!in_array($type, $allowedTypes)) continue;

    $ext = $type === 'image/png' ? '.png' : '.jpg';
    $fileName = uniqid('chat_', true) . $ext;
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($tmpName, $targetFile)) {
        $uploaded[] = 'img/' . $fileName;
    }
}

echo json_encode(['files' => $uploaded]);
?>