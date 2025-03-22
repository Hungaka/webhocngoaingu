<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
require_once(__DIR__ . "/../../vendor/google-api/vendor/autoload.php");


$title = 'Quên mật khẩu | ' . $Database->site("TenWeb");
require_once(__DIR__ . "/../../public/client/header.php");

$checkUser = null;
if (isset($_GET["token"])) {
    $tokenResetPassword = encrypt_decrypt("decrypt", check_string($_GET["token"]));
    if ($tokenResetPassword) {
        $checkUser = $Database->get_row("select * from nguoidung where TokenKhoiPhucMatKhau = '" . $tokenResetPassword . "' ");
        if ($checkUser <= 0) {
            echo "Token không hợp lệ";
            exit;
        }
          $timeSendToken = new DateTime($checkUser["ThoiGianTokenKhoiPhucMatKhau"]);
    $timeSendToken = $timeSendToken->getTimestamp();
    $now = new DateTime();
    $now = $now->getTimestamp();
    if ($now - $timeSendToken >= 10 * 60) {
        echo "Token đã hết hạn sử dụng, vui lòng gửi lại token khác";
        die();
    }
    }
}


?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/login.css");
    ?><?= include_once(__DIR__ . "/../../assets/css/main.css");
        ?>
</style>
<div class="header">
    <div class="grid wide">
        <div class="header_wrap">
            <a href="<?= BASE_URL("/") ?>">
                <h2 class="header__name"><?= $Database->site("TenWeb") ?></h2>
            </a>
            <div class="nav">
                <a href="" class="nav__course">Các khóa học</a>
                <a href="./Auth/DangNhap" class="nav__statr btn">Bắt đầu học</a>
            </div>
        </div>
    </div>
</div>
<div class="container" style="
    margin: 150px auto;
">
    <div class="grid wide">
        <form class="form" action="" id="form">
            <div class="form__title">Quên mật khẩu</div>
            <?php
            if ($checkUser) {
            ?>
                <input type="text" placeholder="Tên đăng nhập hoặc email" class="form__account" value="<?= $checkUser["TaiKhoan"] ?>" disabled>
                <div class="form__password">
                    <input type="password" class="input_password" placeholder="Mật khẩu" id="password">
                    <div id="show">Show</div>
                </div>
                <button type="submit" id="btnUpdatePassword" class="form__login btn">Cập nhật mật khẩu</button>
            <?php

            } else {
            ?>
                <input type="text" placeholder="Tên đăng nhập hoặc email" class="form__account" id="account">
                <button type="submit" id="btnForgotPassword" class="form__login btn">Lấy lại mật khẩu</button>
            <?php
            }
            ?>

        </form>
    </div>
</div>
<?php
if ($checkUser) {
?>
    <script type="text/javascript">
        $("#btnUpdatePassword").on("click", function() {
            $.ajax({
                url: "<?= BASE_URL("assets/ajaxs/Auth.php"); ?>",
                method: "POST",
                data: {
                    type: 'updatePassword',
                    password: $("#password").val().trim(),
                    token: '<?= isset($_GET["token"]) ?  $_GET["token"] : "" ?>'
                },
                beforeSend: function() {
                    $('#btnUpdatePassword').html('Đang xử lý').addClass("disabled");
                    $('#loading_modal').addClass("loading--open");
                },
                success: function(response) {
                    $("#thongbao").html(response);
                    $('#btnUpdatePassword').html('Cập nhật mật khẩu').removeClass("disabled");
                    $('#loading_modal').removeClass("loading--open");

                }
            });
        });
    </script>
<?php
}
?>
<script src="<?= BASE_URL("/") ?>/assets/javascript/show-password.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#form").submit(function(e) {
            e.preventDefault();
        });
    });
    let intervalTimeSendMail = null;
    $("#btnForgotPassword").on("click", function() {
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/Auth.php"); ?>",
            method: "POST",
            data: {
                type: 'forgotPassword',
                account: $("#account").val().trim(),
            },
            beforeSend: function() {
                $('#btnForgotPassword').html('Đang xử lý').addClass("disabled");
                $('#loading_modal').addClass("loading--open");
            },
            success: function(response) {
                let json = $.parseJSON(response);
                $("#thongbao").html(json.message);
                $('#btnForgotPassword').html('Lấy lại mật khẩu').removeClass("disabled");
                $('#loading_modal').removeClass("loading--open");

                if (json.status === "success") {
                    let timeReset = 60;
                    intervalTimeSendMail = setInterval(() => {
                        if (timeReset <= 0) {
                            $('#btnForgotPassword').html('Lấy lại mật khẩu').removeClass("disabled");
                            clearInterval(intervalTimeSendMail);
                            return;
                        }
                        timeReset--;
                        $('#btnForgotPassword').html(`Lấy lại mật khẩu ${timeReset}s`).addClass("disabled");

                    }, 1000)
                }


            }
        });
    });
</script>
<?php
require_once(__DIR__ . "/../../public/client/footer.php"); ?>