<?php
header("Content-Type: application/json");
$from = isset($_POST['from']) ? intval($_POST['from']) : 0;
$to = isset($_POST['to']) ? intval($_POST['to']) : 0;
if (!$from || !$to) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'msg' => 'Thiếu tham số']);
    exit;
}
$min = min($from, $to);
$max = max($from, $to);
$file = __DIR__ . "/../chat/chat_{$min}_{$max}.json";
if (!file_exists($file)) {
    echo json_encode(['status' => 'ok']);
    exit;
}
$data = json_decode(file_get_contents($file), true);
$changed = false;
foreach ($data as &$msg) {
    if ($msg['from'] == $from && $msg['to'] == $to && (!isset($msg['da_doc']) || $msg['da_doc'] == 0)) {
        $msg['da_doc'] = 1;
        $changed = true;
    }
}
if ($changed) file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode(['status' => 'ok']);
?>