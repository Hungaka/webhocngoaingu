<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Ôn tập siêu tốc | ' . $Database->site("TenWeb") . '';
$locationPage = 'home_page';
require_once(__DIR__ . "/../../public/client/header_hoctap.php");

checkLogin();
if (isset($_GET['maKhoaHoc']) && isset($_GET['maBaiHoc'])) {
    $checkDangKyKhoaHoc = $Database->get_row("SELECT * FROM dangkykhoahoc WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' AND `MaKhoaHoc` = '" . check_string($_GET['maKhoaHoc']) . "' ");

    $khoaHoc = $Database->get_row("SELECT * FROM `khoahoc` WHERE `MaKhoaHoc` = '" . check_string($_GET['maKhoaHoc']) . "'  ");
    $baiHoc = $Database->get_row("SELECT * FROM `baihoc` WHERE `MaKhoaHoc` = '" . check_string($_GET['maKhoaHoc']) . "' AND `MaBaiHoc` = '" . check_string($_GET['maBaiHoc']) . "'");

    if ($khoaHoc <= 0 || $baiHoc <= 0 || $checkDangKyKhoaHoc <= 0) {
        return die('<script type="text/javascript">
    setTimeout(function(){ location.href = "' . BASE_URL('') . '" }, 0);
    </script>
    ');
    }
}


// Tao moi database hoc tu moi
$token = randomString('0123456789QWERTYUIOPASDGHJKLZXCVBNM', '20');
$createDatabase = $Database->insert("ontapsieutoctuvung", [
    'TaiKhoan' => $_SESSION["account"],
    'Token' => $token,
    'SoMang' => 3
]);
if ($createDatabase) {
    $_SESSION["thongTinTokenFastPractice"] = $token;
} else {
    msg_error2('Có lỗi xảy ra trong quá trình khởi tạo.');
}


?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/review.css");
    ?>
</style>
<div class="header">
    <div class="grid wide">
        <div class="header_wrap">
            <h2 class="header__name"><?= $Database->site("TenWeb") ?></h2>
            <div class="nav">
                <div class="nav__statr">Khóa học <?= $khoaHoc["TenKhoaHoc"] ?></div>
            </div>
        </div>
    </div>
</div>
<div class="study_container">
    <input type="hidden" id="practiceToken" value=<?= $token ?> />
    <div id="thongbao"></div>
    <div class="grid wide">

        <a onclick={confirmExit()} class="return_home_page btn">Trang chủ</a>

        <div class="content">
            <div class="targer-bar">
                <div class="targer-bar-value"></div>
            </div>
            <div id="loading"></div>
            <div id="study_content" style="width: 100%;">

            </div>
        </div>

    </div>
</div>



</div>
<script src="<?= BASE_URL("/") ?>/assets/javascript/play_sound.js"></script>

<script>
    let audioWord = '';
    let timeInterval = null;

    function exit(type) {
        if (type === "yes") {
            window.history.back();
        } else {
            $("#containerCheckExit").remove();
        }
    }

    function confirmExit() {
        let str = `<div class="check_exit" id="containerCheckExit">
<div class="study__footer_popup background-exit">
    <div class="study__footer_popup-container">
        <div class="answer__container">
       
            <div class="answer__right">
                <div class="answer__right-title">Bạn muốn dừng lại thật sao?</div>
                <div class="answer__of-question">Bạn nên chăm chỉ luyện tập thêm nhé</div>
            </div>
        </div>
        <div class="answer__container">

        <div class="question__btn-stay btn btn--no_active question__btn--no-active" onclick={exit("no")}>Ở lại</div>
        <div class="answer__btn-continue btn"  onclick={exit("yes")}>Thoát</div>
</div>
    </div>
</div>
</div>`;

        $(".study_container").append(str);

    }

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }



    function setBarValue(value = 0) {
        $(".targer-bar-value").width(`${value}%`);
    }

    function createHeart(value = 0) {
        let str = `<ul class="life__list">`;
        for (let i = 0; i < value; i++) {
            str += `<li class="life__item"><i class="life__item-icon fa-solid fa-heart"></i></li>`;

        }
        str += `</ul>`;
        return str;

    }

    function createTimerBar() {
        let initStateBar = 100;
        setBarValue(100);
        timeInterval = setInterval(() => {
            if (initStateBar <= 0) {
                clearInterval(timeInterval);
                resetAnswerLoai1();
                return;
            }
            initStateBar -= 10 / 100;
            setBarValue(initStateBar);

        }, 1000 / 100)
    }

    function khoiTao() {
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/Study.php"); ?>",
            method: "POST",
            data: {
                type: 'FastPracticeWord',
                maKhoaHoc: <?= $_GET["maKhoaHoc"] ?>,
                maBaiHoc: <?= $_GET["maBaiHoc"] ?>,
                token: $("#practiceToken").val(),
            },
            beforeSend: function() {
                clearInterval(timeInterval);
                $("#study__footer_popup").remove();
                $('#loading_modal').addClass("loading--open");
            },
            success: function(response) {
                $('#loading_modal').removeClass("loading--open");
                let json = $.parseJSON(response);
                if (json.status === "complete") {
                    toastr.success("Không còn từ nào để ôn tập", "Thành công!");
                    setTimeout(() => {
                        window.history.back();
                    }, 500)
                    return;
                }
                createTimerBar();
                let str, dataAnswer, strDataAnswer = "";
                if (json.data.type == 1) {
                    dataAnswer = (json.data.randomAnswer);
                    dataAnswer.forEach((item, index) => {
                        strDataAnswer += `
                                        <div class="new-words__wrap_item">
                                            <div class="new-words__item" onclick="checkAnswerLoai1(${item.MaTuVung}, '${item.AmThanh}')">
                                                <img src="${item.HinhAnh}" alt="" class="new-words__img">
                                        
                                                <div class="new-words__name">${item.NoiDungTuVung}</div>
                                            
                                            </div>
                                        </div>
                                    `;
                    })

                    str = `
                    <input type="hidden" id="tokenOnTap" value='${json.data.tokenOnTap}' /> 
                    <div class="wrap__life">
                        ${createHeart(json.data.soMang)}
                        <div class="warp-introduce__ponit">
                            <img src="<?= BASE_URL("/") ?>/assets/img/introcduce-words.svg" alt="" class="introduce__point-img">
                            <span class="introcduce__point-number">${json.data.data.Diem}</span>
                        </div>
                    </div>
                    <div class="warp-introduce">
                        <div class="introduce__new">Chọn câu trả lời phù hợp</div>
                        <div class="score">
                            <div class="score__title">Đúng:</div>
                            <span class="score__number">${json.data.soCauDung}</span>
                        </div>
                    </div>
                    <div class="conjunction">
                    <div class="new-words">
                        <div class="new-words__heading">Đâu là "${json.data.data.DichNghia}" ?</div>
                        <div class="new-words__list">
                         
                               
                                   ${strDataAnswer}
                             

                           
                        </div>
                    </div>
                    <div class="study_review_button_container">
                    <div onclick="{danhDauTuKhoOnTap('${json.data.tokenOnTap}')}" class="btn btn--card study_review_button"><img src="<?= BASE_URL("/") ?>/assets/img/modal-degree.svg" alt=""></div>
                    
                    </div>
                                </div>`;

                }
                $("#study_content").empty().append(str);

            }
        })
    };

    function danhDauTuKhoOnTap(token) {
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/Study.php"); ?>",
            method: "POST",
            data: {
                type: 'DanhDauTuKhoOnTap',
                token: token,
            },
            beforeSend: function() {
                $('#loading_modal').addClass("loading--open");
            },
            success: function(response) {
                let json = $.parseJSON(response);
                $('#loading_modal').removeClass("loading--open");
                $("#thongbao").empty().append(json.message);
            }
        })
    }

    function checkAnswerLoai1(userAnswer, amThanh) {
        playSound(amThanh);
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/Study.php"); ?>",
            method: "POST",
            data: {
                type: 'FastPracticeWordType1',
                maKhoaHoc: <?= $_GET["maKhoaHoc"] ?>,
                maBaiHoc: <?= $_GET["maBaiHoc"] ?>,
                userAnswer: userAnswer,
                token: $('#tokenOnTap').val(),
                practiceToken: $('#practiceToken').val()
            },
            beforeSend: function() {

                $('#loading_modal').addClass("loading--open");
            },
            success: function(response) {
                let json = $.parseJSON(response);
                $('#loading_modal').removeClass("loading--open");
                $("#thongbao").empty().append(json.message);
                khoiTao();
                return;
                if (json.status === "success") {

                    let elmPopupRightAnswer = `<div class="check_exit" id="study__footer_popup"><div class="study__footer_popup background-right">
    <div class="study__footer_popup-container">
        <div class="answer__container">
            <img src="<?= BASE_URL("/") ?>/assets/img/answer_right.svg" alt="" class="answer__flase-img">
            <div class="answer__right">
                <div class="answer__right-title right">Chính xác:</div>
                <div class="answer__of-question right">${json.data.noiDungTuVung}</div>
            </div>
        </div>
        <div class="answer__btn-continue btn"  onclick={khoiTao()}>Tiếp tục</div>

    </div>
    </div>
</div>`;
                    $(".study_container").append(elmPopupRightAnswer);

                } else {
                    let elmPopupWrongAnswer = `<div class="check_exit" id="study__footer_popup"><div class="study__footer_popup background-wrong">
    <div class="study__footer_popup-container">
        <div class="answer__container">
            <img src="<?= BASE_URL("/") ?>/assets/img/answer_flase.svg" alt="" class="answer__flase-img">
            <div class="answer__right">
                <div class="answer__right-title wrong">Đáp án đúng:</div>
                <div class="answer__of-question wrong">${json.data.noiDungTuVung}</div>
            </div>
        </div>
        <div class="answer__btn-continue btn btn--danger" onclick={khoiTao()}>Tiếp tục</div>

    </div>
    </div>
</div>`;
                    $(".study_container").append(elmPopupWrongAnswer);


                }

            }
        })
    };

    function resetAnswerLoai1() {
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/Study.php"); ?>",
            method: "POST",
            data: {
                type: 'FastPracticeWordType1EndTime',
                maKhoaHoc: <?= $_GET["maKhoaHoc"] ?>,
                maBaiHoc: <?= $_GET["maBaiHoc"] ?>,
                token: $('#tokenOnTap').val(),
                practiceToken: $('#practiceToken').val()
            },
            beforeSend: function() {

                $('#loading_modal').addClass("loading--open");
            },
            success: function(response) {
                let json = $.parseJSON(response);
                $('#loading_modal').removeClass("loading--open");
                $("#thongbao").empty().append(json.message);
                khoiTao();
                return;
                if (json.status === "success") {

                    let elmPopupRightAnswer = `<div class="check_exit" id="study__footer_popup"><div class="study__footer_popup background-right">
    <div class="study__footer_popup-container">
        <div class="answer__container">
            <img src="<?= BASE_URL("/") ?>/assets/img/answer_right.svg" alt="" class="answer__flase-img">
            <div class="answer__right">
                <div class="answer__right-title right">Chính xác:</div>
                <div class="answer__of-question right">${json.data.noiDungTuVung}</div>
            </div>
        </div>
        <div class="answer__btn-continue btn"  onclick={khoiTao()}>Tiếp tục</div>

    </div>
    </div>
</div>`;
                    $(".study_container").append(elmPopupRightAnswer);

                } else {
                    let elmPopupWrongAnswer = `<div class="check_exit" id="study__footer_popup"><div class="study__footer_popup background-wrong">
    <div class="study__footer_popup-container">
        <div class="answer__container">
            <img src="<?= BASE_URL("/") ?>/assets/img/answer_flase.svg" alt="" class="answer__flase-img">
            <div class="answer__right">
                <div class="answer__right-title wrong">Đáp án đúng:</div>
                <div class="answer__of-question wrong">${json.data.noiDungTuVung}</div>
            </div>
        </div>
        <div class="answer__btn-continue btn btn--danger" onclick={khoiTao()}>Tiếp tục</div>

    </div>
    </div>
</div>`;
                    $(".study_container").append(elmPopupWrongAnswer);


                }

            }
        })
    };


    $(document).ready(function() {
        khoiTao();


    });
</script>