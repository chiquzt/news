<?php require __DIR__ . '/parts/connect_db.php';
$pageName = 'list';
$title = '消息清單';

$perPage = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM news";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

$totalPages = ceil($totalRows / $perPage);

$sql = sprintf("SELECT * FROM `news` 
JOIN `location` ON news.location_sid = location.l_sid 
JOIN `type` ON news.type_sid = type.ty_sid 
ORDER BY event_time DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

$stmt = $pdo->query("$sql");
$rows = $stmt->fetchAll();

class newsClass
{
    public $topic;
    public $event_time;
    public $type_name;
    public $location;
    public $content;
    public $publish_date;
    public $tag;
}

$newsList = array();

foreach ($rows as $r) {
    $news_sid = $r['sid'];

    $t_sql = $pdo->query("SELECT * FROM `news_tag` JOIN `tag` ON news_tag.tag_sid = tag.tg_sid WHERE news_tag.news_sid = $news_sid ")->fetchAll();

    $news = new newsClass();

    $news->sid = $r['sid'];
    $news->topic = $r['topic'];
    $news->event_time = $r['event_time'];
    $news->type_name = $r['type_name'];
    $news->location = $r['location'];
    $news->content = $r['content'];
    $news->publish_date = $r['publish_date'];
    $news->tag = $t_sql;

    array_push($newsList, $news);
}

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>
<div class="container">
    <div class="bd-example">
        <table class="table table-striped">
            <thead>
                <tr>
                    <td scope="col">
                        <a href="">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                    <td scope="col">編號</td>
                    <td scope="col">標題</td>
                    <td scope="col">事件時間</td>
                    <td scope="col">類型</td>
                    <!-- <td scope="col">圖片</td> -->
                    <td scope="col">地點</td>
                    <td scope="col">內容</td>
                    <td scope="col">發布時間</td>
                    <td scope="col">標籤</td>
                    <td scope="col">
                        <i class="fa-solid fa-file-pen"></i>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsList as $n) : ?>
                    <tr>
                        <td>
                            <a href="news_delete.php?sid=<?= $n->sid ?>" onclick="return confirm(`確定要刪除編號為<?= $n->sid ?>的消息嗎？`)">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                        <td><?= $n->sid ?></td>
                        <td><?= htmlentities($n->topic) ?></td>
                        <td><?= $n->event_time ?></td>
                        <td><?= $n->type_name ?></td>
                        <!-- <td><?= $r['img'] ?></td> -->
                        <td><?= $n->location ?></td>
                        <td><?= htmlentities($n->content) ?></td>
                        <td><?= $n->publish_date ?></td>

                        <td>
                            <?php foreach ($n->tag as $t) : ?>
                                <?= htmlentities($t['tag_name']) ?>
                            <? endforeach; ?>
                        </td>

                        <td>
                            <a href="news_edit.php?sid=<?= $n->sid ?>">
                                <i class="fa-solid fa-file-pen"></i>
                            </a>
                        </td>
                    </tr>
                <? endforeach; ?>
            </tbody>

        </table>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=1">
                        <i class="fa-solid fa-angles-left"></i>
                    </a>
                </li>

                <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                </li>

                <?php for ($i = $page - 2; $i <= $page + 2; $i++) :
                    if ($i >= 1 && $i <= $totalPages) :
                ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                <?php endif;
                endfor; ?>

                <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">
                        <i class="fa-solid fa-angle-right"></i>
                    </a>
                </li>

                <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $totalPages ?>">
                        <i class="fa-solid fa-angles-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<?php include __DIR__ . '/parts/scripts.php' ?>
<?php include __DIR__ . '/parts/html-foot.php' ?>