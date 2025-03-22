<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Khởi tạo tài khoản | ' . $Database->site("TenWeb") . '';
require_once(__DIR__ . "/../../public/client/header.php");

if (!isset($_SESSION["account"])) {
    return die('<script type="text/javascript">
        setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangNhap') . '" }, 0);
        </script>
        ');
} else {
    $row = $Database->get_row("SELECT * FROM `dangkykhoahoc` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "'  ");
    if ($row) {
        return die('<script type="text/javascript">
            setTimeout(function(){ location.href = "' . BASE_URL('') . '" }, 0);
            </script>
            ');
    }
}
?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/choose_course.css");
    ?>
</style>
<div class="header">
    <div class="grid wide">
        <div class="header_wrap">
            <a href="<?= BASE_URL("/") ?>">
                <h2 class="header__name"><?= $Database->site("TenWeb") ?></h2>
            </a>
            <div class="nav">

                <a href="<?= BASE_URL("Auth/DangXuat") ?>" class="nav__statr btn">Đăng xuất</a>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="grid wide">
        <div id="thongbao"></div>
        <div class="content">
            <div class="content__heading">
                <div class="content__switch" id="step-1">
                    <div class="content__switch-number">1</div>
                    <div class="content__switch-text">Chọn khóa học</div>
                </div>
                <div class="content__switch no-active" id="step-2">
                    <div class="content__switch-number">2</div>
                    <div class="content__switch-text">Đặt mục tiêu</div>
                </div>
                <div class="content__switch no-active" id="step-3">
                    <div class="content__switch-number">3</div>
                    <div class="content__switch-text">Bắt đầu học</div>
                </div>
            </div>
            <div id="loading_process"></div>
            <div class="content__choose" id="selection">
                <div class="content__choose-heading">Tôi muốn học:</div>
                <div class="content__choose-wrap">

                </div>
                <div class="content__choose-continue btn no-active" id="continue-step1">
                    Tiếp tục
                </div>
            </div>
            <div class="content__target" id="target">
                <div class="content__target-heading">Đặt mục tiêu học tập hằng ngày</div>
                <div class="content__target-list">
                </div>
                <div class="content__target-node">
                    Bạn có thể thay đổi mục tiêu này trong cài đặt hồ sợ
                </div>
                <div class="content__choose-continue btn no-active" id="continue-step2">
                    Tiếp tục
                </div>
            </div>
            <div class="content__loading" id="loading_setup">
                <div class="content__loading-heading">
                    Đang khởi tạo...
                </div>
                <img src="<?= BASE_URL("/") ?>/assets/img/Loading.gif" alt="" class="content__loading-img">
            </div>
        </div>
    </div>
</div>
<!-- <script src="./assets/javascript/choose__laguage.js"></script> -->
<script type="text/javascript">
    const KHOAHOC = [];
    const MUCTIEUHOCTAP = [];
    const USER_CHOOSE = {
        MaKhoaHoc: null,
        MaMucTieu: null,
    };
    $(document).ready(function() {
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/SetupCourse.php"); ?>",
            method: "POST",
            data: {
                type: 'GetCourses',
            },
            success: function(response) {
                var json = $.parseJSON(response);
                let str = "";
                json.forEach(element => {
                    KHOAHOC.push(element)
                    str += `<div class="content__choose-language card" data-course-id="${element.MaKhoaHoc}">
                        <div class="content__choose-language-background">
                            <img src=${element.LinkAnh} alt="" class="content__choose-language-img">
                        </div>
                        <div class="content__choose-text">${element.TenKhoaHoc}</div>
                    </div>`;
                });
                $(".content__choose-wrap").append(str);
                const nodeList = document.querySelectorAll(".content__choose-language");
                for (let i = 0; i < nodeList.length; i++) {
                    nodeList[i].addEventListener("click", () => {
                        const courseID = nodeList[i].getAttribute("data-course-id");
                        if (!$(nodeList[i]).hasClass("choosed")) {
                            nodeList.forEach(e => {
                                e.classList.remove("choosed")
                            })
                            nodeList[i].classList.add("choosed");
                            USER_CHOOSE.MaKhoaHoc = courseID;
                            $("#continue-step1").removeClass("no-active").addClass("btn-continue btn");
                        }
                    })
                }

            }
        });

        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/SetupCourse.php"); ?>",
            method: "POST",
            data: {
                type: 'GetTargetStudy',
            },
            success: function(response) {
                let json = $.parseJSON(response);
                let str = "";
                json.forEach(element => {
                    MUCTIEUHOCTAP.push(element)
                    str += `<div id="target__choose-${element.MaMucTieu}" data-course-target-id="${element.MaMucTieu}" class="content__target-item"><span class="content__target-item-text">${element.TenMucTieu}</span><span class="content__target-item-word">${element.NoiDungMucTieu}</span></div>`;
                });
                $(".content__target-list").append(str);
                const nodeList = document.querySelectorAll(".content__target-item");
                for (let i = 0; i < nodeList.length; i++) {
                    nodeList[i].addEventListener("click", () => {
                        const courseTargetID = nodeList[i].getAttribute("data-course-target-id");
                        if (!$(nodeList[i]).hasClass("target-choose")) {
                            nodeList.forEach(e => {
                                e.classList.remove("target-choose")
                            })
                            nodeList[i].classList.add("target-choose");
                            USER_CHOOSE.MaMucTieu = courseTargetID;

                            $("#continue-step2").removeClass("no-active").addClass("btn-continue bttn");

                        }
                        console.log(USER_CHOOSE);
                    })
                }


            }
        });
    });


    $(document).ready(function() {
        $("#continue-step1").click(function() {
            if ($(this).hasClass("btn-continue")) {
                console.log(USER_CHOOSE);
                $("#selection").css("display", "none");
                $("#step-1").addClass("no-active");
                $("#step-2").removeClass("no-active");
                $("#target").css("display", "flex");
            }
        })
        $("#continue-step2").click(function() {
            if ($(this).hasClass("btn-continue")) {
                console.log(USER_CHOOSE);
                $("#target").css("display", "none");
                $("#step-2").addClass("no-active");
                $("#step-3").removeClass("no-active");
                $("#loading_setup").css("display", "flex");

                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/SetupCourse.php"); ?>",
                    method: "POST",
                    data: {
                        type: 'CreateTarget',
                        MaKhoaHoc: USER_CHOOSE.MaKhoaHoc,
                        MaMucTieu: USER_CHOOSE.MaMucTieu,
                    },
                    success: function(response) {
                        $("#thongbao").html(response);


                    }
                });
            }
        })
    })
</script>
<?php
require_once(__DIR__ . "/../../public/client/footer.php"); ?>