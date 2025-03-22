<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Danh sách khóa học | ' . $Database->site("TenWeb") . '';
$locationPage = 'khoahoc';
$META_TITLE = "5Fs Group - Danh sách khóa học";
$META_IMAGE = "https://i.imgur.com/WlasPkc.png";
$META_DESCRIPTION = "5Fs Group - Danh sách khóa học";
$META_SITE = BASE_URL("Page/KhoaHoc");
require_once(__DIR__ . "/../../public/client/header.php");
checkLogin();
?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/course_page.css");
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
            <div class="list-course">
                <div class="list-course__content js-list-course__content">
                    <div class="page__title">Danh sách các khóa học:</div>
                    <div class="list_course-detail__content-container">
                        <?php
                        foreach ($Database->get_list(" SELECT * FROM khoahoc ") as $row) {
                            $checkDangKy = $Database->get_row("SELECT * FROM dangkykhoahoc WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' AND `MaKhoaHoc` = '" . $row["MaKhoaHoc"] . "' ");
                            $soHocVien = $Database->num_rows("SELECT * FROM dangkykhoahoc WHERE MaKhoaHoc = '" . $row["MaKhoaHoc"] . "'  ");
                        ?>
                            <div class="list-course__plan card">
                                <img src=<?= $row["LinkAnh"] ?> alt="" class="list-course__plan-img js-english__img">
                                <div class="list-course__plan-content">
                                    <div class="list-course__plan-heading"><a href="<?= BASE_URL('Page/KhoaHoc/' . $row["MaKhoaHoc"] . '') ?>"><?= $row["TenKhoaHoc"] ?></a></div>
                                    <div class="list-course__plan-tick">
                                        <img src="<?= BASE_URL("/") ?>/assets/img/practice.svg" alt="" class="list-course__plan-tick-img">
                                        <span class="list-course__plan-tick-number"><?= $soHocVien ?> học viên</span>
                                    </div>
                                    <?php
                                    if ($checkDangKy > 0) {
                                    ?>
                                        <div class="list-course__plan-btn btn btn--no_active list-course__plan-btn--no-active">Đã đăng kí</div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="modal" id="modal-register-course-<?= $row["MaKhoaHoc"] ?>">
                                            <div class="modal-background"></div>
                                            <div class="modal-content">
                                                <div class="modal-content-body">
                                                    <div class="modal-header__text">
                                                        Xác nhận đăng ký khóa học <?= $row["TenKhoaHoc"] ?> </div>
                                                    <div class="modal-close modal-close-btn" aria-label="close">
                                                    </div>
                                                    <div class="modal-content-body__text">
                                                        Bạn muốn đăng ký khóa học này không?
                                                    </div>

                                                    <div class="btn btn--primary" onclick='handleRegisterCourse(<?= $row["MaKhoaHoc"] ?>)'>
                                                        Xác nhận
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="list-course__plan-btn btn js-modal-trigger" data-target="modal-register-course-<?= $row["MaKhoaHoc"] ?>">Đăng kí</div>
                                    <?php
                                    }
                                    ?>

                                </div>
                            </div>

                        <?php
                        }
                        ?>

                    </div>

                </div>

            </div>

        </div>
        <script>
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
            $(document).ready(function() {});
        </script>
        <?php
        include_once(__DIR__ . "/../../public/client/menu_right.php");
        include_once(__DIR__ . "/../../public/client/navigation_mobile.php");
        ?>
        <?php
        require_once(__DIR__ . "/../../public/client/footer.php"); ?>