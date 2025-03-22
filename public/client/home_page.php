<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Trang chủ học tập | ' . $Database->site('TenWeb') . '';
$locationPage = 'home_page';
$META_TITLE = "5Fs Group - Trang chủ học tập";
$META_IMAGE = "https://i.imgur.com/TxJhptu.png";
$META_DESCRIPTION = "5Fs Group - Trang chủ học tập";
$META_SITE = BASE_URL("Page/Home");
require_once(__DIR__ . "/../../public/client/header.php");
checkLogin();
?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/home_page.css");
    ?>
</style>
<div class="grid">
    <div class="row main-page">
        <div class="nav-container">
            <?php
            include_once(__DIR__ . "/../../public/client/navigation.php");
            ?>
        </div>

        <div class="main_content-container">
            <div class="my-course">
                <div class="page__title">Khóa học của tôi:</div>
                <?php

                foreach ($Database->get_list(" SELECT * FROM dangkykhoahoc INNER JOIN khoahoc ON dangkykhoahoc.MaKhoaHoc = khoahoc.MaKhoaHoc AND dangkykhoahoc.TaiKhoan = '" . $_SESSION['account'] . "' and khoahoc.TrangThaiKhoaHoc = 1  ") as $row) {
                    $soTuDaHoc = $Database->num_rows("SELECT * FROM hoctuvung A inner join tuvung B on A.MaTuVung = B.MaTuVung and A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and A.TaiKhoan = '" . $_SESSION["account"] . "' AND A.MaKhoaHoc = '" . $row["MaKhoaHoc"] . "' and B.TrangThaiTuVung = 1 ");
                    $tongSoTuVung = $Database->num_rows("SELECT * FROM tuvung WHERE MaKhoaHoc = '" . $row["MaKhoaHoc"] . "' and TrangThaiTuVung = 1 ");
                    $tongSoTuVungKho = $Database->num_rows("SELECT * FROM hoctuvung A inner join tuvung B on A.MaTuVung = B.MaTuVung and A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and A.TaiKhoan = '" . $_SESSION["account"] . "' AND A.MaKhoaHoc = '" . $row["MaKhoaHoc"] . "' and B.TrangThaiTuVung = 1 and A.TuKho = 1");
                    $soHocVien = $Database->num_rows("SELECT * FROM dangkykhoahoc WHERE MaKhoaHoc = '" . $row["MaKhoaHoc"] . "'  ");

                    if ($tongSoTuVung == 0) {
                        $tienTrinhHoc = 0;
                    } else {
                        $tienTrinhHoc = floor(($soTuDaHoc / $tongSoTuVung) * 100);
                    }

                ?>

                    <div class="my-course__plan card">
                        <img src=<?= $row["LinkAnh"] ?> alt="" class="my-course__plan-img">
                        <div class="my-course__plan-content">
                            <div class="my-course__plan-heading">
                                <div class="my-course__plan-heading-text">
                                    <a href="<?= BASE_URL('Page/KhoaHoc/' . $row['MaKhoaHoc'] . '') ?>">
                                        <?= $row["TenKhoaHoc"] ?>
                                    </a>
                                </div>
                            </div>
                            <div class="my-course__plan-heading-sub"><span class="my-course__plan-percent">
                                    <?= $tienTrinhHoc >= 100 ? 100 : $tienTrinhHoc  ?>%
                                </span><span class="my-course__planned">Đã học
                                    <?= $soTuDaHoc ?>/<?= $tongSoTuVung ?>
                                </span></div>
                            <div class="my-course__plan-bar">
                                <div class="my-course__plan-bar-value-english <?= $row['MaKhoaHoc'] ?>" style="width: <?= $tienTrinhHoc >= 100 ? 100 : $tienTrinhHoc ?>%" title="<?= $tienTrinhHoc >= 100 ? 100 : $tienTrinhHoc ?>%"></div>
                            </div>
                            <div class="my-course__plan-tick">
                                <div class="my-course__plan-tick-box" title="Số học viên: <?= $soHocVien ?>">
                                    <img src="<?= BASE_URL("/") ?>/assets/img/practice.svg" alt="" class="my-course__plan-tick-img">
                                    <span class="my-course__plan-tick-number">
                                        <?= $soHocVien ?>
                                    </span>
                                </div>
                                <div class="my-course__plan-tick-box" title="Đã đánh dấu: <?= $tongSoTuVungKho ?> từ khó">

                                    <img src="<?= BASE_URL("/") ?>/assets/img/license.svg" alt="" class="my-course__plan-tick-img">
                                    <span class="my-course__plan-tick-number">
                                        <?= $tongSoTuVungKho ?>
                                    </span>
                                </div>

                                <a href="<?= BASE_URL('Page/KhoaHoc/' . $row['MaKhoaHoc'] . '') ?>" style="margin-left: auto;">
                                    <div class="my-course__plan-tick-btn btn">Học tập</div>
                                </a>

                            </div>
                        </div>
                    </div>

                <?php
                }
                ?>
            </div>
        </div>
        <?php
        include_once(__DIR__ . "/../../public/client/menu_right.php");
        include_once(__DIR__ . "/../../public/client/navigation_mobile.php");

        ?>
        <?php
        require_once(__DIR__ . "/../../public/client/footer.php"); ?>