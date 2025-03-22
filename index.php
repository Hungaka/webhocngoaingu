<?php
require_once(__DIR__ . "/configs/config.php");
require_once(__DIR__ . "/configs/function.php");
$title = 'Trang chủ nền tảng học ngoại ngữ online | ' . $Database->site("TenWeb");
$META_TITLE = "5Fs Group - Nền tảng học ngoại ngữ online";
$META_IMAGE = "https://i.imgur.com/Ldhl3hK.png";
$META_DESCRIPTION = "5Fs Group - Nền tảng học ngoại ngữ online";
$META_SITE = BASE_URL("");
require_once(__DIR__ . "/public/client/header.php");

$soLuongKhoaHoc = $Database->get_row("select count(*) as SoLuong from khoahoc ")["SoLuong"];
$soLuongHocVien = $Database->get_row("select count(*) as SoLuong from nguoidung ")["SoLuong"];

?>

<style>
    <?= include_once(__DIR__ . "/assets/css/main.css");
    ?>
</style>
<div class="header">
    <div class="grid wide">
        <div class="header_wrap">
            <a href="<?= BASE_URL("/") ?>">
                <h2 class="header__name"><?= $Database->site("TenWeb") ?></h2>
            </a>
            <div class="nav">
                <a href="#" class="nav__course">Các khóa học </a>
                <?php
                if (isset($_SESSION["account"])) {
                ?>
                    <a href="<?= BASE_URL("Page/Home") ?>" class="nav__statr btn">Bắt đầu học</a>
                <?php
                } else {
                ?>
                    <a href="<?= BASE_URL("Auth/DangNhap") ?>" class="nav__statr btn">Bắt đầu học</a>

                <?php
                }
                ?>

            </div>
        </div>
    </div>
</div>
<div class="slider">
    <div class="grid wide">
        <div class="slider-wrap">
            <div class="slider__content">
                <h1 class="slider__content-heading">Học

                    <div style="line-height:0" class="headline-icon"><img src="https://i.imgur.com/HE3eJGR.png" loading="lazy" class="LazyImage__Img-sc-12k26ab-0 eVDYkS"></div>
                    <div style="line-height:0;top: -40px;right: -40px;" class="headline-icon"><img src="https://i.imgur.com/GF5UgJs.png" loading="lazy" class="LazyImage__Img-sc-12k26ab-0 eVDYkS"></div>
                    <div style="line-height:0; top: -40px;right: -40px;" class="headline-icon"><img src="https://i.imgur.com/fmKnI8E.png" loading="lazy" class="LazyImage__Img-sc-12k26ab-0 eVDYkS"></div>

                    <span class="slider__content-heading--color">ngoại ngữ</span>
                    và cải thiện kĩ năng của bản thân bạn
                </h1>
                <p class="slider__content-text">Ngoại ngữ hiện đang rất quan trọng trong cuộc sống giao tiếp hằng ngày,
                    khi đi du lịch, trong công việc</p>
                <a href="<?= BASE_URL("/") ?>/Auth/DangNhap" class="slider__content-start btn">Bắt đầu</a>
            </div>
            <div class="slider__img">
                <img src="https://i.imgur.com/dGnCisl.png" loading="lazy" alt="<?= $Database->site("TenWeb") . ' - Nền tảng học ngoại ngữ online' ?>" class="image">
                <img src="https://i.imgur.com/ezm9DcC.png" loading="lazy" alt="<?= $Database->site("TenWeb") . ' - Nền tảng học ngoại ngữ online' ?>" class="image_1">
                <img src="https://i.imgur.com/aVPB2Ll.png" loading="lazy" alt="<?= $Database->site("TenWeb") . ' - Nền tảng học ngoại ngữ online' ?>" class="image_2">
                <img src="https://i.imgur.com/nci9dng.png" loading="lazy" alt="<?= $Database->site("TenWeb") . ' - Nền tảng học ngoại ngữ online' ?>" class="image_3">

            </div>
        </div>
    </div>
    <div class="course">
        <ul class="course__list">
            <li class="course__item course_khoahoc">
                <div class="course__item-number"><?= $soLuongKhoaHoc  ?></div>
                <div class="course__item-text">KHÓA HỌC</div>
            </li>
            <li class="course__item nation course__item--separate">
                <img src="<?= BASE_URL("/") ?>/assets/img/America.png" alt="<?= $Database->site("TenWeb") . ' - Khóa học tiếng Anh' ?>" class="course__item-img">
                <div class="course__item-text">TIẾNG ANH</div>
            </li>
            <li class="course__item nation">
                <img src="<?= BASE_URL("/") ?>/assets/img/Japan.png" alt="<?= $Database->site("TenWeb") . ' - Khóa học tiếng Nhật' ?>" class="course__item-img">
                <div class="course__item-text">TIẾNG NHẬT</div>
            </li>
            <li class="course__item course__item--separate course_hocvien">
                <div class="course__item-number"><?= $soLuongHocVien  ?></div>
                <div class="course__item-text">HỌC VIÊN</div>
            </li>
        </ul>
    </div>
</div>

<div class="reason">
    <div class="grid wide">
        <h1 class="introduce__heading">Tại sao nên sử dụng <?= $Database->site("TenWeb") ?>?</h1>
        <div class="why_use_container">

            <div class="reason__content">
                <div class="reason__warp-img " style="background-color: #A2D6E5;">
                    <img class="reason__img" src="https://i.imgur.com/Q9GYNuV.png" alt="<?= $Database->site("TenWeb") . ' - Các kỹ thuật ghi nhớ được khoa học chứng minh' ?>">
                </div>
                <p class="reason__text">Các kỹ thuật ghi nhớ được khoa học chứng minh</p>
            </div>


            <div class="reason__content">
                <div class="reason__warp-img " style="background-color: #D0C9E7;">
                    <img class="reason__img" src="https://i.imgur.com/x2hi4Fq.png" alt="<?= $Database->site("TenWeb") . ' - Học nhanh hơn gấp hai lần so với trên lớp' ?>">
                </div>
                <p class="reason__text">Học nhanh hơn gấp hai lần so với trên lớp</p>
            </div>


            <div class="reason__content">
                <div class="reason__warp-img " style="background-color: #8AD6C2;">
                    <img class="reason__img" src="https://i.imgur.com/9ifba7t.png" alt="<?= $Database->site("TenWeb") . ' - Học bằng cách đắm mình trong ngôn ngữ, như thế bạn đang sống ở đó vậy' ?>">
                </div>
                <p class="reason__text">Học bằng cách đắm mình trong ngôn ngữ, như thế bạn đang sống ở đó vậy.</p>
            </div>


            <div class="reason__content">
                <div class="reason__warp-img " style="background-color: #F6C2C3;">
                    <img class="reason__img" src="https://i.imgur.com/lqi2N7I.png" alt="<?= $Database->site("TenWeb") . ' - Bao quát mọi thứ từ những kiến thức thiết yếu cho những chuyến du lịch của bạn tới những mục tiêu dài hạn hơn' ?>">
                </div>
                <p class="reason__text">Bao quát mọi thứ từ những kiến thức thiết yếu cho những chuyến du lịch của
                    bạn tới những mục tiêu dài hạn hơn</p>

            </div>
        </div>
    </div>
</div>
<div class="introduce">
    <div class="grid wide">
        <h1 class="introduce__heading">Học miễn phí, mọi lúc mọi nơi</h1>
        <div class="introduce_platform">

            <div class="introduce__content">
                <div class="introduce__wrap-img">
                    <img src="<?= BASE_URL("/") ?>/assets/img/menu.png" alt="<?= $Database->site("TenWeb") . ' - Lộ trình học tập được xây dựng cho riêng bạn' ?>" class="introduce__content-img">
                </div>
                <h3 class="introduce__content-heading">Lộ trình học tập</h3>
                <p class="introduce__content-text">Lộ trình học tập được xây dựng cho riêng bạn</p>
            </div>


            <div class="introduce__content">
                <div class="introduce__wrap-img">
                    <img src="<?= BASE_URL("/") ?>/assets/img/book.png" alt="<?= $Database->site("TenWeb") . ' - Danh sách từ vựng phong phú và thiết thực' ?>" class="introduce__content-img">
                </div>
                <h3 class="introduce__content-heading">Bổ sung từ vựng</h3>
                <p class="introduce__content-text">Danh sách từ vựng phong phú và thiết thực</p>
            </div>


            <div class="introduce__content">
                <div class="introduce__wrap-img">
                    <img src="<?= BASE_URL("/") ?>/assets/img/free.png" alt="<?= $Database->site("TenWeb") . ' - Nền tảng miễn phí, bất cứ ai cũng có thể học, nó là miễn phí' ?>" class="introduce__content-img">
                </div>
                <h3 class="introduce__content-heading">Nền tảng miễn phí</h3>
                <p class="introduce__content-text">Bất cứ ai cũng có thể học, nó là miễn phí</p>

            </div>
        </div>
    </div>
</div>
<div style="
 
    margin-top: 90px;
    padding: 40px 0px;
">
    <div class="introduce_website">
        <div class="introduce_website-left">

            <h1 class="introduce__heading">Nhiều từ vựng được dịch nghĩa chính xác nhất</h1>
            <p class="support-browser__content">Khác với các từ điển thông thường. Chúng tôi xây dựng từ điển của mình theo hướng người Việt giúp mang lại từ vựng có nghĩa chính xác nhất theo định nghĩa tiếng Việt.</p>
        </div>
        <div class="introduce_website-right">
            <img src="https://i.imgur.com/aDm5Pgc.png" alt="<?= $Database->site("TenWeb") . ' - Nhiều từ vựng được dịch nghĩa chính xác nhất' ?>" />
        </div>
    </div>
</div>
<div style="
 
    margin-top: 90px;
    padding: 40px 0px;
">
    <div class="introduce_website">
        <div class="introduce_website-right">
            <img src="https://i.imgur.com/RjfROrU.png" alt="<?= $Database->site("TenWeb") . ' - Ví dụ cụ thể cho từng từ' ?>" />
        </div>
        <div class="introduce_website-left">
            <h1 class="introduce__heading">Ví dụ cụ thể cho từng từ</h1>
            <p class="support-browser__content">Với mỗi từ tiếng Anh sẽ có 1-2 ví dụ tương ứng, giúp các bạn nắm được cách mà từ này được sử dụng trong câu ứng với ngữ cảnh thực tế.</p>
        </div>

    </div>
</div>
<div class="comment-slider">
    <h1 class="introduce__heading">Học viên nói gì về <?= $Database->site("TenWeb") ?></h1>
    <div class="grid wide">
        <div class="comment-slider__list">
            <?php

            foreach ($Database->get_list(" select * from danhgiakhoahoc A inner join nguoidung B on A.TaiKhoan = B.TaiKhoan order by A.ThoiGian desc limit 9") as $danhGiaKhoaHoc) {

            ?>
                <div class="comment-item">
                    <div class="comment-item__wrap">
                        <div class="comment-left">
                            <img src="<?= $danhGiaKhoaHoc["AnhDaiDien"] ?>" alt="<?= $danhGiaKhoaHoc["TenHienThi"] ?>" class="comment-left__img">
                            <p class="comment-left__text"><?= $danhGiaKhoaHoc["NoiDungDanhGia"] ?></p>
                            <div class="comment-left__name"><?= $danhGiaKhoaHoc["TenHienThi"] ?></div>
                            <div class="comment-item__balloon--left"></div>
                        </div>
                        <div class="comment-right">
                            <div class="comment-right-person">
                                <img src="<?= BASE_URL("/") ?>/assets/img/Trung.png" alt="Học viên của <?= $Database->site("TenWeb") ?>" class="comment-right-person__img">
                                <div class="comment-right-person__wrap-content">
                                    <p class="comment-right-person__text">Ứng dụng hay và học tiếng anh rất tốt, tôi thích
                                        nó! Arigathank</p>
                                    <div class="comment-right-person__name">Nguyễn Đức Trung</div>
                                </div>
                                <div class="comment-item__balloon--right"></div>
                            </div>
                            <div class="comment-right-person">
                                <img src="<?= BASE_URL("/") ?>/assets/img/Quynh.png" alt="Học viên của <?= $Database->site("TenWeb") ?>" class="comment-right-person__img">
                                <div class="comment-right-person__wrap-content">
                                    <p class="comment-right-person__text">Nhờ có trang web này, tôi đã có thể đi xuất ngoại
                                        với mức lương ngàn đô</p>
                                    <div class="comment-right-person__name">Lê Thanh Quỳnh</div>
                                </div>
                                <div class="comment-item__balloon--right"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>



        </div>
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js">
        </script>
        <script src="<?= BASE_URL("/") ?>/assets/javascript/comment-slider.js?t=<?= rand(0, 99999) ?>"></script>
    </div>
</div>

<div style="
    background-color: #FAFCFF;
    margin-top: 90px;
    padding: 40px 0px;
">
    <div class="support-browser">
        <h1 class="introduce__heading">Các trình duyệt hỗ trợ</h1>
        <p class="support-browser__content">Nền tảng học ngoại ngữ <?= $Database->site("TenWeb") ?> hỗ trợ có thể tương tác với tất cả các trình duyệt máy tính để bàn và thiết bị di động hiện đại, và đã được kiểm tra kỹ lưỡng để đạt được hiệu suất và độ tin cậy trong phạm vi trình duyệt rộng nhất có thể.</p>
        <div class="support-browser__list-browser">
            <div class="support-browser__item">
                <img src="https://i.imgur.com/Dp6UTr8.png" alt="<?= $Database->site("TenWeb") ?> - Học trên Firefox" class="support-browser__item-img">
                <div class="support-browser__item-name">Firefox</div>
            </div>
            <div class="support-browser__item">
                <img src="<?= BASE_URL("/") ?>/assets/img/chrome.svg" alt="<?= $Database->site("TenWeb") ?> - Học trên Chrome" class="support-browser__item-img">
                <div class="support-browser__item-name">Chrome</div>
            </div>
            <div class="support-browser__item">
                <img src="<?= BASE_URL("/") ?>/assets/img/safari.svg" alt="<?= $Database->site("TenWeb") ?> - Học trên Safari" class="support-browser__item-img">
                <div class="support-browser__item-name">Safari</div>
            </div>
            <div class="support-browser__item">
                <img src="<?= BASE_URL("/") ?>/assets/img/opera.svg" alt="<?= $Database->site("TenWeb") ?> - Học trên Opera" class="support-browser__item-img">
                <div class="support-browser__item-name">Opera</div>
            </div>
        </div>
    </div>
</div>
<div class="info">
    <div class="info-wrap">
        <h1 class="introduce__heading" style="color: #fff">Nhận thông tin mới nhất từ chúng tôi</h1>
        <div class="info-wrap-form">
            <input type="email" placeholder="Nhập email của bạn vào đây" class="info__input">
            <div class="btn">
                Đăng ký

            </div>
        </div>
    </div>
</div>

<script>
    anime({
        targets: '.headline-icon',
        scale: 1.2,
        direction: 'alternate',
        loop: true,
        easing: 'easeInOutSine'
    });

    anime({
        targets: '.image_1',

        direction: 'alternate',
        loop: true,
        keyframes: [{
                translateY: -40,
                scale: 1.5,
            },
            {
                translateY: 40,
                scale: 1,
            },

        ],
        duration: 4000,
        easing: 'easeInOutSine'
    });
    anime({
        targets: '.image_2',
        scale: 1.2,

        keyframes: [{
                translateX: -40
            },

            {
                translateX: 40
            },

        ],
        duration: 4000,
        direction: 'alternate',
        loop: true,
        easing: 'easeInOutSine'
    });
    anime({
        targets: '.image_3',

        keyframes: [{
                opacity: 0,
                scale: 1.4,
                translateY: -40
            },

            {
                opacity: 1,
                scale: 1,
                translateY: 0
            },

        ],
        duration: 4000,
        direction: 'alternate',
        loop: true,
        easing: 'easeInOutSine'
    });
</script>



<?php
require_once(__DIR__ . "/public/client/footer_about.php");

require_once(__DIR__ . "/public/client/footer.php");

?>