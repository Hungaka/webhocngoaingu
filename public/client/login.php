<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
require_once(__DIR__ . "/../../vendor/google-api/vendor/autoload.php");
$title = 'Đăng nhập tài khoản | ' . $Database->site("TenWeb");
$META_TITLE = "5Fs Group - Đăng nhập tài khoản";
$META_IMAGE = "https://i.imgur.com/LMiRDR5.png";
$META_DESCRIPTION = "5Fs Group - Đăng nhập tài khoản";
$META_SITE = BASE_URL("Auth/DangNhap");
require_once(__DIR__ . "/../../public/client/header.php");
if (isset($_SESSION["account"])) {
    $row = $Database->get_row("SELECT * FROM `dangkykhoahoc` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "'  ");
    if (!$row) {
        return die('<script type="text/javascript">
            setTimeout(function(){ location.href = "' . BASE_URL('Page/KhoiTaoTaiKhoan') . '" }, 0);
            </script>
            ');
    }
    return die('<script type="text/javascript">
        setTimeout(function(){ location.href = "' . BASE_URL('') . '" }, 0);
        </script>
        ');
}

// Login with Google

$client = new Google_Client();
$client->setClientId(GOOGLE_APP_ID);
$client->setClientSecret(GOOGLE_APP_SECRET);
$client->setRedirectUri(GOOGLE_APP_CALLBACK_URL);
$client->addScope("email");
$client->addScope("profile");


// Login with Facebook
$fb = new Facebook\Facebook([
    'app_id' => FACEBOOK_APP_ID,
    'app_secret' => FACEBOOK_APP_SECRET,
    'default_graph_version' => 'v2.5',
]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // optional
$loginFacebookUrl = $helper->getLoginUrl(FACEBOOK_APP_CALLBACK_URL, $permissions);

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
                <a href="<?= BASE_URL("Auth/DangNhap") ?>" class="nav__statr btn">Bắt đầu học</a>
            </div>
        </div>
    </div>
</div>
<div class="container" style="
    margin: 150px auto;
">
    <div class="grid wide">
        <form class="form" action="" id="form">
            <div class="form__title">Đăng nhập</div>
            <div id="thongbao"></div>
            <input type="text" placeholder="Tên đăng nhập" class="form__account" id="account">
            <div class="form__password">
                <input type="password" class="input_password" placeholder="Mật khẩu" id="password" />
                <div id="show">Show</div>
            </div>

            <a href="<?= BASE_URL("Auth/QuenMatKhau") ?>" class="form__forget-password">Quên mật khẩu?</a>
            <button type="submit" id="btnLogin" class="form__login btn">ĐĂNG NHẬP</button>
            <span class="form__separate-text">HOẶC</span>
            <div class="form__separate"></div>
            <div class="wrap-social">
                <a href="<?= $loginFacebookUrl  ?>" class="social__item"><img src="<?= BASE_URL("/") ?>/assets/img/facebook.svg" alt="" class="social__item-img"><span class="social__item-text">FACEBOOK</span></a>
                <a href=<?= $client->createAuthUrl() ?> class="social__item"><img src="<?= BASE_URL("/") ?>/assets/img/google.svg" alt="" class="social__item-img"><span class="social__item-text">GOOGLE</span></a>
            </div>
            <div class="form__not-account">
                Chưa có tài khoản? <a href="<?= BASE_URL("Auth/DangKy") ?>" class="form__not-account-link">Đăng kí ngay</a>
            </div>


        </form>
    </div>
</div>
<script src="<?= BASE_URL("/") ?>/assets/javascript/show-password.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#form").submit(function(e) {
            e.preventDefault();
        });
    });
    $("#btnLogin").on("click", function() {
        $.ajax({
            url: "<?= BASE_URL("assets/ajaxs/Auth.php"); ?>",
            method: "POST",
            data: {
                type: 'login',
                account: $("#account").val().trim(),
                password: $("#password").val().trim()
            },
            beforeSend: function() {
                $('#btnLogin').html('Đang xử lý').addClass("disabled");
                $('#loading_modal').addClass("loading--open");
            },
            success: function(response) {
                $("#thongbao").html(response);
                $('#btnLogin').html('Đăng nhập').removeClass("disabled");
                $('#loading_modal').removeClass("loading--open");
            }
        });
    });
</script>
<?php
require_once(__DIR__ . "/../../public/client/footer.php"); ?>