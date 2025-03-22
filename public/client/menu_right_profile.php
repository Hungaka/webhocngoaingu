<?php
$taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
if (!$taikhoan) {
    return die('<script type="text/javascript">
    setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangNhap') . '" }, 0);
    </script>
    ');
}
$rand = rand(1,2);

?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/menu_right.css");
    ?>
</style>

<div class="menu_right-container">
    <div class="statistical">
        <div class="interact">
            <?php
            if ($rand == 1) {
            ?>
            <div class="interact__question-congratulations">
                <div class="interact__question-title">HOẠT ĐỘNG LÀ GÌ?</div>
                <div class="interact__question-text">
                    Là khi bạn tiến hành tương tác với hệ thống: đăng nhập, học tập,.. thì sẽ được lưu vào hoạt động của bạn.
                </div>
                <img src="<?=BASE_URL("/")?>/assets/img/question.svg" alt="" class="interact__question-img">
            </div>
            <?php
            } else if ($rand == 2) {
                ?>
                  <div class="interact__question-congratulations">
                      <div class="interact__question-title">MỤC TIÊU HỌC TẬP LÀ GÌ?</div>
                <div class="interact__question-text">
                    Là số từ đặt ra hằng ngày mà bạn phải học. Hãy cố gắng học thật đều đặn nhé.
                </div>
                <img src="<?=BASE_URL("/")?>/assets/img/question.svg" alt="" class="interact__question-img">
            </div>
                <?php
            }
            ?>
      
        </div>

    </div>
</div>