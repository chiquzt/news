<?php require __DIR__ . '/parts/connect_db.php';

$folder = __DIR__ . '/uploaded/';

$extMap = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
    'image/gif' => '.gif',
];


$output = [
    'success' => false,
    'postData' => $_POST,
    'filename' => '',
    'error' => '',
];

if (empty($extMap[$_FILES['img']['type']])) {
    $output['error'] = '檔案類型錯誤';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
$ext = $extMap[$_FILES['img']['type']];

$filename = md5($_FILES['img']['name'] . rand()) . $ext;
//$output['filename'] = $filename;

move_uploaded_file($_FILES['img']['tmp_name'], $folder . $filename);
//move_uploaded_file($_FILES['img']['tmp_name'], $folder . $_FILES['img']['name']);


if (empty($_POST['topic'])) {
    $output['error'] = '沒有標題名稱';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "INSERT INTO `news`(
    `topic`, `event_time`, `type_sid`,
    `img`,`location_sid`, 
    `content`,`publish_date`) 
     VALUES(
         ?,?,?,
         ?,?,?,
         ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['topic'],
    $_POST['event_time'],
    $_POST['type_sid'],
    $filename,
    // $_FILES['img']['name'] ?? '',
    $_POST['location_sid'],
    $_POST['content'],
    $_POST['publish_date'],
]);



if ($stmt->rowCount() == 1) {
    $output['success'] = true;
    $output['lastInsertId'] = $pdo->lastInsertId();
} else {
    $output['error'] = '資料沒有新增';
}

$newsId = $pdo->lastInsertId();

$t_sql = "INSERT INTO `news_tag`(`news_sid`, `tag_sid`) 
    VALUES (?,?)";


if (!empty($_POST['tag_add'])) {
    $sql = "INSERT INTO `tag`(
    `tag_name`) VALUES(?)";

    $stmt = $pdo->prepare($sql)
        ->execute([
            $_POST['tag_add'],
        ]);

    $tagId = $pdo->lastInsertId();


    $stmt = $pdo->prepare($t_sql)
        ->execute([
            $newsId,
            $tagId
        ]);
}

$output['tg_sid'] = $_POST['tg_sid'];

$checkbox = $_POST['tg_sid'];
foreach ($checkbox as $c) {
    $stmt = $pdo->prepare($t_sql)
        ->execute([
            $newsId,
            $c
        ]);
}

$json = json_encode($output, JSON_UNESCAPED_UNICODE);
echo $json;
