<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Kích hoạt email tài khoản';

require_once(__DIR__ . "/../../public/client/header.php");

if (!isset($_GET["account"]) || !isset($_GET["token"])) {
    echo 'Vui lòng nhập dữ liệu';
    die();
}
if (empty($_GET["account"]) || empty($_GET["token"])) {
    echo 'Vui lòng nhập dữ liệu';
    die();
}

$checkTaiKhoan = $Database->get_row("select * from nguoidung where TaiKhoan = '" . check_string($_GET["account"]) . "'");
if ($checkTaiKhoan <= 0) {
    echo "Tài khoản không tồn tại";
} else
if ($checkTaiKhoan["KichHoatEmail"] == 1) {
    echo "Tài khoản đã kích hoạt email rồi";
} else if (!isset($checkTaiKhoan["TokenKichHoatEmail"])) {
    echo "Token không hợp lệ";
} else {
    $timeSendToken = new DateTime($checkTaiKhoan["ThoiGianTokenKichHoatEmail"]);
    $timeSendToken = $timeSendToken->getTimestamp();
    $now = new DateTime();
    $now = $now->getTimestamp();
    if ($now - $timeSendToken >= 10 * 60) {
        echo "Token đã hết hạn sử dụng, vui lòng gửi lại token khác";
        die();
    }
    if ( encrypt_decrypt("decrypt", check_string($_GET["token"])) != ($checkTaiKhoan["TokenKichHoatEmail"])) {
        echo "Token không hợp lệ";
        die();
    }
    $Database->update("nguoidung", [
        'KichHoatEmail' => 1,
        'TokenKichHoatEmail' => NULL,
    ], "TaiKhoan = '" . check_string($_GET["account"]) . "'");
    echo "Kích hoạt email thành công";
}
