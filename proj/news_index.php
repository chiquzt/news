<?php require __DIR__ . '/parts/connect_db.php';
$pageName = 'index';
$title = '最新消息';

$perPage = 8;
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

    $tag = $pdo->query("SELECT * FROM `news_tag` JOIN `tag` ON news_tag.tag_sid = tag.tg_sid WHERE news_tag.news_sid = $news_sid ")->fetchAll();

    $news = new newsClass();

    $news->topic = $r['topic'];
    $news->event_time = $r['event_time'];
    $news->type_name = $r['type_name'];
    $news->img = $r['img'];
    $news->location = $r['location'];
    $news->content = $r['content'];
    $news->publish_date = $r['publish_date'];
    $news->tag = $tag;

    array_push($newsList, $news);
}

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>
<style>
    .imgwrap {
        height: 130px;
        overflow: hidden
    }

    .imgsize {
        transform: scale(1.3);
    }
</style>
<div class="container">
    <div class="row">
        <?php foreach ($newsList as $n) : ?>
            <div class="col-3 d-flex flex-wrap mb-3 mt-3">
                <div class="card mb-3">
                    <div class="imgwrap">
                        <img src="./uploaded/<?= $n->img ?>" class="card-img-top imgsize">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $n->topic ?></h5>
                        <p class="card-title"><?= $n->event_time ?></p>
                        <p class="card-text"><?= $n->type_name ?></p>
                        <p class="card-text"><?= $n->location ?></p>
                        <p class="card-text"><?= $n->content ?></p>
                        <div class="d-flex flex-wrap">

                            <?php foreach ($n->tag as $t) : ?>
                                <span class="card-text me-2"><?= "#" . $t['tag_name'] ?></span>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted"><?= $n->publish_date ?></small>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </div>
</div>
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


<?php include __DIR__ . '/parts/html-foot.php' ?>
<?php include __DIR__ . '/parts/scripts.php' ?>