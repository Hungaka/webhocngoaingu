<style>
    <?= include_once(__DIR__ . "/../../assets/css/responsive.css");
    ?>
</style>
<?php
$checkQuyenHan = $Database->get_row("select * from nguoidung where TaiKhoan = '" . $_SESSION["account"] . "' ");
?>
<div class="nav_mobile">
    <ul class="nav__list">
        <li class="nav__item">
            <a href="<?= BASE_URL("Page/Home") ?>" class="nav__item-link <?= ($locationPage == "home_page") ? "nav__item-link--chose" : ""  ?>"><img src="<?= BASE_URL("") ?>/assets/img/home.svg" alt="" class="nav__item-img"><span class="nav__item-text">TRANG CHỦ</span></a>
        </li>
        <li class="nav__item">
            <a href="<?= BASE_URL("Page/KhoaHoc") ?>" class="nav__item-link <?= ($locationPage == "khoahoc") ? "nav__item-link--chose" : ""  ?>"><img src="<?= BASE_URL("") ?>/assets/img/course.svg" alt="" class="nav__item-img"><span class="nav__item-text">KHÓA HỌC</span></a>
        </li>
        <li class="nav__item">
            <a href="<?= BASE_URL("Page/TrangCaNhan/" . $_SESSION["account"]) ?>/" class="nav__item-link <?= ($locationPage == "profile_page") ? "nav__item-link--chose" : ""  ?>"><img src="<?= BASE_URL("") ?>/assets/img/file.svg" alt="" class="nav__item-img"><span class="nav__item-text">HỒ SƠ</span></a>
        </li>
        <li class="nav__item">
            <a href="<?= BASE_URL("Page/CaiDat/") ?>" class="nav__item-link <?= ($locationPage == "setting_page") ? "nav__item-link--chose" : ""  ?>"><img src="<?= BASE_URL("") ?>/assets/img/setting.svg" alt="" class="nav__item-img"><span class="nav__item-text">CÀI ĐẶT</span></a>
        </li>
        <?php
        if ($checkQuyenHan["MaQuyenHan"] >= 2) {
        ?>

            <li class="nav__item">
                <a href="<?= BASE_URL("admin/home") ?>" class="nav__item-link <?= ($locationPage == "admin_page") ? "nav__item-link--chose" : ""  ?>"><img src="https://i.imgur.com/sA8BWyZ.png" alt="" class="nav__item-img"><span class="nav__item-text">ADMIN</span></a>
            </li>
        <?php

        }
        ?>
        <li class="nav__item">
            <a href="<?= BASE_URL("Auth/DangXuat") ?>" class="nav__item-link"><img src="<?= BASE_URL("/") ?>/assets/img/logout.svg" alt="" class="nav__item-img"><span class="nav__item-text">ĐĂNG XUẤT</span></a>
        </li>
    </ul>

</div>
<script src="<?= BASE_URL("/") ?>/assets/javascript/modal.js"></script>