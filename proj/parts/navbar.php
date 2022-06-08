<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="news_index.php">Logo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $pageName == 'index' ? 'active' : '' ?>" href="news_index.php">最新消息</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $pageName == 'list' ? 'active' : '' ?>" href="news_list.php">消息清單</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $pageName == 'add' ? 'active' : '' ?>" href="news_add.php">新增消息</a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="搜尋內容" aria-label="Search">
                <button class="btn btn-outline-light" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
    </div>
</nav>