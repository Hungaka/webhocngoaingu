<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Bài học chi tiết | ' . $Database->site("TenWeb") . '';
$locationPage = 'khoahoc';
$META_TITLE = "5Fs Group - Bài học chi tiết";
$META_IMAGE = "https://i.imgur.com/3DRA8rf.png";
$META_DESCRIPTION = "5Fs Group - Bài học chi tiết";
$META_SITE = BASE_URL("Page/BaiHoc");

require_once(__DIR__ . "/../../public/client/header.php");

checkLogin();
if (isset($_GET['maKhoaHoc']) && isset($_GET['maBaiHoc'])) {
    $khoaHoc = $Database->get_row("SELECT * FROM khoahoc A inner join baihoc B on A.MaKhoaHoc = B.MaKhoaHoc and  A.MaKhoaHoc = '" . check_string($_GET['maKhoaHoc']) . "' and A.TrangThaiKhoaHoc = 1 and B.TrangThaiBaiHoc = 1 and B.MaBaiHoc = '" . check_string($_GET['maBaiHoc']) . "'");
    if ($khoaHoc <= 0) {
        return die('<script type="text/javascript">
    setTimeout(function(){ location.href = "' . BASE_URL('') . '" }, 0);
    </script>
    ');
    }
}

$checkDangKy = $Database->get_row("SELECT * FROM dangkykhoahoc WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' AND `MaKhoaHoc` = '" . $khoaHoc["MaKhoaHoc"] . "' ") > 0;
$tongTuVungTheoBaiHoc = $Database->get_row("SELECT COUNT(*) AS SoLuongTuVungBaiHoc FROM tuvung WHERE MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "' AND MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and TrangThaiTuVung = 1 ")["SoLuongTuVungBaiHoc"];
$danhSachTuVungDaHocTheoBaiHoc = $Database->get_row("SELECT COUNT(*) AS SoLuongTuVungDaHoc FROM hoctuvung WHERE MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "' AND MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' AND TaiKhoan = '" . $_SESSION["account"] . "' ")["SoLuongTuVungDaHoc"];
if ($tongTuVungTheoBaiHoc == 0) {
    $tienTrinhHocTap = 0;
} else {

    $tienTrinhHocTap = floor($danhSachTuVungDaHocTheoBaiHoc / $tongTuVungTheoBaiHoc * 100);
}
$soTuVungBoQua = $Database->num_rows("SELECT * FROM boquatuvung A inner join tuvung B on A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and A.MaTuVung = B.MaTuVung and A.TaiKhoan = '" . $_SESSION["account"] . "' AND A.MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "'  AND A.MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and B.TrangThaiTuVung = 1 ");

?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/course_page.css");
    ?><?= include_once(__DIR__ . "/../../assets/css/home_page.css");
        ?>
</style>
<div id="thongbao"></div>
<div class="grid">
    <div class="row main-page">
        <div class="nav-container">
            <?php
            include_once(__DIR__ . "/../../public/client/navigation.php");
            ?>
        </div>
        <div class="main_content-container">
            <div class="list-course">
                <div class="list-course__detail" style="display: block;">
                    <nav class="breadcrumb has-succeeds-separator page__title" aria-label="breadcrumbs">
                        <ul>
                            <li><a href="<?= BASE_URL("Page/KhoaHoc") ?>">Khóa học</a></li>
                            <li><a href="<?= BASE_URL("Page/KhoaHoc/") . $khoaHoc["MaKhoaHoc"] ?>"><?= $khoaHoc["TenKhoaHoc"] ?></a></li>
                            <li class="is-active"><a href="#"><?= $khoaHoc["TenBaiHoc"] ?></a></li>

                        </ul>
                    </nav>
                    <div class="course-detail__start" style="display: block;">
                        <div class="start__header card">
                            <div class="start__header-level">
                                <div class="start__header-level-number">Bài học <?= $khoaHoc["MaBaiHoc"] ?></div>
                                <img src="<?= BASE_URL("/") ?>/assets/img/book_list.svg" alt="" class="start__header-level-img">
                            </div>
                            <div class="start__header-content">
                                <div class="start__header-content-wrap-title">
                                    <div class="start__header-content-title"><?= $khoaHoc["TenBaiHoc"] ?></div>
                                    <?php
                                    if ($tongTuVungTheoBaiHoc == $danhSachTuVungDaHocTheoBaiHoc && $checkDangKy) {
                                    ?>
                                        <img title="Bạn đã hoàn thành bài học này" src="<?= BASE_URL("/") ?>/assets/img/learned-list.svg" alt="" class="start__header-content-learned">
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="start__header-content-bar">
                                    <div class="start__header-content-bar-value" style="width: <?= $tienTrinhHocTap >= 100 ? 100  : $tienTrinhHocTap    ?>%;"><span class="start__header-content-bar-percent"><?= $tienTrinhHocTap >= 100 ? 100  : $tienTrinhHocTap ?>%</span></div>
                                </div>
                                <div class="start__header-content-separate"></div>
                                <div class="start__header-content-wrap-btn">
                                    <?php
                                    if ($checkDangKy) {
                                        $maBaiTruoc = $khoaHoc["MaBaiHoc"] - 1;
                                        $maBaiSau = $khoaHoc["MaBaiHoc"] + 1;
                                        $checkBaiTruoc = $Database->get_row("select * from baihoc where MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and MaBaiHoc = '" . $maBaiTruoc . "' ");
                                        $checkBaiTiepTheo = $Database->get_row("select * from baihoc where MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and MaBaiHoc = '" . $maBaiSau . "' ");
                                    ?>

                                        <?php
                                        if ($checkBaiTiepTheo > 0) {
                                        ?>
                                            <a href="<?= BASE_URL("Page/BaiHoc" . "/" . $khoaHoc["MaKhoaHoc"] . "/" . $maBaiSau) ?>">

                                                <div class="start__header-content-btn btn ">Bài tiếp theo</div>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                        <div class="start__header-content-btn btn js-modal-trigger" data-target="modal-study-course">Học tập</div>
                                        <?php
                                        if ($checkBaiTruoc > 0) {
                                        ?>
                                            <a href="<?= BASE_URL("Page/BaiHoc" . "/" . $khoaHoc["MaKhoaHoc"] . "/" .  $maBaiTruoc) ?>">

                                                <div class="start__header-content-btn btn ">Bài trước</div>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="modal" id="modal-register-course-<?= $khoaHoc["MaKhoaHoc"] ?>">
                                            <div class="modal-background"></div>
                                            <div class="modal-content">
                                                <div class="modal-content-body">
                                                    <div class="modal-header__text">
                                                        Xác nhận đăng ký khóa học <?= $khoaHoc["TenKhoaHoc"] ?> </div>
                                                    <div class="modal-close modal-close-btn is-large" aria-label="close">
                                                    </div>
                                                    <div class="modal-content-body__text">
                                                        Bạn muốn đăng ký khóa học này không?
                                                    </div>

                                                    <div class="btn btn--primary" onclick='handleRegisterCourse(<?= $khoaHoc["MaKhoaHoc"] ?>)'>
                                                        Xác nhận
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="start__header-content-btn btn js-modal-trigger" data-target="modal-register-course-<?= $khoaHoc["MaKhoaHoc"] ?>">Đăng ký khóa học</div>
                                    <?php

                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="start__information_lession">
                            <div class="start__content-item-word-detail"><?= $tongTuVungTheoBaiHoc ?> từ</div>
                            <div class="start__content-item-word-detail"> Bỏ qua <?= $soTuVungBoQua ?> từ</div>


                        </div>

                        <div class="start__content">

                            <table class="start__content-table">
                                <thead>
                                    <tr>
                                        <td colspan="3">
                                            <div class="table-header">
                                                <?php
                                                if ($checkDangKy) {
                                                ?>
                                                    <div class="table-header-desc">
                                                        <div class="start__content-item-skipped">
                                                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                            <span class="start__content-item-skipped-text">Chưa học</span>
                                                        </div>
                                                        <div class="start__content-item-skipped">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                            <span class="start__content-item-skipped-text">Đã học</span>
                                                        </div>
                                                        <div class="start__content-item-ready">
                                                            <i class="fa fa-tint" aria-hidden="true"></i>
                                                            <span class="start__content-item-ready-text">Sẵn sàng ôn tập</span>
                                                        </div>
                                                        <div class="start__content-item-ready">
                                                            <i class="fa fa-bookmark" aria-hidden="true"></i>
                                                            <span class="start__content-item-ready-text">Từ khó</span>
                                                        </div>
                                                    </div>
                                                    <div class="start__content-item-skipp-btn" id="btnBoQua">
                                                        Bỏ qua
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($Database->get_list(" SELECT * FROM tuvung WHERE MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' AND MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "' and TrangThaiTuVung = 1 ") as $tuVungBaiHoc) {
                                        $checkBoQuaTuVung = $Database->get_row("SELECT * FROM boquatuvung WHERE TaiKhoan = '" . $_SESSION["account"] . "' AND MaTuVung = '" . $tuVungBaiHoc["MaTuVung"] . "' AND MaBaiHoc = '" . $tuVungBaiHoc["MaBaiHoc"] . "' AND MaKhoaHoc = '" . $tuVungBaiHoc["MaKhoaHoc"] . "' ");
                                        $checkDaHocTuVung = $Database->get_row("SELECT * FROM hoctuvung WHERE TaiKhoan = '" . $_SESSION["account"] . "' AND MaTuVung = '" . $tuVungBaiHoc["MaTuVung"] . "' AND MaBaiHoc = '" . $tuVungBaiHoc["MaBaiHoc"] . "' AND MaKhoaHoc = '" . $tuVungBaiHoc["MaKhoaHoc"] . "' ");
                                        $checkDenThoiGianOnTap = $Database->get_row("SELECT * FROM hoctuvung WHERE TaiKhoan = '" . $_SESSION["account"] . "' AND MaTuVung = '" . $tuVungBaiHoc["MaTuVung"] . "' AND MaBaiHoc = '" . $tuVungBaiHoc["MaBaiHoc"] . "' AND MaKhoaHoc = '" . $tuVungBaiHoc["MaKhoaHoc"] . "' and (ThoiGianOnTap is NULL or ThoiGianOnTap < NOW() - INTERVAL 30 minute) ");


                                    ?>
                                        <tr class="<?= $checkBoQuaTuVung > 0 ? "word skipped" : "word" ?>" data-matuvung=<?= $tuVungBaiHoc["MaTuVung"] ?>>
                                            <td>
                                                <div class="start__content-item-word-detail"><?= $tuVungBaiHoc["NoiDungTuVung"] ?></div>
                                            </td>
                                            <td>
                                                <div class="start__content-item-word-vietnam"><?= $tuVungBaiHoc["DichNghia"] ?></div>
                                            </td>

                                            <td class="option">

                                                <?php
                                                if ($checkBoQuaTuVung > 0) {
                                                ?>
                                                    Đã bỏ qua
                                                    <?php
                                                } else if ($checkDaHocTuVung > 0) {
                                                    if ($checkDenThoiGianOnTap > 0) {
                                                    ?>
                                                        <i class="fa fa-tint" aria-hidden="true" title="Đến thời gian ôn tập"></i>
                                                    <?php
                                                    } else {
                                                    ?>

                                                        <i class="fa fa-eye" aria-hidden="true" title="Đã học"></i>
                                                    <?php
                                                    }
                                                    if ($checkDaHocTuVung["TuKho"] == 1) {
                                                    ?>

                                                        <i class="fa fa-bookmark" style="padding-left: 10px;" aria-hidden="true" title="Từ khó"></i>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <i class="fa fa-eye-slash" aria-hidden="true" title="Chưa học"></i>
                                                <?php
                                                }
                                                ?>

                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <?php
        if ($checkDangKy) {
            // Lấy danh sách các từ vựng đã học đến thời gian ôn tập, nhưng không nằm trong danh sách bỏ qua
            $listDaHocChuaBoQua = $Database->get_list("select * from hoctuvung A left join boquatuvung B on A.MaTuVung = B.MaTuVung and A.MaBaiHoc = B.MaBaiHoc and A.MaKhoaHoc = B.MaKhoaHoc
        and A.TaiKhoan = B.TaiKhoan where A.TaiKhoan = '" . $_SESSION["account"] . "' and A.MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "' and A.MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and (A.ThoiGianOnTap is NULL or A.ThoiGianOnTap < NOW() - INTERVAL 30 minute) and B.TaiKhoan is NULL and B.MaTuVung is NULL and B.MaBaiHoc is NULL and B.MaKhoaHoc is NULL");

        ?>

            <div class="modal" id="modal-study-course">
                <div class="modal-background"></div>
                <div class="modal-content">
                    <div class="modal-content-body">
                        <div class="modal-header__text">
                            <?= $khoaHoc["TenKhoaHoc"] ?> > <?= $khoaHoc["TenBaiHoc"] ?>
                            <div class="modal-close modal-close-btn" aria-label="close">
                            </div>
                        </div>
                        <div class="modal-study__suggest">
                            <div class="modal-study__suggest-heading">Đề xuất cho bạn</div>
                            <?php

                            // Lấy danh sách tất cả từ vựng
                            $danhSachTuVung = $Database->get_list("select A.MaTuVung, A.MaBaiHoc, A.MaKhoaHoc from tuvung A  where A.MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and A.TrangThaiTuVung = 1 and A.MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "' ORDER BY `MaBaiHoc` ASC, `MaTuVung` ASC");
                            $danhSachTuVungDaBoQua = $Database->get_list("select A.MaTuVung, A.MaBaiHoc, A.MaKhoaHoc from boquatuvung A inner join tuvung B on A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and A.MaTuVung = B.MaTuVung and B.TrangThaiTuVung = 1 and A.MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "'  and A.MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "' and A.TaiKhoan = '" . $_SESSION['account'] . "' ORDER BY A.MaBaiHoc ASC, A.MaTuVung ASC");
                            // Lấy danh sách các từ vựng người dùng đã học
                            $danhSachTuVungDaHoc = $Database->get_list("select A.MaTuVung, A.MaBaiHoc, A.MaKhoaHoc from hoctuvung A inner join tuvung B on A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and A.MaTuVung = B.MaTuVung and B.TrangThaiTuVung = 1 and A.MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and A.MaBaiHoc = '" . $khoaHoc["MaBaiHoc"] . "' and A.TaiKhoan = '" . $_SESSION['account'] . "' order by A.MaBaiHoc asc, A.MaTuVung asc");
                            // Loại các từ đã học ra khỏi danh sách từ vựng
                            $danhSachTuVungChuaHoc = removeItemDuplicate(array_merge($danhSachTuVung, $danhSachTuVungDaBoQua, $danhSachTuVungDaHoc), "MaTuVung");

                            if (count($listDaHocChuaBoQua) > 0) {
                            ?>

                                <div class="modal-study__choose-function-wrap">
                                    <a href="<?= BASE_URL("Page/OnTap?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]") ?>" class="modal-study__choose-function-btn-link">
                                        <div class="modal-study__choose-function-btn modal-study__choose-function-btn--2">
                                            <img src="<?= BASE_URL("/") ?>/assets/img/modal-practice.svg" alt="" class="modal-study__choose-function-btn-img">
                                            <div class="modal-study__notifi-practive"><span class="modal-study__notifi-practive-number"><?= count($listDaHocChuaBoQua) ?></span></div>
                                        </div>
                                    </a>
                                    <div class="modal-study__choose-function-text">Ôn tập</div>
                                </div>
                            <?php
                            } else
                        if (count($danhSachTuVungChuaHoc) == 0) {
                            ?>
                                <div class="modal-study__choose-function-wrap">
                                    <a href="<?= BASE_URL("Page/OnSieuToc?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]") ?>" class="modal-study__choose-function-btn-link">
                                        <div class="modal-study__choose-function-btn modal-study__choose-function-btn--3">
                                            <img src="<?= BASE_URL("/") ?>/assets/img/modal-watch.svg" alt="" class="modal-study__choose-function-btn-img">
                                        </div>
                                    </a>
                                    <div class="modal-study__choose-function-text">Ôn siêu tốc</div>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="modal-study__choose-function-wrap">
                                    <a href="<?= BASE_URL("Page/Study?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]") ?>" class="modal-study__choose-function-btn-link">
                                        <div class="modal-study__choose-function-btn">
                                            <img src="<?= BASE_URL("/") ?>/assets/img/modal-plus.svg" alt="" class="modal-study__choose-function-btn-img">
                                        </div>
                                    </a>
                                    <div class="modal-study__choose-function-text">Học từ mới</div>
                                </div>

                            <?php
                            }
                            ?>
                        </div>
                        <div class="modal-study__choose-function">
                            <div class="modal-study__choose-function__heading">
                                Lựa chọn các mục sau
                            </div>

                            <div class="row">
                                <div class="col l-6">
                                    <div class="modal-study__choose-function-wrap">
                                        <a href="<?= BASE_URL("Page/Study?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]") ?>" class="modal-study__choose-function-btn-link">
                                            <div class="modal-study__choose-function-btn">
                                                <img src="<?= BASE_URL("/") ?>/assets/img/modal-plus.svg" alt="" class="modal-study__choose-function-btn-img">
                                            </div>
                                        </a>
                                        <div class="modal-study__choose-function-text">Học từ mới</div>
                                    </div>
                                </div>

                                <div class="col l-6">
                                    <div class="modal-study__choose-function-wrap">
                                        <a href="<?= BASE_URL("Page/OnTap?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]") ?>" class="modal-study__choose-function-btn-link">
                                            <div class="modal-study__choose-function-btn modal-study__choose-function-btn--2">
                                                <img src="<?= BASE_URL("/") ?>/assets/img/modal-practice.svg" alt="" class="modal-study__choose-function-btn-img">
                                                <div class="modal-study__notifi-practive"><span class="modal-study__notifi-practive-number"><?= count($listDaHocChuaBoQua) ?></span></div>
                                            </div>
                                        </a>
                                        <div class="modal-study__choose-function-text">Ôn tập</div>
                                    </div>
                                </div>
                                <div class="col l-6">
                                    <div class="modal-study__choose-function-wrap">
                                        <a href="<?= BASE_URL("Page/OnSieuToc?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]") ?>" class="modal-study__choose-function-btn-link">
                                            <div class="modal-study__choose-function-btn modal-study__choose-function-btn--3">
                                                <img src="<?= BASE_URL("/") ?>/assets/img/modal-watch.svg" alt="" class="modal-study__choose-function-btn-img">
                                            </div>
                                        </a>
                                        <div class="modal-study__choose-function-text">Ôn siêu tốc</div>
                                    </div>
                                </div>
                                <div class="col l-6">
                                    <div class="modal-study__choose-function-wrap">
                                        <a href="<?= BASE_URL("Page/OnTuKho?maKhoaHoc=$khoaHoc[MaKhoaHoc]&maBaiHoc=$khoaHoc[MaBaiHoc]") ?>">
                                            <div class="modal-study__choose-function-btn modal-study__choose-function-btn--4">
                                                <img src="<?= BASE_URL("/") ?>/assets/img/modal-degree.svg" alt="" class="modal-study__choose-function-btn-img">
                                            </div>
                                        </a>
                                        <div class="modal-study__choose-function-text">Ôn từ khó</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>

        <script>
            const CURRENT_STATUS_WORDS = [];
            let checkOpenSkipMenu = false;

            function handleRegisterCourse(maKhoaHoc) {
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/Course.php"); ?>",
                    method: "POST",
                    data: {
                        type: 'registerCourse',
                        maKhoaHoc: maKhoaHoc,
                    },
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");
                    },
                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        $("#thongbao").empty().append(response);
                        setTimeout(() => {

                            window.location.reload();
                        }, 1000)
                    }
                });
            }

            function chonHetBoQuaTuVung() {
                if (checkOpenSkipMenu) {
                    const allWordsOption = document.querySelectorAll(".word .option input[type=checkbox]");
                    allWordsOption.forEach((e) => {
                        e.checked = true;
                    })
                }
            }

            function boChonHetBoQuaTuVung() {
                if (checkOpenSkipMenu) {
                    const allWordsOption = document.querySelectorAll(".word .option input[type=checkbox]");
                    allWordsOption.forEach((e) => {
                        e.checked = false;
                    })
                }
            }

            function clickSaveSkipButton() {
                const arrayRequest = [];
                const allWordsOption = document.querySelectorAll(".word .option input[type=checkbox]");
                allWordsOption.forEach((e) => {
                    const item = {
                        maTuVung: e.getAttribute("id"),
                        type: e.checked
                    };
                    arrayRequest.push(item);
                })
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/Course.php"); ?>",
                    method: "POST",
                    data: {
                        type: 'updateSkipWord',
                        maKhoaHoc: <?= $khoaHoc["MaKhoaHoc"] ?>,
                        maBaiHoc: <?= $khoaHoc["MaBaiHoc"] ?>,
                        data: arrayRequest
                    },
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");
                    },
                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        $("#thongbao").empty().append(response);
                        setTimeout(() => {

                            window.location.reload();
                        }, 1000)
                    }
                });
            }

            function openSkipMenu() {
                if (checkOpenSkipMenu) {
                    closeSkipMenu();
                    return;
                }
                checkOpenSkipMenu = true;
                const allWords = document.querySelectorAll(".word");
                allWords.forEach(element => {
                    CURRENT_STATUS_WORDS[element.getAttribute("data-matuvung")] = element.children[2].innerHTML.trim();
                    element.children[2].innerHTML = `<input type="checkbox" id="${element.getAttribute("data-matuvung")}">`;
                    if (element.classList.contains("skipped")) {
                        element.children[2].childNodes[0].checked = true;
                    }

                });
                $("#btnBoQua").text("Thoát");
                const str = `<div class="box_option" onclick={clickSaveSkipButton()}>
                <div class="btn btn--primary">
                Lưu lại
            </div></div>`;
                const str2 = `       <div class="box_option" style="display: flex; flex-direction: column; gap: 10px">
                                <p class="start__content-item-word-detail" style="font-size: 2rem">Bỏ qua từ</P>
                                <p style="font-size: 1.8rem">Chọn các ô dưới đây để bỏ qua / giữ lại từ, rồi chọn nút Lưu phía dưới. Những từ bị bỏ qua sẽ không xuất hiện trong tiết học nào nữa.</p>
                                <div style="display: flex;
    gap: 10px;
    justify-content: flex-end;">
                                    <div class="start__content-item-skipp-btn" id="btnChonHet" onclick={chonHetBoQuaTuVung()}>
                                        Chọn hết
                                    </div>
                                    <div class="start__content-item-skipp-btn" id="btnBoChonHet" onclick={boChonHetBoQuaTuVung()}>
                                        Bỏ chọn
                                    </div>
                                </div>
                            </div>`;

                $(".start__content").prepend(str2);
                $(".start__content").append(str);
            }

            function closeSkipMenu() {
                checkOpenSkipMenu = false;
                const allWords = document.querySelectorAll(".word");
                allWords.forEach(element => {
                    element.children[2].innerHTML = CURRENT_STATUS_WORDS[element.getAttribute("data-matuvung")];
                });
                $("#btnBoQua").text("Bỏ qua");
                $(".box_option").remove();
            }


            $(document).ready(function() {
                $("#btnBoQua").click(function() {
                    openSkipMenu();
                })


            });
        </script>
        <?php
        include_once(__DIR__ . "/../../public/client/menu_right.php");
        include_once(__DIR__ . "/../../public/client/navigation_mobile.php");
        ?>
        <?php
        require_once(__DIR__ . "/../../public/client/footer.php"); ?>