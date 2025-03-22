<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");




if (empty($_POST['type'])) {
    msg_error2('Dữ liệu không tồn tại');
}
if (!isset($_SESSION["account"])) {
    msg_error2('Vui lòng đăng nhập vào hệ thống');
}

checkAccountExist();

if ($_POST['type'] == 'updateSkipWord') {
    try {

        $data = ($_POST['data']);
        $maKhoaHoc = check_string($_POST["maKhoaHoc"]);
        $maBaiHoc = check_string($_POST["maBaiHoc"]);
        if (empty($maKhoaHoc) || empty($data) || empty($maBaiHoc)) {
            msg_error2('Vui lòng điền đủ dữ liệu');
        }
        $checkBaiHoc = $Database->get_row("SELECT * FROM baihoc A inner join khoahoc B WHERE A.MaKhoaHoc = '" . $maKhoaHoc . "' AND A.MaBaiHoc = '" . $maBaiHoc . "' and A.MaKhoaHoc = B.MaKhoaHoc");
        if ($checkBaiHoc <= 0) {
            msg_error2('Bài học không tồn tại');
        }
        $keys = array_keys($data);
        $mangLuuTruTuVungHuyBoQua = array();
        $mangLuuTruTuVungBoQua = array();
        for ($i = 0; $i < count($data); $i++) {
            $maTuVung = '';
            foreach ($data[$keys[$i]] as $key => $value) {
                if ($key == 'maTuVung') {
                    $maTuVung = $value;
                } else if ($key == 'type') {
                    if ($value == 'false') {
                        // hủy bỏ qua từ vựng
                        $checkValid = $Database->get_row("select * from boquatuvung where MaKhoaHoc = '" . $maKhoaHoc . "' and MaBaiHoc = '" . $maBaiHoc . "' and MaTuVung = '" . $maTuVung . "' and TaiKhoan = '" . $_SESSION['account'] . "' ");
                        if ($checkValid) {
                            $Database->query("DELETE FROM boquatuvung where MaKhoaHoc = '" . $maKhoaHoc . "' and MaBaiHoc = '" . $maBaiHoc . "' and MaTuVung = '" . $maTuVung . "' and TaiKhoan = '" . $_SESSION['account'] . "' ");
                            $getTuVung = $Database->get_row("select * from tuvung where MaTuVung = '" . $maTuVung . "' and MaBaiHoc = '" . $maBaiHoc . "' and MaKhoaHoc = '" . $maKhoaHoc . "' ");
                            array_push($mangLuuTruTuVungHuyBoQua, $getTuVung["NoiDungTuVung"]);
                        }
                    } else  if ($value == 'true') {
                        $checkValid = $Database->get_row("select * from boquatuvung where MaKhoaHoc = '" . $maKhoaHoc . "' and MaBaiHoc = '" . $maBaiHoc . "' and MaTuVung = '" . $maTuVung . "' and TaiKhoan = '" . $_SESSION['account'] . "' ");
                        if (!$checkValid) {
                            // bỏ qua từ vựng
                            $Database->insert("boquatuvung", [
                                'MaKhoaHoc' => $maKhoaHoc,
                                'MaBaiHoc' => $maBaiHoc,
                                'MaTuVung' => $maTuVung,
                                'TaiKhoan' => $_SESSION["account"]
                            ]);


                            $getTuVung = $Database->get_row("select * from tuvung where MaTuVung = '" . $maTuVung . "' and MaBaiHoc = '" . $maBaiHoc . "' and MaKhoaHoc = '" . $maKhoaHoc . "' ");
                            array_push($mangLuuTruTuVungBoQua, $getTuVung["NoiDungTuVung"]);
                        }
                    }
                }
            }
        }
        // lưu lại hoạt động
        if (count($mangLuuTruTuVungBoQua) != 0) {
            $HoatDong->insertHoatDong([
                'MaLoaiHoatDong' => 2,
                'TenHoatDong' => 'Bỏ qua từ vựng',
                'NoiDung' => 'Bỏ qua từ vựng "' . implode(", ", $mangLuuTruTuVungBoQua) . '" thuộc bài học "' . $checkBaiHoc["TenBaiHoc"] . '" của khóa học "' . $checkBaiHoc["TenKhoaHoc"] . '"',
                'TaiKhoan' => $_SESSION["account"]
            ]);
        }
        if (count($mangLuuTruTuVungHuyBoQua) != 0) {
            $HoatDong->insertHoatDong([
                'MaLoaiHoatDong' => 2,
                'TenHoatDong' => 'Hủy bỏ qua từ vựng',
                'NoiDung' => 'Hủy bỏ qua từ vựng "' . implode(", ", $mangLuuTruTuVungHuyBoQua) . '" thuộc bài học "' . $checkBaiHoc["TenBaiHoc"] . '" của khóa học "' . $checkBaiHoc["TenKhoaHoc"] . '"',
                'TaiKhoan' => $_SESSION["account"]
            ]);
        }
        msg_success2("Áp dụng thành công");
    } catch (Exception $err) {
        msg_error2('Có lỗi xảy ra, vui lòng thử lại');
    }
}

if ($_POST['type'] == 'deleteCourse') {
    try {
        if (!isset($_POST["maKhoaHoc"])) {
            msg_error2('Vui lòng điền đủ dữ liệu');
        }
        $maKhoaHoc = check_string($_POST["maKhoaHoc"]);

        if (empty($maKhoaHoc)) {
            msg_error2('Vui lòng điền đủ dữ liệu');
        }
        $checkKhoaHoc = $Database->get_row("SELECT * FROM dangkykhoahoc A inner join khoahoc B on A.TaiKhoan = '" . $_SESSION["account"] . "' and A.MaKhoaHoc = '" . $maKhoaHoc . "' and A.MaKhoaHoc = B.MaKhoaHoc");
        if ($checkKhoaHoc <= 0) {
            msg_error2('Khóa học không tồn tại');
        }
        // lưu hoạt động
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 2,
            'TenHoatDong' => 'Xóa khóa học',
            'NoiDung' => 'Xóa khóa học "' . $checkKhoaHoc["TenKhoaHoc"] . '" thành công',
            'TaiKhoan' => $_SESSION["account"]
        ]);

        // xóa các từ vựng đã học của khóa học
        $Course->xoaTatCaTuVungDaHoc([
            'TaiKhoan' => $_SESSION["account"],
            'MaKhoaHoc' => $maKhoaHoc
        ]);

        // xóa tất cả các từ vựng đã bỏ qua của khóa học
        $Course->xoaTatCaTuVungDaBoQua([
            'TaiKhoan' => $_SESSION["account"],
            'MaKhoaHoc' => $maKhoaHoc
        ]);

        // xóa đăng ký khóa học
        $Course->xoaDangKyKhoaHoc([
            'TaiKhoan' => $_SESSION["account"],
            'MaKhoaHoc' => $maKhoaHoc
        ]);
        msg_success2("Xóa khóa học thành công");
    } catch (Exception $err) {
        msg_error2($err->getMessage());
    }
}
if ($_POST['type'] == 'registerCourse') {
    try {
        if (!isset($_POST["maKhoaHoc"])) {
            msg_error2('Vui lòng điền đủ dữ liệu');
        }
        $maKhoaHoc = check_string($_POST["maKhoaHoc"]);

        if (empty($maKhoaHoc)) {
            msg_error2('Vui lòng điền đủ dữ liệu');
        }
        $checkKhoaHoc = $Database->get_row("SELECT * FROM dangkykhoahoc A inner join khoahoc B on A.TaiKhoan = '" . $_SESSION["account"] . "' and A.MaKhoaHoc = '" . $maKhoaHoc . "' and A.MaKhoaHoc = B.MaKhoaHoc");
        if ($checkKhoaHoc > 0) {
            msg_error2('Khóa học này đã tồn tại');
        }
        $getKhoaHoc = $Database->get_row("select * from khoahoc where MaKhoaHoc = '" . $maKhoaHoc . "' ");
        // lưu hoạt động
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 2,
            'TenHoatDong' => 'Đăng ký khóa học',
            'NoiDung' => 'Đăng ký khóa học "' . $getKhoaHoc["TenKhoaHoc"] . '" thành công',
            'TaiKhoan' => $_SESSION["account"]
        ]);

        // đăng ký khóa học
        $Course->dangKyKhoaHoc([
            'TaiKhoan' => $_SESSION["account"],
            'MaKhoaHoc' => $maKhoaHoc
        ]);
        msg_success2("Đăng ký khóa học thành công");
    } catch (Exception $err) {
        msg_error2($err->getMessage());
    }
}


if ($_POST['type'] == 'ratingCourse') {
    try {
        if (!isset($_POST["maKhoaHoc"]) || !isset($_POST["rating"]) || !isset($_POST["noiDung"])) {
            msg_error2('Vui lòng điền đủ dữ liệu');
        }
        $maKhoaHoc = check_string($_POST["maKhoaHoc"]);
        $rating = check_string($_POST["rating"]);
        $noiDung = check_string($_POST["noiDung"]);

        $rangeRating = array("1", "2", "3", "4", "5");

        if (empty($maKhoaHoc) || empty($rating) || empty($noiDung) || !in_array($rating, $rangeRating)) {
            msg_error2('Vui lòng điền đủ dữ liệu');
        }
        $checkKhoaHoc = $Database->get_row("SELECT * FROM dangkykhoahoc A inner join khoahoc B on A.TaiKhoan = '" . $_SESSION["account"] . "' and A.MaKhoaHoc = '" . $maKhoaHoc . "' and A.MaKhoaHoc = B.MaKhoaHoc");
        if ($checkKhoaHoc <= 0) {
            msg_error2('Khóa học không tồn tại');
        }
        // Kiểm tra xem đã đánh giá khóa học này chưa
        $checkDanhGiaKhoaHoc = $Database->num_rows("select * from danhgiakhoahoc where TaiKhoan = '" . $_SESSION["account"] . "' and MaKhoaHoc =  '" . $maKhoaHoc . "' ");

        if ($checkDanhGiaKhoaHoc > 0) {
            msg_error2('Bạn đã đánh giá khóa học này');
        }

        // lưu hoạt động
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 2,
            'TenHoatDong' => 'Đánh giá khóa học',
            'NoiDung' => 'Đánh giá khóa học "' . $checkKhoaHoc["TenKhoaHoc"] . '" với nội dung "' . $noiDung . '" thành công',
            'TaiKhoan' => $_SESSION["account"]
        ]);
        // Đánh giá khóa học
        $Database->insert("danhgiakhoahoc", [
            "TaiKhoan" => $_SESSION["account"],
            "MaKhoaHoc" => $maKhoaHoc,
            "NoiDungDanhGia" =>  $noiDung,
            "Rating" => $rating
        ]);
        msg_success("Đánh giá khóa học thành công", "", 1000);
    } catch (Exception $err) {
        msg_error2($err->getMessage());
    }
}
if ($_POST['type'] == 'getListRating') {
    try {
        if (!isset($_POST["maKhoaHoc"]) || !isset($_POST["sortTimeBy"])) {
            throw new Exception(getMessageError2('Vui lòng điền đủ dữ liệu'));
        }
        $maKhoaHoc = check_string($_POST["maKhoaHoc"]);
        $sortTimeBy = check_string($_POST["sortTimeBy"]);
        $itemsPerPage = check_string($_POST["itemsPerPage"]);
        $currentPage = check_string($_POST["currentPage"]);
        if (empty($maKhoaHoc) || empty($sortTimeBy)) {
            throw new Exception(getMessageError2('Vui lòng điền đủ dữ liệu'));
        }
        $skipResult = ($currentPage - 1) *  $itemsPerPage;
        $strResult = '';

        $getList = $Database->get_list(" SELECT B.TaiKhoan, B.AnhDaiDien, B.TenHienThi, A.NoiDungDanhGia, A.Rating, A.ThoiGian FROM danhgiakhoahoc A inner join nguoidung B on A.TaiKhoan = B.TaiKhoan and A.MaKhoaHoc = '" . $maKhoaHoc . "' order by A.ThoiGian $sortTimeBy limit $skipResult,  $itemsPerPage ");
        $result = array(
            'status' => 'success',
            'data' => $getList,
            'results' =>  count($getList),
        );

        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage(),

        );
        return die(json_encode($result));
    }
}
