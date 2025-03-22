<?php
$taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
if (!$taikhoan) {
    return die('<script type="text/javascript">
    setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangNhap') . '" }, 0);
    </script>
    ');
}

?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/menu_right.css");
    ?>
</style>

<div class="menu_right-container">

    <div class="statistical">
        <div class="interact">
            <div class="interact__question-active">
                <div class="interact__question-title">HOẠT ĐỘNG LÀ GÌ?</div>
                <div class="interact__question-text">
                    Là khi bạn tiến hành tương tác với hệ thống: đăng nhập, học tập,.. thì sẽ được lưu vào hoạt động của bạn.
                </div>
                <img src="<?= BASE_URL("/") ?>/assets/img/question.svg" alt="" class="interact__question-img">
            </div>
            <div class="interact__question-congratulations">
                <div class="interact__question-title">BXH là gì?</div>
                <div class="interact__question-text">
                    Bảng xếp hạng là nơi để bạn xứng danh với toàn thể học viên của <?= $Database->site("TenWeb") ?> khi đứng tên trên đó
                </div>
                <img src="<?= BASE_URL("/") ?>/assets/img/congratulations.svg" alt="" class="interact__question-img">
            </div>
        </div>

    </div>
</div>