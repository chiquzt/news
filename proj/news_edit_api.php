<?php require __DIR__ . '/parts/connect_db.php';

$output = [
    'success' => false,
    'postData' => $_POST,
    'filename' => '',
    'error' => ''
];

$sid = isset($_POST['sid']) ? intval($_POST['sid']) : 0;

if (empty($_POST['sid']) or empty($_POST['topic'])) {
    $output['error'] = '沒有標題名稱';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$extMap = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/gif' => '.gif',
];

// $old_img=$_POST["old_img"];
// $filename='';
// if ($_FILES['file']['error'] == 0) {
//      $filename=$_FILES['file’][‘img’];
   
// }


if (empty($extMap[$_FILES['img']['type']])) {
    $output['error'] = '檔案類型錯誤';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}


$ext = $extMap[$_FILES['img']['type']];

$filename = md5($_FILES['img']['name'] . rand()) . $ext;
$folder = __DIR__ . '/uploaded/';
move_uploaded_file($_FILES['img']['tmp_name'], $folder . $filename);

$sql = "UPDATE `news` 
SET`topic`=?,`event_time`=?,`type_sid`=?,`img`=?,`location_sid`=?,`content`=?,`publish_date`=?
WHERE sid = $sid";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['topic'],
    $_POST['event_time'],
    $_POST['type_sid'],
    $filename,
    $_POST['location_sid'],
    $_POST['content'],
    $_POST['publish_date'],
]);

if ($stmt->rowCount() == 1) {
    $output['success'] = true;
} else {
    $output['error'] = '資料沒有修改';
}

// $newsId = $pdo->lastInsertId();

// $t_sql = "UPDATE `news_tag`SET`news_sid`=?, `tag_sid`=?";


// if (!empty($_POST['tag_add'])) {
//     $sql = "INSERT INTO `tag`(
//     `tag_name`) VALUES(?)";

//     $stmt = $pdo->prepare($sql)
//         ->execute([
//             $_POST['tag_add'],
//         ]);

//     $tagId = $pdo->lastInsertId();


//     $stmt = $pdo->prepare($t_sql)
//         ->execute([
//             $newsId,
//             $tagId
//         ]);
// }

// $output['tg_sid'] = $_POST['tg_sid'];

// $checkbox = $_POST['tg_sid'];
// foreach ($checkbox as $c) {
//     $stmt = $pdo->prepare($t_sql)
//         ->execute([
//             $sid,
//             $c
//         ]);
// }


$json = json_encode($output, JSON_UNESCAPED_UNICODE);

echo $json;
