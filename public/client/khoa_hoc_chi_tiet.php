<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Khóa học chi tiết | ' . $Database->site("TenWeb") . '';
$locationPage = 'khoahoc';
$META_TITLE = "5Fs Group - Khóa học chi tiết";
$META_IMAGE = "https://i.imgur.com/vIoahub.png";
$META_DESCRIPTION = "5Fs Group - Khóa học chi tiết";
$META_SITE = BASE_URL("Page/KhoaHoc");
require_once(__DIR__ . "/../../public/client/header.php");

checkLogin();
if (isset($_GET['maKhoaHoc'])) {
    $khoaHoc = $Database->get_row("SELECT * FROM `khoahoc` WHERE `MaKhoaHoc` = '" . check_string($_GET['maKhoaHoc']) . "' and TrangThaiKhoaHoc = 1  ");
    if ($khoaHoc <= 0) {
        return die('<script type="text/javascript">
    setTimeout(function(){ location.href = "' . BASE_URL('Page/KhoaHoc') . '" }, 0);
    </script>
    ');
    }
}
$checkDangKy = $Database->get_row("SELECT * FROM dangkykhoahoc WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' AND `MaKhoaHoc` = '" . $khoaHoc["MaKhoaHoc"] . "' ") > 0;
$soHocVien = $Database->num_rows("SELECT * FROM dangkykhoahoc WHERE MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "'  ");
?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/course_page.css");
    ?>
</style>

<?php
if ($checkDangKy) {
?>

    <!-- Modal hướng dẫn học tập -->
    <div class="modal" id="modal-guide-course">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="modal-content-body">
                <div class="modal-header__text">
                    Hướng dẫn học tập
                    <div class="modal-close modal-close-btn" id="modal-guide-course-close" aria-label="close">
                    </div>
                </div>
                <div class="modal-content-body__text">
                    Bước 1: Truy cập vào bài cần học:
                </div>
                <img src="https://i.imgur.com/MesUgYY.png" />
                <div class="modal-content-body__text">
                    Bước 2: Nhấn vào nút "Học tập" để bắt đầu học:
                </div>
                <img src="https://i.imgur.com/zgjWP06.png" />

            </div>

        </div>
    </div>


    <!-- Modal xóa khóa học -->
    <div class="modal" id="modal-delete-course">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="modal-content-body">
                <div class="modal-header__text">
                    Xác nhận hủy đăng ký khóa học
                    <div class="modal-close modal-close-btn" aria-label="close">
                    </div>
                </div>
                <div class="modal-content-body__text">
                    Bạn muốn hủy đăng ký khóa học này không?
                </div>
                <div class="btn btn--primary" id="btnConfirmDeleteCourse">
                    Xác nhận
                </div>
            </div>

        </div>
    </div>
    <!-- Modal đánh giá khóa học -->
    <div class="modal" id="modal-rating-course">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="modal-content-body">
                <div class="modal-header__text">
                    Đánh giá khóa học
                    <div class="modal-close modal-close-btn" aria-label="close">
                    </div>
                </div>
                <div class="modal-content-body__text">
                    Chọn cảm xúc mà bạn muốn thể hiện
                </div>
                <ul class="modal-course-english__comment-list-icon">
                    <li class="modal-course-english__comment-item-icon" data-rating-course="1">
                        <img src="<?= BASE_URL("/") ?>/assets/img/Like-icon.svg" alt="" class="modal-course-english__comment-item-icon-img">
                    </li>
                    <li class="modal-course-english__comment-item-icon" data-rating-course="2">
                        <img src="<?= BASE_URL("/") ?>/assets/img/Heart-icon.svg" alt="" class="modal-course-english__comment-item-icon-img">
                    </li>
                    <li class="modal-course-english__comment-item-icon" data-rating-course="3">
                        <img src="<?= BASE_URL("/") ?>/assets/img/Smile-icon.svg" alt="" class="modal-course-english__comment-item-icon-img">
                    </li>
                    <li class="modal-course-english__comment-item-icon" data-rating-course="4">
                        <img src="<?= BASE_URL("/") ?>/assets/img/Sad-icon.svg" alt="" class="modal-course-english__comment-item-icon-img">
                    </li>
                    <li class="modal-course-english__comment-item-icon" data-rating-course="5">
                        <img src="<?= BASE_URL("/") ?>/assets/img/Angry-icon.svg" alt="" class="modal-course-english__comment-item-icon-img">
                    </li>
                </ul>
                <textarea id="rating_content" class="modal-course-english__comment-write" placeholder="Nhập nội dung"></textarea>
                <div class="btn btn--primary" id="btnRatingCourse">
                    Xác nhận
                </div>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <!-- Modal đăng ký khóa học -->
    <div class="modal" id="modal-register-course-<?= $khoaHoc["MaKhoaHoc"] ?>">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="modal-content-body">
                <div class="modal-header__text">
                    Xác nhận đăng ký khóa học <?= $khoaHoc["TenKhoaHoc"] ?> </div>
                <div class="modal-close modal-close-btn" aria-label="close">
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
<?php

}
?>
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
                            <li class="is-active"><a href="#"><?= $khoaHoc["TenKhoaHoc"] ?></a></li>

                        </ul>
                    </nav>

                    <div class="course-detail__wrap-content">
                        <div class="course-detail__plan card">
                            <div class="course-detail__plan-header">
                                <img src=<?= $khoaHoc["LinkAnh"] ?> alt="" class="course-detail__plan-header-img">
                                <div class="course-detail__plan-header-content">
                                    <div class="course-detail__plan-header-title">
                                        <?= $khoaHoc["TenKhoaHoc"] ?>
                                    </div>
                                    <div class="course-detail__plan-header-text">
                                        <?= $khoaHoc["NoiDung"] ?>
                                    </div>
                                </div>
                            </div>
                            <div class="course-detail__plan-content">
                                <div class="course-detail__plan-tick">
                                    <div class="course-detail__plan-tick-content">
                                        <img src="<?= BASE_URL("/") ?>/assets/img/practice.svg" alt="" class="course-detail__plan-tick-img">
                                        <span class="course-detail__plan-tick-text"><?= $soHocVien ?> học viên</span>
                                    </div>
                                    <div class="course-detail__plan-tick-content">
                                        <img src="<?= BASE_URL("/") ?>/assets/img/plan-tick-admin.svg" alt="" class="course-detail__plan-tick-img">
                                        <span class="course-detail__plan-tick-text">Người tạo: <a href="<?= BASE_URL("profile.php/" . $khoaHoc["NguoiTao"] . "/") ?>"><?= $Database->get_row("SELECT * FROM nguoidung WHERE TaiKhoan = '" . $khoaHoc["NguoiTao"] . "' ")["TenHienThi"] ?></a></span>
                                    </div>
                                </div>
                                <div class="course-detail__plan-wrap-btn">
                                    <?php
                                    if ($checkDangKy) {
                                    ?>
                                        <div class="course-detail__plan-btn btn btn--no_active course-detail__plan-btn--no-active">Đã đăng kí</div>
                                        <div class="course-detail__plan-btn btn btn--primary js-modal-trigger" data-target="modal-delete-course">Hủy đăng kí</div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="course-detail__plan-btn btn btn--primary js-modal-trigger" data-target="modal-register-course-<?= $khoaHoc["MaKhoaHoc"] ?>">Đăng kí</div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="course-detail__footer">
                            <div class="grid">
                                <div class="course-detail__content-container">
                                    <?php

                                    foreach ($Database->get_list(" SELECT * FROM baihoc WHERE MaKhoaHoc = '" . $khoaHoc["MaKhoaHoc"] . "' and TrangThaiBaiHoc = 1 ") as $baihoc) {
                                        $danhSachTuVungTheoBaiHoc = $Database->get_row("SELECT COUNT(*) AS SoLuongTuVungBaiHoc FROM tuvung WHERE MaBaiHoc = '" . $baihoc["MaBaiHoc"] . "' AND MaKhoaHoc = '" . $baihoc["MaKhoaHoc"] . "' and TrangThaiTuVung = 1 ")["SoLuongTuVungBaiHoc"];
                                        $danhSachTuVungDaHocTheoBaiHoc = $Database->get_row("SELECT COUNT(*) AS SoLuongTuVungDaHoc FROM hoctuvung A inner join tuvung B on A.MaTuVung = B.MaTuVung and A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and B.TrangThaiTuVung = 1 and A.MaBaiHoc = '" . $baihoc["MaBaiHoc"] . "' AND A.MaKhoaHoc = '" . $baihoc["MaKhoaHoc"] . "' AND A.TaiKhoan = '" . $_SESSION["account"] . "' ")["SoLuongTuVungDaHoc"];
                                    ?>
                                        <div class="stage card" title="Đã hoàn thành: <?= $danhSachTuVungDaHocTheoBaiHoc  . '/' . $danhSachTuVungTheoBaiHoc ?>">
                                            <div class="stage__background-img">
                                                <img src="<?= BASE_URL("/") ?>/assets/img/book_list.svg" alt="" class="stage__img">
                                                <div class="stage__index-background"><span class="stage__index"><?= $baihoc["MaBaiHoc"] ?></span></div>
                                                <div class="stage__learned <?= $checkDangKy && $danhSachTuVungTheoBaiHoc == $danhSachTuVungDaHocTheoBaiHoc  ? "" : "stage__learned--no-active" ?>">
                                                    <img src="<?= BASE_URL("/") ?>/assets/img/learned-list.svg" alt="" class="stage__learned-img">
                                                </div>
                                            </div>
                                            <div class="stage__title"><a href=<?= BASE_URL("Page/BaiHoc" . "/" . $khoaHoc["MaKhoaHoc"] . "/" . $baihoc["MaBaiHoc"]) ?>><?= $baihoc["TenBaiHoc"] ?></a></div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>

                        <div class="course-english__comment">
                            <input type="hidden" id="currentPage" value="1">
                            <div class="page__title">Các đánh giá</div>
                            <?php
                            if ($checkDangKy) {
                            ?>

                                <div class="course-english__comment-btn btn btn--primary js-modal-trigger" data-target="modal-rating-course">Đánh giá khóa học</div>
                            <?php
                            }
                            ?>
                            <div class="course-english__comment-best-time card">
                                <div class="course-english__comment-old-time" data-sort-rating="asc">Cũ nhất</div>
                                <div class="course-english__comment-old-time" data-sort-rating="desc">Mới nhất</div>
                            </div>
                            <div id="noResultRatingList" style="font-size: 1.8rem; margin-top: 32px; justify-content: center; display: flex"><span class="course-english__comment-item-paragraph">
                                    Không có đánh giá nào
                                </span>
                            </div>

                            <div class="course-english__comment-list" id="course-english__comment-list">
                            </div>
                            <div class="course-english__comment-btn-download-more btn btn--primary" id="btnLoadMoreRatingList" onclick="getListRating('loadmore')">Tải thêm</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <script>
            const RATING_COURSE = {
                ratingCourse: 0,
                sortTimeBy: "desc",
                itemsPerPage: 10,
                currentPage: 1,
            }

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

            function initialRatingSortButton() {
                const nodeList = document.querySelectorAll(".course-english__comment-old-time");
                nodeList.forEach(e => {
                    const getSortTimeKey = e.getAttribute("data-sort-rating");
                    if (getSortTimeKey === RATING_COURSE.sortTimeBy) {
                        e.classList.add("comment--active");
                        getListRating();
                    } else {
                        e.classList.remove("comment--active")
                    }
                    e.addEventListener("click", () => {
                        if (!$(e).hasClass("comment--active")) {
                            nodeList.forEach(e => {
                                e.classList.remove("comment--active")
                            })
                            e.classList.add("comment--active");
                            RATING_COURSE.sortTimeBy = getSortTimeKey;
                            $("#currentPage").val(1);
                            getListRating();
                        }


                    })
                })
            }

            function transformRatingIcon(id) {
                let result = "";
                if (id == 1) {
                    result = "Like-icon.svg";
                } else if (id == 2) {
                    result = "Heart-icon.svg";

                } else if (id == 3) {
                    result = "Smile-icon.svg";

                } else if (id == 4) {
                    result = "Sad-icon.svg";

                } else if (id == 5) {
                    result = "Angry-icon.svg";

                }
                return result;
            }

            function getListRating(type = "sort") {
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/Course.php"); ?>",
                    method: "POST",
                    data: {
                        type: 'getListRating',
                        maKhoaHoc: <?= $khoaHoc["MaKhoaHoc"] ?>,
                        sortTimeBy: RATING_COURSE.sortTimeBy,
                        itemsPerPage: RATING_COURSE.itemsPerPage,
                        currentPage: $("#currentPage").val(),
                    },
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");

                    },
                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        $('#noResultRatingList').show();
                        let json = $.parseJSON(response);
                        if (json.status === "error") {
                            $('#thongbao').append(json.message);

                        } else if (json.status === "success") {

                            let str = "";

                            if (json.data && json.data.map((item) => {
                                    str += ` <div class="course-english__comment-item">
                <img src="${item.AnhDaiDien}" alt="" class="course-english__comment-item-avata">
                <div class="course-english__comment-item-content card">
                    <div class="course-english__comment-item-content-wrap">
                        <div class="course-english__comment-item-name"><a href="<?= BASE_URL("profile.php") ?>/${item.TaiKhoan}/">${item.TenHienThi}</a></div>
                        <div class="course-english__comment-item-paragraph">${item.NoiDungDanhGia}</div>
                        <div class="course-english__comment-item-time">${item.ThoiGian}</div>
                    </div>
                    <img src="<?= BASE_URL("/") ?>/assets/img/${transformRatingIcon(item.Rating)}" alt="" class="course-english__comment-item-content-icon">
                </div>
            </div>`;
                                }));
                            if (json.results === 0 && $("#currentPage").val() == 1) {
                                $('#noResultRatingList').show();
                            } else {
                                $('#noResultRatingList').hide();
                            }

                            if (type == "sort") {
                                $("#course-english__comment-list").empty().append(str);
                                $("#currentPage").val(1);
                            } else if (type == "loadmore") {
                                $("#course-english__comment-list").append(str);
                            }
                            if (json.results < RATING_COURSE.itemsPerPage) {
                                $('#btnLoadMoreRatingList').hide();
                            } else {
                                const currentPage = $("#currentPage").val();
                                $("#currentPage").val(parseInt(currentPage) + 1);
                                $('#btnLoadMoreRatingList').show();
                            }
                        }
                    }
                });
            }

            function initialRatingReaction() {
                const nodeList = document.querySelectorAll(".modal-course-english__comment-item-icon");
                for (let i = 0; i < nodeList.length; i++) {
                    nodeList[i].addEventListener("click", () => {
                        const courseRatingID = nodeList[i].getAttribute("data-rating-course");
                        if (!$(nodeList[i]).hasClass("modal-course-english__comment-item-icon--chose")) {
                            nodeList.forEach(e => {
                                e.classList.remove("modal-course-english__comment-item-icon--chose")
                            })
                            nodeList[i].classList.add("modal-course-english__comment-item-icon--chose");
                            RATING_COURSE.ratingCourse = courseRatingID;
                        }
                    })
                }

            }

            function handleRatingCourse() {
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/Course.php"); ?>",
                    method: "POST",
                    data: {
                        type: 'ratingCourse',
                        rating: RATING_COURSE.ratingCourse,
                        maKhoaHoc: <?= $khoaHoc["MaKhoaHoc"] ?>,
                        noiDung: $("#rating_content").val().trim()
                    },
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");
                    },
                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        $("#thongbao").empty().append(response);

                    }
                });
            }

            function handleDeleteCourse() {
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/Course.php"); ?>",
                    method: "POST",
                    data: {
                        type: 'deleteCourse',
                        maKhoaHoc: <?= $khoaHoc["MaKhoaHoc"] ?>,
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
            $(document).ready(function() {


                initialRatingReaction();
                initialRatingSortButton();

                $("#btnConfirmDeleteCourse").click(function() {
                    handleDeleteCourse();
                })
                $("#btnRatingCourse").click(function() {
                    handleRatingCourse();
                })
                const getStatusViewGuideCourse = localStorage.getItem("statusViewGuideCourse") ? JSON.parse(localStorage.getItem("statusViewGuideCourse")) : false;
                if (!getStatusViewGuideCourse) {
                    document.getElementById('modal-guide-course').style.display = 'flex';
                    localStorage.setItem("statusViewGuideCourse", true);
                }
                $("#modal-guide-course-close").click(function() {
                    $("#modal-guide-course").hide();
                })


            });
        </script>

        <?php
        include_once(__DIR__ . "/../../public/client/menu_right.php");
        include_once(__DIR__ . "/../../public/client/navigation_mobile.php");
        ?>
        <?php
        require_once(__DIR__ . "/../../public/client/footer.php"); ?>