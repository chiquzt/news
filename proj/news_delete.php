<?php require __DIR__ . '/parts/connect_db.php';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;

if (!empty($sid)) {
    $pdo->query("DELETE FROM `news` WHERE sid = $sid");
}
$come_from = 'news_list.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $come_from = $_SERVER['HTTP_REFERER'];
}
header("Location: $come_from");
