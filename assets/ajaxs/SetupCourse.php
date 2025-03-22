<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");




if (empty($_POST['type'])) {
    msg_error2('Dữ liệu không tồn tại');
}

if ($_POST['type'] == 'GetCourses') {
    $result = array();
    foreach ($Database->get_list(" SELECT * FROM `khoahoc`") as $row) {
        array_push($result, $row);
    }
    return die(json_encode($result));
}
if ($_POST['type'] == 'GetTargetStudy') {
    $result = array();
    foreach ($Database->get_list(" SELECT * FROM `muctieuhoctap`") as $row) {
        array_push($result, $row);
    }
    return die(json_encode($result));
}
if ($_POST['type'] == 'CreateTarget') {
    if (!isset($_SESSION["account"])) {
        msg_error2("Bạn chưa đăng nhập vào hệ thống");
    }
    $makhoahoc = check_string($_POST['MaKhoaHoc']);
    $mamuctieu = check_string($_POST['MaMucTieu']);

    if (empty($makhoahoc)) {
        msg_error2("Bạn chưa chọn khóa học");
    }
    if (empty($mamuctieu)) {
        msg_error2("Bạn chưa chọn mục tiêu học tập");
    }
    $row = $Database->get_row(" SELECT * FROM `khoahoc` WHERE `MaKhoaHoc` = '$makhoahoc' ");
    if (!$row) {
        msg_error2("Khóa học không tồn tại");
    }
    $row = $Database->get_row(" SELECT * FROM `muctieuhoctap` WHERE `MaMucTieu` = '$mamuctieu' ");
    if (!$row) {
        msg_error2("Mục tiêu học tập không tồn tại");
    }
    $row = $Database->get_row(" SELECT * FROM `dangkykhoahoc` WHERE `MaKhoaHoc` = '$makhoahoc' AND `TaiKhoan` = '" . $_SESSION['account'] . "' ");
    if ($row) {
        msg_error2("Bạn đã đăng ký khóa học này rồi");
    }
    $createKhoaHoc = $Database->insert("dangkykhoahoc", [
        'TaiKhoan' => $_SESSION["account"],
        'MaKhoaHoc' => $makhoahoc
    ]);
    $updateKhoaHoc = $Database->query("UPDATE `khoahoc` SET `SoHocVien` = `SoHocVien` + 1 WHERE `MaKhoaHoc` = '" . $makhoahoc . "' ");
    $updateNguoiDung = $Database->update("nguoidung", [
        'MaMucTieu' => $mamuctieu
    ], " `TaiKhoan` = '" . $_SESSION["account"] . "' ");
    if ($createKhoaHoc && $updateNguoiDung && $updateKhoaHoc) {
        msg_success('Thiết lập thành công', BASE_URL('Page/Home'), 1000);
    } else {
        msg_error2("Xảy ra lỗi hệ thống! Vui lòng thử lại sau");
    }
}
