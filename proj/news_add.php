<?php require __DIR__ . '/parts/connect_db.php';
$pageName = 'add';
$title = '新增消息';

$opt = $pdo->query('SELECT * FROM `type`')->fetchAll();
$loc = $pdo->query('SELECT * FROM `location`')->fetchAll();
$tag = $pdo->query('SELECT * FROM `tag`')->fetchAll();

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>
<style>
    .form-control.red {
        border: 1px solid red;
    }

    .form-select.red {
        border: 1px solid red;
    }

    .form-text.red {
        color: red;
    }

    .imgwrap {
        width: 300px;
        overflow: hidden;
        border-radius: 10px;
    }

    .img {
        width: 100%;

    }
</style>
<div class="container">
    <div class="card" style="width: 50rem; margin:30px auto 0 auto;">
        <div class="card-body">
            <h5 class="card-title">新增最新消息</h5>
            <form name="form1" onsubmit="checkData();return false;" novalidate>

                <div class="row mb-3">
                    <label for="topic" class="col-sm-2 col-form-label">標題</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="topic" name="topic">
                        <div class="form-text red"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="eventtime" class="col-sm-2 col-form-label">事件時間</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="eventtime" name="event_time">
                        <div class="form-text red"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-form-label col-sm-2 pt-0">事件類型</label>
                    <div class="col-sm-10">
                        <?php foreach ($opt as $o) : ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_sid" id="type-<?= $o['ty_sid'] ?>" value="<?= $o['ty_sid'] ?>">
                                <label class="form-check-label" for="">
                                    <?= $o['type_name'] ?>
                                </label>
                                <div class="form-text red"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="" class="col-sm-2">圖片</label>
                    <div class="col-sm-10">
                        <input type="file" id="img" class="form-control" name="img" accept="image/*" onchange="changeImg()">
                        <div id="imgwrap" class="">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="" class="col-sm-2 col-form-label">地點</label>
                    <div class="col-sm-10">
                        <select class="form-select" name="location_sid">
                            <option value="" selected disabled>請選擇</option>
                            <?php foreach ($loc as $l) : ?>
                                <option value="<?= $l['l_sid'] ?>"><?= $l['location'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text red"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="" class="col-sm-2 col-form-label">內容</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="content" rows="5"></textarea>
                        <div class="form-text red"></div>
                    </div>
                </div>
                <!-- <div class="row mb-3">
                    <label for="" class="col-sm-2 col-form-label">標籤</label>
                    <div class="col-sm-10" id="tag_div">
                        <div class="input-group flex-nowrap" id="tag_group">
                            <span class="input-group-text" id="tag">#</span>
                            <select class="form-select" name="tag_sid" id="tag_sid-<?= $t['tg_sid'] ?>" onchange="addTag();">
                                <option value="" selected disabled>請選擇</option>
                                <?php foreach ($tag as $t) : ?>
                                    <option value="<?= $t['tg_sid'] ?>"><?= $t['tag_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div> -->

                <div class="row mb-3">
                    <label for="" class="col-sm-2 col-form-label">標籤</label>
                    <div class="col-sm-10 d-flex flex-wrap">
                        <?php foreach ($tag as $t) : ?>
                            <div class="form-check mx-2">
                                <input class="form-check-input" type="checkbox" name="tg_sid[]" value="<?= $t['tg_sid'] ?>" id="tag-<?= $t['tg_sid'] ?>">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <?= $t['tag_name'] ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <div class="input-group my-2" id="tag_group">
                            <span class="input-group-text" id="tag">#</span>
                            <input type="text" class="form-control" id="" name="tag_add">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="" class="col-sm-2 col-form-label">發布時間</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="publishdate" name="publish_date">
                        <div class="form-text red"></div>

                    </div>
                </div>
                <button type="submit" class="btn btn-primary">新增</button>
                <div id="info_bar" class="alert alert-success" role="alert" style="display:none;">
                    消息新增成功！
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/scripts.php' ?>

<script>
    const topic_f = document.form1.topic;
    const eventtime_f = document.form1.event_time;
    const type_f = document.form1.type_sid;
    const location_f = document.form1.location_sid;
    const content_f = document.form1.content;
    const publishdate_f = document.form1.publish_date;

    const fields = [topic_f, eventtime_f, type_f, location_f, content_f, publishdate_f];
    const fieldTexts = [];

    const info_bar = document.querySelector('#info_bar');


    for (let f of fields) {
        fieldTexts.push(f.nextElementSibling);
    }


    function changeImg() {

        const imgwrap = document.querySelector('#imgwrap');

        imgwrap.innerHTML = '<img class="img" id="newsimg" src="" alt="">';
        img.nextElementSibling.classList.add('imgwrap', 'mt-3');

        const file = event.currentTarget.files[0];
        console.log(file);
        const reader = new FileReader();

        reader.onload = function() {
            console.log(reader.result)
            document.querySelector('#newsimg').src = reader.result;
        };
        reader.readAsDataURL(file);
    }



    //echo json_encode($_FILES);



    async function checkData() {

        for (let i in fields) {
            //console.log(fields[i], fields[i].length, fields[i].nodeName, typeof fields[i])

            switch (fields[i].nodeName) {
                case 'INPUT':
                case 'SELECT':
                case 'TEXTAREA':
                    fields[i].classList.remove('red');
                    fieldTexts[i].innerText = '';
                    break;
                default:
                    break;
            }
        }

        let isPass = true;

        // info_bar.style.display = 'none';


        if (topic_f.value == '') {
            fields[0].classList.add('red');
            fieldTexts[0].innerText = '請填寫標題名稱';
            isPass = false;
        };


        if (eventtime_f.value == '') {
            fields[1].classList.add('red');
            fieldTexts[1].innerText = '請選擇事件時間';
            isPass = false;
        }

        // if (eventtype_f.value == 0) {
        //     fields[2].classList.add('red');
        //     fieldTexts[2].innerText = '請選擇類型';
        //     isPass = false;
        // }

        if (location_f.value == '') {
            fields[3].classList.add('red');
            fieldTexts[3].innerText = '請選擇地點';
            isPass = false;
        }

        if (content_f.value == '') {
            fields[4].classList.add('red');
            fieldTexts[4].innerText = '請填寫欲發布內容';
            isPass = false;
        }
        if (publishdate_f.value == '') {
            fields[5].classList.add('red');
            fieldTexts[5].innerText = '請選擇發佈時間';
            isPass = false;
        }
        if (!isPass) {
            return;
        }

        const fd = new FormData(document.form1);
        const r = fetch('news_add_api.php', {
                method: 'POST',
                body: fd
            }).then(r => {
                return r.json()
            })
            .then(result => {
                console.log(result);

                info_bar.style.display = 'block';

                if (result.success) {
                    info_bar.classList.add('alert-success');
                    info_bar.innerText = '新增一筆最新消息';
                    setTimeout(() => {
                        location.href = 'news_index.php';
                    }, 1000);
                } else {
                    nfo_bar.classList.remove('alert-success');
                    info_bar.classList.add('alert-danger');
                    info_bar.innerText = result.error || '資料沒有修改';
                }

            }).catch(r => {

                console.log(r)
            });
    }

    // function addTag() {
    //     const tagGroup = document.querySelector('#tag_group');
    //     const tagDiv = document.querySelector('#tag_div');

    //     tagDiv.appendChild(tagGroup.cloneNode(true));
    // }
</script>
<?php include __DIR__ . '/parts/html-foot.php' ?>