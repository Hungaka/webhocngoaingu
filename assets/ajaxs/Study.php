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

if ($_POST['type'] == 'GetNewWord') {
    $maKhoaHoc = check_string($_POST['maKhoaHoc']);
    $maBaiHoc = check_string($_POST['maBaiHoc']);
    $token = check_string($_POST['token']);
    if (empty($maKhoaHoc) || empty($token) || empty($maBaiHoc)) {
        msg_error2('Vui lòng điền đủ dữ liệu');
    }
    $checkToken = $Database->get_row("SELECT * FROM hoctumoi WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
    if ($checkToken <= 0) {
        msg_error2('Dữ liệu không tồn tại');
    }
    // Lấy danh sách tất cả từ vựng
    $danhSachTuVung = $Database->get_list("select A.MaTuVung, A.MaBaiHoc, A.MaKhoaHoc from tuvung A  where A.MaKhoaHoc = '" . $maKhoaHoc . "'  and A.MaBaiHoc = '" . $maBaiHoc . "' and A.TrangThaiTuVung = 1 ORDER BY `MaBaiHoc` ASC, `MaTuVung` ASC");
    $danhSachTuVungDaBoQua = $Database->get_list("select A.MaTuVung, A.MaBaiHoc, A.MaKhoaHoc from boquatuvung A inner join tuvung B on A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and A.MaTuVung = B.MaTuVung and B.TrangThaiTuVung = 1 and A.MaKhoaHoc = '" . $maKhoaHoc . "'  and A.MaBaiHoc = '" . $maBaiHoc . "' and TaiKhoan = '" . $_SESSION['account'] . "' ORDER BY `MaBaiHoc` ASC, `MaTuVung` ASC");
    // Lấy danh sách các từ vựng người dùng đã học
    $danhSachTuVungDaHoc = $Database->get_list("select A.MaTuVung, A.MaBaiHoc, A.MaKhoaHoc from hoctuvung A inner join tuvung B on A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and A.MaTuVung = B.MaTuVung and A.MaKhoaHoc = '" . $maKhoaHoc . "' and A.MaBaiHoc = '" . $maBaiHoc . "' and TaiKhoan = '" . $_SESSION['account'] . "' order by MaBaiHoc asc, MaTuVung asc");
    // Loại các từ đã học ra khỏi danh sách từ vựng
    $danhSachTuVungChuaHoc = removeItemDuplicate(array_merge($danhSachTuVung, $danhSachTuVungDaBoQua, $danhSachTuVungDaHoc), "MaTuVung");

    if (count($danhSachTuVungChuaHoc) == 0 ||  $checkToken["SoCauHienTai"] == 5 || $checkToken["TienTrinh"] == 100) {

        $result = array(
            'status' => "complete",
        );
    } else {
        // chọn từ đầu tiên trong danh sách từ mới
        $tuMoi = reset($danhSachTuVungChuaHoc);
        $tuMoi = $Database->get_row("SELECT * FROM tuvung A inner join khoahoc B on A.MaKhoaHoc = '" . $tuMoi["MaKhoaHoc"] . "' and A.MaKhoaHoc = B.MaKhoaHoc inner join baihoc C on A.MaBaiHoc = '" . $tuMoi["MaBaiHoc"] . "' and A.MaBaiHoc = C.MaBaiHoc  and A.MaKhoaHoc = C.MaKhoaHoc and A.MaTuVung = '" . $tuMoi["MaTuVung"] . "' ");
        // lấy các ví dụ từ từ mới
        $viDu = $Database->get_list(" SELECT * FROM vidu WHERE MaTuVung = '" . $tuMoi["MaTuVung"] . "' AND MaKhoaHoc = '" . $tuMoi["MaKhoaHoc"] . "' AND MaBaiHoc = '" . $tuMoi["MaBaiHoc"] . "' ");
        $tienTrinh = $checkToken["TienTrinh"] + 20;
        $soCauHienTai = $checkToken["SoCauHienTai"] + 1;
        // cập nhật lại tiến trình học từ mới
        $Database->update("hoctumoi", ['TienTrinh' => $tienTrinh, 'SoCauHienTai' => $soCauHienTai], "Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "' ");

        // cập nhật điểm kinh nghiệm cho người dùng  
        $Study->updateKinhNghiem($tuMoi["Diem"], $_SESSION['account']);
        // thêm vào hoạt động 
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 2,
            'TenHoatDong' => 'Học từ vựng',
            'NoiDung' => 'Học từ vựng mới "' . $tuMoi["NoiDungTuVung"] . '" thuộc bài học "' . $tuMoi["TenBaiHoc"] . '" của khóa học "' . $tuMoi["TenKhoaHoc"] . '"',
            'TaiKhoan' => $_SESSION["account"]
        ]);
        // thêm vào danh sách từ vựng đã học
        $Study->insertTuVungDaHoc([
            'TaiKhoan' => $_SESSION['account'],
            'MaTuVung' => $tuMoi["MaTuVung"],
            'MaKhoaHoc' => $tuMoi["MaKhoaHoc"],
            'MaBaiHoc' => $tuMoi["MaBaiHoc"],
        ]);

        $result = array(
            'status' => "success",
            'data' => array(
                'tienTrinh' => $tienTrinh,
                'data' => $tuMoi,
                'viDu' => $viDu,
                'soCauHienTai' => $soCauHienTai,
            ),
            'danhSachTuVungChuaHoc' => $danhSachTuVungChuaHoc,
        );
    }



    return die(json_encode($result));
}
if ($_POST['type'] == 'DanhDauTuKho') {
    $maKhoaHoc = check_string($_POST['maKhoaHoc']);
    $maBaiHoc = check_string($_POST['maBaiHoc']);
    $maTuVung = check_string($_POST['maTuVung']);
    $token = check_string($_POST['token']);
    if (empty($maKhoaHoc) || empty($token) || empty($maBaiHoc) || empty($maTuVung)) {
        msg_error2('Vui lòng điền đủ dữ liệu');
    }
    $checkToken = $Database->get_row("SELECT * FROM hoctumoi WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
    if ($checkToken <= 0) {
        msg_error2('Dữ liệu không tồn tại');
    }
    $checkHocTuVung = $Database->get_row("select * from hoctuvung A inner join tuvung B on A.MaKhoaHoc = B.MaKhoaHoc and A.MaBaiHoc = B.MaBaiHoc and A.MaTuVung = B.MaTuVung and B.TrangThaiTuVung = 1 and A.TaiKhoan = '" . $_SESSION['account'] . "' and A.MaTuVung = '" . $maTuVung . "'  and A.MaBaiHoc = '" . $maBaiHoc . "' and A.MaKhoaHoc = '" . $maKhoaHoc . "'");
    if ($checkHocTuVung <= 0) {
        msg_error2('Dữ liệu không tồn tại');
    }
    $getTuVung = $Database->get_row("SELECT * FROM tuvung A inner join khoahoc B on A.MaKhoaHoc = '" . $maKhoaHoc . "' and A.MaKhoaHoc = B.MaKhoaHoc inner join baihoc C on A.MaBaiHoc = '" . $maBaiHoc . "' and A.MaBaiHoc = C.MaBaiHoc and A.MaTuVung = '" . $maTuVung . "'  ");
    if ($checkHocTuVung["TuKho"] == 0) {
        $Study->danhDauTuKho([
            'TaiKhoan' => $_SESSION['account'],
            'MaTuVung' => $checkHocTuVung["MaTuVung"],
            'MaKhoaHoc' => $checkHocTuVung["MaKhoaHoc"],
            'MaBaiHoc' => $checkHocTuVung["MaBaiHoc"],
        ]);
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 2,
            'TenHoatDong' => 'Đánh dấu từ khó',
            'NoiDung' => 'Đánh dấu từ khó mới: "' . $getTuVung["NoiDungTuVung"] . '" thuộc bài học "' . $getTuVung["TenBaiHoc"] . '" của khóa học "' . $getTuVung["TenKhoaHoc"] . '"',
            'TaiKhoan' => $_SESSION["account"]
        ]);

        msg_success2("Đánh dấu từ khó thành công");
    } else {
        $Study->huyDanhDauTuKho([
            'TaiKhoan' => $_SESSION['account'],
            'MaTuVung' => $checkHocTuVung["MaTuVung"],
            'MaKhoaHoc' => $checkHocTuVung["MaKhoaHoc"],
            'MaBaiHoc' => $checkHocTuVung["MaBaiHoc"],
        ]);
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 2,
            'TenHoatDong' => 'Hủy đánh dấu từ khó',
            'NoiDung' => 'Hủy đánh dấu từ khó mới: "' . $getTuVung["NoiDungTuVung"] . '" thuộc bài học "' . $getTuVung["TenBaiHoc"] . '" của khóa học "' . $getTuVung["TenKhoaHoc"] . '"',
            'TaiKhoan' => $_SESSION["account"]
        ]);
        msg_success2("Hủy đánh dấu từ khó thành công");
    }
}

if ($_POST['type'] == 'GetTienDoHocTap') {
    $taiKhoan = check_string($_POST['taiKhoan']);
    if (empty($taiKhoan)) {
        msg_error2('Vui lòng điền đủ dữ liệu');
    }
    $checkToken = $Database->get_row("SELECT * FROM hoctumoi WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
    if ($checkToken <= 0) {
        msg_error2('Dữ liệu không tồn tại');
    }
    // Lấy danh sách tất cả từ vựng
    $danhSachTuVung = $Database->get_list("select A.MaTuVung, A.MaBaiHoc, A.MaKhoaHoc from tuvung A  where A.MaKhoaHoc = '" . $maKhoaHoc . "'  and A.MaBaiHoc = '" . $maBaiHoc . "' ORDER BY `MaBaiHoc` ASC, `MaTuVung` ASC");
    $danhSachTuVungDaBoQua = $Database->get_list("select A.MaTuVung, A.MaBaiHoc, A.MaKhoaHoc from boquatuvung A  where A.MaKhoaHoc = '" . $maKhoaHoc . "'  and A.MaBaiHoc = '" . $maBaiHoc . "' and TaiKhoan = '" . $_SESSION['account'] . "' ORDER BY `MaBaiHoc` ASC, `MaTuVung` ASC");
    // Lấy danh sách các từ vựng người dùng đã học
    $danhSachTuVungDaHoc = $Database->get_list("select MaTuVung, MaBaiHoc, MaKhoaHoc from hoctuvung where MaKhoaHoc = '" . $maKhoaHoc . "' and MaBaiHoc = '" . $maBaiHoc . "' and TaiKhoan = '" . $_SESSION['account'] . "' order by MaBaiHoc asc, MaTuVung asc");
    // Loại các từ đã học ra khỏi danh sách từ vựng
    $danhSachTuVungChuaHoc = removeItemDuplicate(array_merge($danhSachTuVung, $danhSachTuVungDaBoQua, $danhSachTuVungDaHoc), "MaTuVung");

    if (count($danhSachTuVungChuaHoc) == 0 ||  $checkToken["SoCauHienTai"] == 5 || $checkToken["TienTrinh"] == 100) {

        $result = array(
            'status' => "complete",
        );
    } else {
        // chọn từ đầu tiên trong danh sách từ mới
        $tuMoi = reset($danhSachTuVungChuaHoc);
        $tuMoi = $Database->get_row("SELECT * FROM tuvung A inner join khoahoc B on A.MaKhoaHoc = '" . $tuMoi["MaKhoaHoc"] . "' and A.MaKhoaHoc = B.MaKhoaHoc inner join baihoc C on A.MaBaiHoc = '" . $tuMoi["MaBaiHoc"] . "' and A.MaBaiHoc = C.MaBaiHoc and A.MaTuVung = '" . $tuMoi["MaTuVung"] . "' ");
        // lấy các ví dụ từ từ mới
        $viDu = $Database->get_list(" SELECT * FROM vidu WHERE MaTuVung = '" . $tuMoi["MaTuVung"] . "' AND MaKhoaHoc = '" . $tuMoi["MaKhoaHoc"] . "' AND MaBaiHoc = '" . $tuMoi["MaBaiHoc"] . "' ");
        $tienTrinh = $checkToken["TienTrinh"] + 20;
        $soCauHienTai = $checkToken["SoCauHienTai"] + 1;
        // cập nhật lại tiến trình học từ mới
        $Database->update("hoctumoi", ['TienTrinh' => $tienTrinh, 'SoCauHienTai' => $soCauHienTai], "Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "' ");

        // cập nhật điểm kinh nghiệm cho người dùng  
        $Study->updateKinhNghiem($tuMoi["Diem"], $_SESSION['account']);
        // thêm vào hoạt động 
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 2,
            'TenHoatDong' => 'Học từ vựng',
            'NoiDung' => 'Học từ vựng mới "' . $tuMoi["NoiDungTuVung"] . '" thuộc bài học "' . $tuMoi["TenBaiHoc"] . '" của khóa học "' . $tuMoi["TenKhoaHoc"] . '"',
            'TaiKhoan' => $_SESSION["account"]
        ]);
        // thêm vào danh sách từ vựng đã học
        $Study->insertTuVungDaHoc([
            'TaiKhoan' => $_SESSION['account'],
            'MaTuVung' => $tuMoi["MaTuVung"],
            'MaKhoaHoc' => $tuMoi["MaKhoaHoc"],
            'MaBaiHoc' => $tuMoi["MaBaiHoc"],
        ]);

        $result = array(
            'status' => "success",
            'data' => array(
                'tienTrinh' => $tienTrinh,
                'data' => $tuMoi,
                'viDu' => $viDu,
                'soCauHienTai' => $soCauHienTai,
            ),
            'danhSachTuVungChuaHoc' => $danhSachTuVungChuaHoc,
        );
    }



    return die(json_encode($result));
}


if ($_POST['type'] == 'PracticeWord') {
    try {

        $maKhoaHoc = check_string($_POST['maKhoaHoc']);
        $maBaiHoc = check_string($_POST['maBaiHoc']);
        $token = check_string($_POST['token']);
        if (empty($maKhoaHoc) || empty($token) || empty($maBaiHoc)) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ dữ liệu'));
        }
        $checkToken = $Database->get_row("SELECT * FROM ontaptuvung WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkToken <= 0) {
            throw new Exception(getMessageError2('Dữ liệu không tồn tại'));
        }
        // Hoàn thành
        if ($checkToken["TienTrinh"] == 100) {
            $result = array(
                'status' => "complete",

            );
            return die(json_encode($result));
        }

        // Lấy danh sách các từ vựng đã học đến thời gian ôn tập, nhưng không nằm trong danh sách bỏ qua
        $listDaHocChuaBoQua = $Database->get_list("select A.TaiKhoan as TaiKhoan, A.MaBaiHoc as MaBaiHoc, A.MaKhoaHoc as MaKhoaHoc, A.MaTuVung as MaTuVung from hoctuvung A left join boquatuvung B on A.MaTuVung = B.MaTuVung and A.MaBaiHoc = B.MaBaiHoc and A.MaKhoaHoc = B.MaKhoaHoc
            and A.TaiKhoan = B.TaiKhoan where A.TaiKhoan = '" . $_SESSION["account"] . "' and A.MaBaiHoc = '" . $maBaiHoc . "' and A.MaKhoaHoc = '" . $maKhoaHoc . "' and (A.ThoiGianOnTap is NULL or A.ThoiGianOnTap < NOW() - INTERVAL 30 minute) and B.TaiKhoan is NULL and B.MaTuVung is NULL and B.MaBaiHoc is NULL and B.MaKhoaHoc is NULL");

        $listDaHocChuaBoQua = array_slice($listDaHocChuaBoQua, 0, 5);
        $randomType = rand(1, 3);
   

        /*
   $type: 
   - 1: Look 1 picture and choose the correct answer
   - 2: Look 4 picture and choose one correct answer
   - 3: Fill in into input
*/
        if ($randomType == 1 || $randomType == 2) {
            // Nếu không còn từ vựng để ôn thì complete trạng thái
            if (count($listDaHocChuaBoQua) == 0) {
                $result = array(
                    'status' => "complete",

                );
                return die(json_encode($result));
            }
            $word = reset($listDaHocChuaBoQua);


            $word = $Database->get_row("SELECT * FROM tuvung WHERE MaBaiHoc = '" . $maBaiHoc . "' AND MaKhoaHoc = '" . $maKhoaHoc . "' AND MaTuVung = '" . $word["MaTuVung"] . "' ");

            // Lấy 3 từ vựng random (ngoại trừ từ để ôn)
            $getRandomAnswer = $Database->get_list("select * from tuvung WHERE MaBaiHoc = '" . $maBaiHoc . "' AND MaKhoaHoc = '" . $maKhoaHoc . "' AND MaTuVung != '" . $word["MaTuVung"] . "' ORDER BY RAND() limit 3");
            // Nếu không đủ 3 từ vựng random thì ta bù vào bằng từ chính
            $soLuongRandomAnswer = count($getRandomAnswer);
            if (count($getRandomAnswer) < 3) {
                for ($i = 0; $i < 3 - $soLuongRandomAnswer; $i++) {
                    array_push($getRandomAnswer, $word);
                }
            }


            // kết hợp 3 đáp án random với đáp án chính xác
            array_push($getRandomAnswer, $word);


            // Sắp xếp ranndom các lựa chọn
            shuffle($getRandomAnswer);

            // Tạo token cho câu ôn tập
            $tokenOnTap = randomString('0123456789QWERTYUIOPASDGHJKLZXCVBNM', '20');
            $Database->insert("ontaploai1", [
                'TaiKhoan' => $_SESSION["account"],
                'Token' => $tokenOnTap,
                'MaTuVung' => $word["MaTuVung"],
                'MaBaiHoc' => $word["MaBaiHoc"],
                'MaKhoaHoc' => $word["MaKhoaHoc"],

            ]);
            $tienTrinh = $checkToken["TienTrinh"];
            $soCauHienTai = $checkToken["SoCauHienTai"];
            $result = array(
                'status' => "success",
                'data' => array(
                    'tokenOnTap' => $tokenOnTap,
                    'type' => $randomType,
                    'data' => $word,
                    'randomAnswer' => ($getRandomAnswer),
                    'tienTrinh' => $tienTrinh,
                    'soCauHienTai' => $soCauHienTai,
                ),
            );
        } else if ($randomType == 3) {
            $getFirstWord = reset($listDaHocChuaBoQua);
            if ($getFirstWord == false) {
                $result = array(
                    'status' => "complete",
                );
                return die(json_encode($result));
            }

            $word = $Database->get_row("SELECT * FROM tuvung WHERE MaBaiHoc = '" . $maBaiHoc . "' AND MaKhoaHoc = '" . $maKhoaHoc . "' AND MaTuVung = '" . $getFirstWord["MaTuVung"] . "' ");
            // Tạo token cho câu ôn tập
            $tokenOnTap = randomString('0123456789QWERTYUIOPASDGHJKLZXCVBNM', '20');
            $Database->insert("ontaploai1", [
                'TaiKhoan' => $_SESSION["account"],
                'Token' => $tokenOnTap,
                'MaTuVung' => $word["MaTuVung"],
                'MaBaiHoc' => $word["MaBaiHoc"],
                'MaKhoaHoc' => $word["MaKhoaHoc"],

            ]);
            $tienTrinh = $checkToken["TienTrinh"];
            $soCauHienTai = $checkToken["SoCauHienTai"];
            $result = array(
                'status' => "success",
                'data' => array(
                    'tokenOnTap' => $tokenOnTap,
                    'type' => $randomType,
                    'data' => $word,
                    'tienTrinh' => $tienTrinh,
                    'soCauHienTai' => $soCauHienTai,
                ),
            );
        }


        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage(),

        );
        return die(json_encode($result));
    }
}
if ($_POST['type'] == 'PracticeDifficultWord') {
    try {

        $maKhoaHoc = check_string($_POST['maKhoaHoc']);
        $maBaiHoc = check_string($_POST['maBaiHoc']);
        $token = check_string($_POST['token']);
        if (empty($maKhoaHoc) || empty($token) || empty($maBaiHoc)) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ dữ liệu'));
        }
        $checkToken = $Database->get_row("SELECT * FROM ontaptuvungkho WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkToken <= 0) {
            throw new Exception(getMessageError2('Dữ liệu không tồn tại'));
        }
        // Hoàn thành
        if ($checkToken["TienTrinh"] == 100) {
            $result = array(
                'status' => "complete",

            );
            return die(json_encode($result));
        }

        // Lấy danh sách các từ vựng đã học đến thời gian ôn tập, nhưng không nằm trong danh sách bỏ qua
        $listDaHocChuaBoQua = $Database->get_list("select A.TaiKhoan as TaiKhoan, A.MaBaiHoc as MaBaiHoc, A.MaKhoaHoc as MaKhoaHoc, A.MaTuVung as MaTuVung from hoctuvung A left join boquatuvung B on A.MaTuVung = B.MaTuVung and A.MaBaiHoc = B.MaBaiHoc and A.MaKhoaHoc = B.MaKhoaHoc
            and A.TaiKhoan = B.TaiKhoan where A.TuKho = '1' and A.TaiKhoan = '" . $_SESSION["account"] . "' and A.MaBaiHoc = '" . $maBaiHoc . "' and A.MaKhoaHoc = '" . $maKhoaHoc . "' and (A.ThoiGianOnTap is NULL or A.ThoiGianOnTap < NOW() - INTERVAL 30 minute) and B.TaiKhoan is NULL and B.MaTuVung is NULL and B.MaBaiHoc is NULL and B.MaKhoaHoc is NULL");

        $listDaHocChuaBoQua = array_slice($listDaHocChuaBoQua, 0, 5);
        $randomType = rand(1, 3);

        /*
   $type: 
   - 1: Look 1 picture and choose the correct answer
   - 2: Look 4 picture and choose one correct answer
   - 3: Match words together
   - 4: Fill in into input
*/
        if ($randomType == 1 || $randomType == 2) {
            // Nếu không còn từ vựng để ôn thì complete trạng thái
            if (count($listDaHocChuaBoQua) == 0) {
                $result = array(
                    'status' => "complete",

                );
                return die(json_encode($result));
            }
            $word = reset($listDaHocChuaBoQua);


            $word = $Database->get_row("SELECT * FROM tuvung WHERE MaBaiHoc = '" . $maBaiHoc . "' AND MaKhoaHoc = '" . $maKhoaHoc . "' AND MaTuVung = '" . $word["MaTuVung"] . "' ");

            // Lấy 3 từ vựng random (ngoại trừ từ để ôn)
            $getRandomAnswer = $Database->get_list("select * from tuvung WHERE MaBaiHoc = '" . $maBaiHoc . "' AND MaKhoaHoc = '" . $maKhoaHoc . "' AND MaTuVung != '" . $word["MaTuVung"] . "' ORDER BY RAND() limit 3");
            // Nếu không đủ 3 từ vựng random thì ta bù vào bằng từ chính
            $soLuongRandomAnswer = count($getRandomAnswer);
            if (count($getRandomAnswer) < 3) {
                for ($i = 0; $i < 3 - $soLuongRandomAnswer; $i++) {
                    array_push($getRandomAnswer, $word);
                }
            }


            // kết hợp 3 đáp án random với đáp án chính xác
            array_push($getRandomAnswer, $word);


            // Sắp xếp ranndom các lựa chọn
            shuffle($getRandomAnswer);

            // Tạo token cho câu ôn tập
            $tokenOnTap = randomString('0123456789QWERTYUIOPASDGHJKLZXCVBNM', '20');
            $Database->insert("ontaploai1", [
                'TaiKhoan' => $_SESSION["account"],
                'Token' => $tokenOnTap,
                'MaTuVung' => $word["MaTuVung"],
                'MaBaiHoc' => $word["MaBaiHoc"],
                'MaKhoaHoc' => $word["MaKhoaHoc"],

            ]);
            $tienTrinh = $checkToken["TienTrinh"];
            $soCauHienTai = $checkToken["SoCauHienTai"];
            $result = array(
                'status' => "success",
                'data' => array(
                    'tokenOnTap' => $tokenOnTap,
                    'type' => $randomType,
                    'data' => $word,
                    'randomAnswer' => ($getRandomAnswer),
                    'tienTrinh' => $tienTrinh,
                    'soCauHienTai' => $soCauHienTai,
                ),
            );
        } else if ($randomType == 3) {
            $getFirstWord = reset($listDaHocChuaBoQua);
            if ($getFirstWord == false) {
                $result = array(
                    'status' => "complete",
                );
                return die(json_encode($result));
            }

            $word = $Database->get_row("SELECT * FROM tuvung WHERE MaBaiHoc = '" . $maBaiHoc . "' AND MaKhoaHoc = '" . $maKhoaHoc . "' AND MaTuVung = '" . $getFirstWord["MaTuVung"] . "' ");
            // Tạo token cho câu ôn tập
            $tokenOnTap = randomString('0123456789QWERTYUIOPASDGHJKLZXCVBNM', '20');
            $Database->insert("ontaploai1", [
                'TaiKhoan' => $_SESSION["account"],
                'Token' => $tokenOnTap,
                'MaTuVung' => $word["MaTuVung"],
                'MaBaiHoc' => $word["MaBaiHoc"],
                'MaKhoaHoc' => $word["MaKhoaHoc"],

            ]);
            $tienTrinh = $checkToken["TienTrinh"];
            $soCauHienTai = $checkToken["SoCauHienTai"];
            $result = array(
                'status' => "success",
                'data' => array(
                    'tokenOnTap' => $tokenOnTap,
                    'type' => $randomType,
                    'data' => $word,
                    'tienTrinh' => $tienTrinh,
                    'soCauHienTai' => $soCauHienTai,
                ),
            );
        }


        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage(),

        );
        return die(json_encode($result));
    }
}
if ($_POST['type'] == 'FastPracticeWordType1') {
    try {
        $maKhoaHoc = check_string($_POST['maKhoaHoc']);
        $maBaiHoc = check_string($_POST['maBaiHoc']);
        $token = check_string($_POST['token']);
        $practiceToken = check_string($_POST['practiceToken']);
        $userAnswer = check_string($_POST['userAnswer']);
        if (empty($maKhoaHoc) || empty($token) || empty($maBaiHoc) || empty($userAnswer)) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ dữ liệu'));
        }

        $checkPracticeToken = $Database->get_row("SELECT * FROM ontapsieutoctuvung WHERE Token = '" .  $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkPracticeToken <= 0) {
            throw new Exception(getMessageError2('Token không tồn tại'));
        }
        $checkToken = $Database->get_row("SELECT * FROM ontaploai1 WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkToken <= 0) {
            throw new Exception(getMessageError2('Token không tồn tại'));
        }
        // Kiểm tra xem có học từ vựng này hay chưa
        $checkHocTuVung = $Database->get_row("select * from hoctuvung where TaiKhoan = '" . $_SESSION['account'] . "' and MaTuVung = '" . $checkToken["MaTuVung"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'");
        if ($checkHocTuVung <= 0) {
            throw new Exception(getMessageError2('Dữ liệu không tồn tại'));
        }
        if ($checkPracticeToken["SoMang"] <= 0) {
            throw new Exception(getMessageError2('Bạn đã hết lượt ôn tập'));
        }
        if ($checkPracticeToken["SoCauHienTai"] > 20) {
            throw new Exception(getMessageError2('Bạn đã hết lượt ôn tập'));
        }
        $timeSendRequest = new DateTime($checkToken["ThoiGian"]);
        $timeSendRequest = $timeSendRequest->getTimestamp();
        $now = new DateTime();
        $now = $now->getTimestamp();
        if ($now - $timeSendRequest >= 10) {
            throw new Exception(getMessageError2('Đã hết thời gian trả lời'));
        }
        $getCorrectAnswer = $Database->get_row("select * from tuvung where MaTuVung = '" . $checkToken["MaTuVung"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "'");
        // xóa token câu ôn tập hiện tại
        $Database->query("delete from ontaploai1 where  Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");

        if ($checkToken["MaTuVung"] != $userAnswer) {
            // update lại số mạng và số câu hiện tại
            $soMang = $checkPracticeToken["SoMang"] - 1;
            $soCauHienTai = $checkPracticeToken["SoCauHienTai"] + 1;
            $Database->update("ontapsieutoctuvung", ['SoMang' => $soMang, 'SoCauHienTai' => $soCauHienTai], "Token = '" . $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "' ");

            // ghi vào hoạt động của người dùng
            $HoatDong->insertHoatDong([
                'TaiKhoan' =>  $_SESSION['account'],
                'TenHoatDong' => 'Ôn tập từ vựng siêu tốc',
                'MaLoaiHoatDong' => '2',
                'NoiDung' => 'Trả lời sai câu ôn tập của từ vựng "' .  $getCorrectAnswer['NoiDungTuVung'] . '"'
            ]);
            $result = array(
                'status' => 'error',
                'message' => getMessageError2('Câu trả lời không chính xác'),
                'data' => array(
                    'soMang' => $soMang,
                    'soCauHienTai' => $soCauHienTai,
                    'noiDungTuVung' =>  $getCorrectAnswer["NoiDungTuVung"]
                )
            );
        } else {
            // Update trạng thái ôn tập cho từ vựng
            $Database->update(
                "hoctuvung",
                [
                    'ThoiGianOnTap' => getTime()
                ],
                "TaiKhoan = '" . $_SESSION['account'] . "' and MaTuVung = '" . $checkToken["MaTuVung"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'"
            );

            // update lại số mạng và số câu hiện tại
            $soCauDung = $checkPracticeToken["SoCauDung"] + 1;
            $soCauHienTai = $checkPracticeToken["SoCauHienTai"] + 1;
            $Database->update("ontapsieutoctuvung", ['SoCauDung' => $soCauDung, 'SoCauHienTai' => $soCauHienTai], "Token = '" . $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "' ");


            // Cộng điểm kinh nghiệm cho người dùng
            $Study->updateKinhNghiem($getCorrectAnswer["Diem"], $_SESSION['account']);
            // ghi vào hoạt động của người dùng
            $HoatDong->insertHoatDong([
                'TaiKhoan' =>  $_SESSION['account'],
                'TenHoatDong' => 'Ôn tập từ vựng siêu tốc',
                'MaLoaiHoatDong' => '2',
                'NoiDung' => 'Trả lời đúng câu ôn tập của từ vựng "' .  $getCorrectAnswer['NoiDungTuVung'] . '"'
            ]);
            $result = array(
                'status' => 'success',
                'message' => getMessageSuccess2('Câu trả lời chính xác'),
                'data' => array(
                    'soCauHienTai' => $soCauHienTai,
                    'soCauDung' => $soCauDung,

                    'noiDungTuVung' =>  $getCorrectAnswer["NoiDungTuVung"]
                )
            );
        }

        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage(),

        );
        return die(json_encode($result));
    }
}
if ($_POST['type'] == 'FastPracticeWordType1EndTime') {
    try {
        $maKhoaHoc = check_string($_POST['maKhoaHoc']);
        $maBaiHoc = check_string($_POST['maBaiHoc']);
        $token = check_string($_POST['token']);
        $practiceToken = check_string($_POST['practiceToken']);
        if (empty($maKhoaHoc) || empty($token) || empty($maBaiHoc)) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ dữ liệu'));
        }

        $checkPracticeToken = $Database->get_row("SELECT * FROM ontapsieutoctuvung WHERE Token = '" .  $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkPracticeToken <= 0) {
            throw new Exception(getMessageError2('Token không tồn tại'));
        }
        $checkToken = $Database->get_row("SELECT * FROM ontaploai1 WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkToken <= 0) {
            throw new Exception(getMessageError2('Token không tồn tại'));
        }
        // Kiểm tra xem có học từ vựng này hay chưa
        $checkHocTuVung = $Database->get_row("select * from hoctuvung where TaiKhoan = '" . $_SESSION['account'] . "' and MaTuVung = '" . $checkToken["MaTuVung"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'");
        if ($checkHocTuVung <= 0) {
            throw new Exception(getMessageError2('Dữ liệu không tồn tại'));
        }
        if ($checkPracticeToken["SoMang"] <= 0) {
            throw new Exception(getMessageError2('Bạn đã hết lượt ôn tập'));
        }
        if ($checkPracticeToken["SoCauHienTai"] > 20) {
            throw new Exception(getMessageError2('Bạn đã hết lượt ôn tập'));
        }
        $getCorrectAnswer = $Database->get_row("select * from tuvung where MaTuVung = '" . $checkToken["MaTuVung"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "'");
        // xóa token câu ôn tập hiện tại
        $Database->query("delete from ontaploai1 where  Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        // update lại số mạng và số câu hiện tại
        $soMang = $checkPracticeToken["SoMang"] - 1;
        $soCauHienTai = $checkPracticeToken["SoCauHienTai"] + 1;
        $Database->update("ontapsieutoctuvung", ['SoMang' => $soMang, 'SoCauHienTai' => $soCauHienTai], "Token = '" . $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "' ");

        // ghi vào hoạt động của người dùng
        $HoatDong->insertHoatDong([
            'TaiKhoan' =>  $_SESSION['account'],
            'TenHoatDong' => 'Ôn tập từ vựng siêu tốc',
            'MaLoaiHoatDong' => '2',
            'NoiDung' => 'Không trả lời câu ôn tập của từ vựng "' .  $getCorrectAnswer['NoiDungTuVung'] . '"'
        ]);
        $result = array(
            'status' => 'error',
            'message' => getMessageError2('Chưa chọn câu trả lời'),
            'data' => array(
                'soMang' => $soMang,
                'soCauHienTai' => $soCauHienTai,
                'noiDungTuVung' =>  $getCorrectAnswer["NoiDungTuVung"]
            )
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

if ($_POST['type'] == 'PracticeWordType1') {
    try {
        $maKhoaHoc = check_string($_POST['maKhoaHoc']);
        $maBaiHoc = check_string($_POST['maBaiHoc']);
        $token = check_string($_POST['token']);
        $practiceToken = check_string($_POST['practiceToken']);
        $userAnswer = check_string($_POST['userAnswer']);
        $typeOnTap = check_string($_POST['typeOnTap']);
        if (empty($maKhoaHoc) || empty($token) || empty($maBaiHoc) || empty($userAnswer) || empty($typeOnTap)) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ dữ liệu'));
        }
        if ($typeOnTap == "tuKho") {
            $checkPracticeToken = $Database->get_row("SELECT * FROM ontaptuvungkho WHERE Token = '" .  $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
            if ($checkPracticeToken <= 0) {
                throw new Exception(getMessageError2('Token không tồn tại'));
            }
        } else  if ($typeOnTap == "binhThuong") {
            $checkPracticeToken = $Database->get_row("SELECT * FROM ontaptuvung WHERE Token = '" .  $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
            if ($checkPracticeToken <= 0) {
                throw new Exception(getMessageError2('Token không tồn tại'));
            }
        }


        $checkToken = $Database->get_row("SELECT * FROM ontaploai1 WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkToken <= 0) {
            throw new Exception(getMessageError2('Token không tồn tại'));
        }
        // Kiểm tra xem có học từ vựng này hay chưa
        $checkHocTuVung = $Database->get_row("select * from hoctuvung where TaiKhoan = '" . $_SESSION['account'] . "' and MaTuVung = '" . $checkToken["MaTuVung"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'");
        if ($checkHocTuVung <= 0) {
            throw new Exception(getMessageError2('Dữ liệu không tồn tại'));
        }


        $getCorrectAnswer = $Database->get_row("select * from tuvung where MaTuVung = '" . $checkToken["MaTuVung"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "'");
        // xóa token câu ôn tập hiện tại
        $Database->query("delete from ontaploai1 where  Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");

        if ($checkToken["MaTuVung"] != $userAnswer) {
            // ghi vào hoạt động của người dùng
            $HoatDong->insertHoatDong([
                'TaiKhoan' =>  $_SESSION['account'],
                'TenHoatDong' => 'Ôn tập từ vựng',
                'MaLoaiHoatDong' => '2',
                'NoiDung' => 'Trả lời sai câu ôn tập của từ vựng "' .  $getCorrectAnswer['NoiDungTuVung'] . '"'
            ]);
            $result = array(
                'status' => 'error',
                'message' => getMessageError2('Câu trả lời không chính xác'),
                'data' => array(

                    'noiDungTuVung' =>  $getCorrectAnswer["NoiDungTuVung"]
                )
            );
        } else {
            // Update trạng thái ôn tập cho từ vựng
            $Database->update(
                "hoctuvung",
                [
                    'ThoiGianOnTap' => getTime()
                ],
                "TaiKhoan = '" . $_SESSION['account'] . "' and MaTuVung = '" . $checkToken["MaTuVung"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'"
            );

            // Update tiến trình của phiên ôn tập hiện tại
            $tienTrinh =  $checkPracticeToken["TienTrinh"] + 20;
            $soCauHienTai =  $checkPracticeToken["SoCauHienTai"] + 1;
            $Database->update("ontaptuvung", ['TienTrinh' => $tienTrinh, 'SoCauHienTai' => $soCauHienTai], "Token = '" . $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "' ");


            // Cộng điểm kinh nghiệm cho người dùng
            $Study->updateKinhNghiem($getCorrectAnswer["Diem"], $_SESSION['account']);
            // ghi vào hoạt động của người dùng
            $HoatDong->insertHoatDong([
                'TaiKhoan' =>  $_SESSION['account'],
                'TenHoatDong' => 'Ôn tập từ vựng',
                'MaLoaiHoatDong' => '2',
                'NoiDung' => 'Trả lời đúng câu ôn tập của từ vựng "' .  $getCorrectAnswer['NoiDungTuVung'] . '"'
            ]);
            $result = array(
                'status' => 'success',
                'message' => getMessageSuccess2('Câu trả lời chính xác'),
                'data' => array(
                    'tienTrinh' => $tienTrinh,
                    'noiDungTuVung' =>  $getCorrectAnswer["NoiDungTuVung"]
                )
            );
        }

        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage(),

        );
        return die(json_encode($result));
    }
}
if ($_POST['type'] == 'PracticeWordType3') {
    try {
        $maKhoaHoc = check_string($_POST['maKhoaHoc']);
        $maBaiHoc = check_string($_POST['maBaiHoc']);
        $token = check_string($_POST['token']);
        $practiceToken = check_string($_POST['practiceToken']);
        $userAnswer = ($_POST['userAnswer']);
        $typeOnTap = check_string($_POST['typeOnTap']);
        if (empty($maKhoaHoc) || empty($token) || empty($maBaiHoc) || empty($userAnswer)  || empty($typeOnTap)) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ dữ liệu'));
        }

        if ($typeOnTap == "tuKho") {
            $checkPracticeToken = $Database->get_row("SELECT * FROM ontaptuvungkho WHERE Token = '" .  $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
            if ($checkPracticeToken <= 0) {
                throw new Exception(getMessageError2('Token không tồn tại'));
            }
        } else  if ($typeOnTap == "binhThuong") {
            $checkPracticeToken = $Database->get_row("SELECT * FROM ontaptuvung WHERE Token = '" .  $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
            if ($checkPracticeToken <= 0) {
                throw new Exception(getMessageError2('Token không tồn tại'));
            }
        }
        $checkToken = $Database->get_row("SELECT * FROM ontaploai1 WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkToken <= 0) {
            throw new Exception(getMessageError2('Token không tồn tại'));
        }
        // Kiểm tra xem có học từ vựng này hay chưa
        $checkHocTuVung = $Database->get_row("select * from hoctuvung where TaiKhoan = '" . $_SESSION['account'] . "' and MaTuVung = '" . $checkToken["MaTuVung"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'");
        if ($checkHocTuVung <= 0) {
            throw new Exception(getMessageError2('Dữ liệu không tồn tại'));
        }


        $getCorrectAnswer = $Database->get_row("select * from tuvung where MaTuVung = '" . $checkToken["MaTuVung"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "'");
        // xóa token câu ôn tập hiện tại
        $Database->query("delete from ontaploai1 where  Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");

        if ($getCorrectAnswer["NoiDungTuVung"] != $userAnswer) {
            // ghi vào hoạt động của người dùng
            $HoatDong->insertHoatDong([
                'TaiKhoan' =>  $_SESSION['account'],
                'TenHoatDong' => 'Ôn tập từ vựng',
                'MaLoaiHoatDong' => '2',
                'NoiDung' => 'Trả lời sai câu ôn tập của từ vựng "' .  $getCorrectAnswer['NoiDungTuVung'] . '"'
            ]);
            $result = array(
                'status' => 'error',
                'message' => getMessageError2('Câu trả lời không chính xác'),
                'data' => array(

                    'noiDungTuVung' =>  $getCorrectAnswer["NoiDungTuVung"]
                )
            );
        } else {
            // Update trạng thái ôn tập cho từ vựng
            $Database->update(
                "hoctuvung",
                [
                    'ThoiGianOnTap' => getTime()
                ],
                "TaiKhoan = '" . $_SESSION['account'] . "' and MaTuVung = '" . $checkToken["MaTuVung"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'"
            );

            // Update tiến trình của phiên ôn tập hiện tại
            $tienTrinh =  $checkPracticeToken["TienTrinh"] + 20;
            $soCauHienTai =  $checkPracticeToken["SoCauHienTai"] + 1;
            $Database->update("ontaptuvung", ['TienTrinh' => $tienTrinh, 'SoCauHienTai' => $soCauHienTai], "Token = '" . $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "' ");


            // Cộng điểm kinh nghiệm cho người dùng
            $Study->updateKinhNghiem($getCorrectAnswer["Diem"], $_SESSION['account']);
            // ghi vào hoạt động của người dùng
            $HoatDong->insertHoatDong([
                'TaiKhoan' =>  $_SESSION['account'],
                'TenHoatDong' => 'Ôn tập từ vựng',
                'MaLoaiHoatDong' => '2',
                'NoiDung' => 'Trả lời đúng câu ôn tập của từ vựng "' .  $getCorrectAnswer['NoiDungTuVung'] . '"'
            ]);
            $result = array(
                'status' => 'success',
                'message' => getMessageSuccess2('Câu trả lời chính xác'),
                'data' => array(
                    'tienTrinh' => $tienTrinh,
                    'noiDungTuVung' =>  $getCorrectAnswer["NoiDungTuVung"]
                )
            );
        }

        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage(),

        );
        return die(json_encode($result));
    }
}
if ($_POST['type'] == 'HienThiDapAn') {
    try {
        $practiceToken = check_string($_POST['practiceToken']);

        $token = check_string($_POST['token']);
        if (empty($token) || empty($practiceToken)) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ dữ liệu'));
        }

        $checkPracticeToken1 = $Database->get_row("SELECT * FROM ontaptuvung WHERE Token = '" .  $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        $checkPracticeToken2 = $Database->get_row("SELECT * FROM ontaptuvungkho WHERE Token = '" .  $practiceToken . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkPracticeToken1 <= 0 && $checkPracticeToken2) {
            throw new Exception(getMessageError2('Token không tồn tại'));
        }
        $checkToken = $Database->get_row("SELECT * FROM ontaploai1 WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkToken <= 0) {
            throw new Exception(getMessageError2('Token không tồn tại'));
        }
        $getCorrectAnswer = $Database->get_row("select * from tuvung where MaTuVung = '" . $checkToken["MaTuVung"] . "' and MaKhoaHoc = '" . $checkToken["MaKhoaHoc"] . "'  and MaBaiHoc = '" . $checkToken["MaBaiHoc"] . "'");


        // ghi vào hoạt động của người dùng
        $HoatDong->insertHoatDong([
            'TaiKhoan' =>  $_SESSION['account'],
            'TenHoatDong' => 'Ôn tập từ vựng',
            'MaLoaiHoatDong' => '2',
            'NoiDung' => 'Hỏi đáp án câu ôn tập của từ vựng "' .  $getCorrectAnswer['NoiDungTuVung'] . '"'
        ]);
        $result = array(
            'status' => 'success',
            'message' => getMessageSuccess2('Câu trả lời chính xác là: ' . $getCorrectAnswer['NoiDungTuVung']),
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

if ($_POST['type'] == 'DanhDauTuKhoOnTap') {
    try {
        $token = check_string($_POST['token']);
        if (empty($token)) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ dữ liệu'));
        }
        $checkTokenOnTap = $Database->get_row("select * from ontaploai1 where TaiKhoan = '" . $_SESSION['account'] . "' and Token = '" . $token . "'");
        if ($checkTokenOnTap <= 0) {
            throw new Exception(getMessageError2('Dữ liệu không tồn tại'));
        }
        // Kiểm tra xem đã học từ vựng này chưa
        $checkHocTuVung = $Database->get_row("select * from hoctuvung where TaiKhoan = '" . $_SESSION['account'] . "' and MaTuVung = '" . $checkTokenOnTap["MaTuVung"] . "'  and MaBaiHoc = '" . $checkTokenOnTap["MaBaiHoc"] . "' and MaKhoaHoc = '" . $checkTokenOnTap["MaKhoaHoc"] . "'");
        if ($checkHocTuVung <= 0) {
            throw new Exception(getMessageError2('Dữ liệu không tồn tại'));
        }
        $getTuVung = $Database->get_row("SELECT * FROM tuvung A inner join khoahoc B on A.MaKhoaHoc = '" . $checkTokenOnTap["MaKhoaHoc"] . "' and A.MaKhoaHoc = B.MaKhoaHoc inner join baihoc C on A.MaBaiHoc = '" . $checkTokenOnTap["MaBaiHoc"] . "' and A.MaBaiHoc = C.MaBaiHoc and A.MaTuVung = '" . $checkTokenOnTap["MaTuVung"] . "'  ");
        if ($checkHocTuVung["TuKho"] == 0) {
            $Study->danhDauTuKho([
                'TaiKhoan' => $_SESSION['account'],
                'MaTuVung' => $checkHocTuVung["MaTuVung"],
                'MaKhoaHoc' => $checkHocTuVung["MaKhoaHoc"],
                'MaBaiHoc' => $checkHocTuVung["MaBaiHoc"],
            ]);
            $HoatDong->insertHoatDong([
                'MaLoaiHoatDong' => 2,
                'TenHoatDong' => 'Đánh dấu từ khó',
                'NoiDung' => 'Đánh dấu từ khó mới: "' . $getTuVung["NoiDungTuVung"] . '" thuộc bài học "' . $getTuVung["TenBaiHoc"] . '" của khóa học "' . $getTuVung["TenKhoaHoc"] . '"',
                'TaiKhoan' => $_SESSION["account"]
            ]);

            $result = array(
                'status' => 'success',
                'message' =>  getMessageSuccess2("Đánh dấu từ khó thành công")

            );
        } else {
            $Study->huyDanhDauTuKho([
                'TaiKhoan' => $_SESSION['account'],
                'MaTuVung' => $checkHocTuVung["MaTuVung"],
                'MaKhoaHoc' => $checkHocTuVung["MaKhoaHoc"],
                'MaBaiHoc' => $checkHocTuVung["MaBaiHoc"],
            ]);
            $HoatDong->insertHoatDong([
                'MaLoaiHoatDong' => 2,
                'TenHoatDong' => 'Hủy đánh dấu từ khó',
                'NoiDung' => 'Hủy đánh dấu từ khó mới: "' . $getTuVung["NoiDungTuVung"] . '" thuộc bài học "' . $getTuVung["TenBaiHoc"] . '" của khóa học "' . $getTuVung["TenKhoaHoc"] . '"',
                'TaiKhoan' => $_SESSION["account"]
            ]);
            $result = array(
                'status' => 'success',
                'message' =>  getMessageSuccess2("Hủy đánh dấu từ khó thành công")

            );
        }
        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage(),

        );
        return die(json_encode($result));
    }
}


if ($_POST['type'] == 'FastPracticeWord') {
    try {

        $maKhoaHoc = check_string($_POST['maKhoaHoc']);
        $maBaiHoc = check_string($_POST['maBaiHoc']);
        $token = check_string($_POST['token']);
        if (empty($maKhoaHoc) || empty($token) || empty($maBaiHoc)) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ dữ liệu'));
        }
        $checkToken = $Database->get_row("SELECT * FROM ontapsieutoctuvung WHERE Token = '" . $token . "' AND `TaiKhoan` = '" . $_SESSION['account'] . "'");
        if ($checkToken <= 0) {
            throw new Exception(getMessageError2('Dữ liệu không tồn tại'));
        }
        // Hoàn thành
        if ($checkToken["SoCauHienTai"] == 20 || $checkToken["SoMang"] <= 0) {
            $result = array(
                'status' => "complete",

            );
            return die(json_encode($result));
        }

        // Lấy danh sách các từ vựng đã học đến thời gian ôn tập, nhưng không nằm trong danh sách bỏ qua
        $listDaHocChuaBoQua = $Database->get_list("select A.ThoiGianOnTap as ThoiGianOnTap, A.TaiKhoan as TaiKhoan, A.MaBaiHoc as MaBaiHoc, A.MaKhoaHoc as MaKhoaHoc, A.MaTuVung as MaTuVung from hoctuvung A left join boquatuvung B on A.MaTuVung = B.MaTuVung and A.MaBaiHoc = B.MaBaiHoc and A.MaKhoaHoc = B.MaKhoaHoc
            and A.TaiKhoan = B.TaiKhoan where A.TaiKhoan = '" . $_SESSION["account"] . "' and A.MaBaiHoc = '" . $maBaiHoc . "' and A.MaKhoaHoc = '" . $maKhoaHoc . "'  and B.TaiKhoan is NULL and B.MaTuVung is NULL and B.MaBaiHoc is NULL and B.MaKhoaHoc is NULL order by ThoiGianOnTap asc");

        $listDaHocChuaBoQua = array_slice($listDaHocChuaBoQua, 0, 20);
        $randomType = 1;
        if ($randomType == 1) {
            // Nếu không còn từ vựng để ôn thì complete trạng thái
            if (count($listDaHocChuaBoQua) == 0) {
                $result = array(
                    'status' => "complete",
                );
                return die(json_encode($result));
            }
            $word = reset($listDaHocChuaBoQua);
            $word = $Database->get_row("SELECT * FROM tuvung WHERE MaBaiHoc = '" . $maBaiHoc . "' AND MaKhoaHoc = '" . $maKhoaHoc . "' AND MaTuVung = '" . $word["MaTuVung"] . "' ");

            // Lấy 3 từ vựng random (ngoại trừ từ để ôn)
            $getRandomAnswer = $Database->get_list("select * from tuvung WHERE MaBaiHoc = '" . $maBaiHoc . "' AND MaKhoaHoc = '" . $maKhoaHoc . "' AND MaTuVung != '" . $word["MaTuVung"] . "' ORDER BY RAND() limit 3");
            // Nếu không đủ 3 từ vựng random thì ta bù vào bằng từ chính
            $soLuongRandomAnswer = count($getRandomAnswer);
            if (count($getRandomAnswer) < 3) {
                for ($i = 0; $i < 3 - $soLuongRandomAnswer; $i++) {
                    array_push($getRandomAnswer, $word);
                }
            }


            // kết hợp 3 đáp án random với đáp án chính xác
            array_push($getRandomAnswer, $word);
            // Sắp xếp ranndom các lựa chọn
            shuffle($getRandomAnswer);

            // Tạo token cho câu ôn tập
            $tokenOnTap = randomString('0123456789QWERTYUIOPASDGHJKLZXCVBNM', '20');
            $Database->insert("ontaploai1", [
                'TaiKhoan' => $_SESSION["account"],
                'Token' => $tokenOnTap,
                'MaTuVung' => $word["MaTuVung"],
                'MaBaiHoc' => $word["MaBaiHoc"],
                'MaKhoaHoc' => $word["MaKhoaHoc"],

            ]);
            $soMang = $checkToken["SoMang"];
            $soCauDung = $checkToken["SoCauDung"];
            $soCauHienTai = $checkToken["SoCauHienTai"];
            $result = array(
                'status' => "success",
                'data' => array(
                    'tokenOnTap' => $tokenOnTap,
                    'type' => $randomType,
                    'data' => $word,
                    'randomAnswer' => ($getRandomAnswer),
                    'soMang' => $soMang,
                    'soCauDung' => $soCauDung,
                    'soCauHienTai' => $soCauHienTai,
                ),
            );
        }
        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage(),

        );
        return die(json_encode($result));
    }
}
