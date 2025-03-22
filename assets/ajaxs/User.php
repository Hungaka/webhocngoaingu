<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");



if (empty($_POST['type'])) {
    $result = array(
        'status' => 'error',
        'message' => 'Dữ liệu không tồn tại'
    );
    return die(json_encode($result));
}
if (!isset($_SESSION["account"])) {
    $result = array(
        'status' => 'error',
        'message' => 'Vui lòng đăng nhập vào hệ thống'
    );
    return die(json_encode($result));
}
checkAccountExist();
if ($_POST['type'] == 'GetTienDoHocTap') {
    try {
        $taiKhoan = check_string($_POST['taiKhoan']);
        if (empty($taiKhoan)) {
            throw new Exception(getMessageError2('Vui lòng điền đủ dữ liệu'));
        }
        $checkTaiKhoan = $Database->get_row("SELECT * FROM nguoidung WHERE TaiKhoan = '" . $taiKhoan . "'");
        if ($checkTaiKhoan <= 0) {
            throw new Exception(getMessageError2('Nguời dùng không tồn tại'));
        }
        // Lấy dữ liệu học tập từ 7 ngày gần nhất
        $resultTienTrinhHocTap = array();
        for ($i = 6; $i >= 0; $i--) {
            $getDay = date("Y-m-d", time() - 60 * 60 * 24 * $i);
            $getData = $Database->num_rows("select * from hoctuvung where TaiKhoan = '" . $taiKhoan . "' and DATE(ThoiGian) = '" . $getDay . "' ");
            array_push($resultTienTrinhHocTap, $getData);
        }
        $result = array(
            'status' => 'success',
            'message' => getMessageSuccess2('Thành công'),
            'data' => $resultTienTrinhHocTap
        );
        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage()
        );
        return die(json_encode($result));
    }
}
if ($_POST['type'] == 'sendEmailActive') {
    try {
        if (!isset($_POST['email'])) {
            throw new Exception(getMessageError2('Vui lòng điền đủ dữ liệu'));
        }
        $email = check_string($_POST['email']);
        if (empty($email)) {
            throw new Exception(getMessageError2('Vui lòng điền đủ dữ liệu'));
        }
        if (!checkEmail($email)) {
            throw new Exception(getMessageError2('Email không đúng định dạng'));
        }
        $checkTaiKhoan = $Database->get_row("SELECT * FROM nguoidung WHERE TaiKhoan = '" . $_SESSION["account"] . "'");
        if ($checkTaiKhoan["KichHoatEmail"] == 1) {
            throw new Exception(getMessageError2('Bạn đã kích hoạt email rồi'));
        }
        $checkEmail = $Database->get_row("SELECT * FROM nguoidung WHERE Email = '" . $email . "'");
        if ($checkEmail > 0 && $checkEmail["TaiKhoan"] != $_SESSION["account"]) {
            throw new Exception(getMessageError2('Email đã có người sử dụng'));
        }
        // Tạo access token
        $randomToken = randomString('0123456789QWERTYUIOPASDGHJKLZXCVBNM', '6');
        // Update token cho người dùng
        $Database->update("nguoidung", [
            'Email' => $email,
            'TokenKichHoatEmail' =>  $randomToken,
            'ThoiGianTokenKichHoatEmail' => getTime()
        ], "TaiKhoan = '" . $_SESSION["account"] . "' ");

        // gửi mail kích hoạt 
        $linkActiveEmail = BASE_URL("active_email.php?account=" . $_SESSION["account"] . "&token=" . encrypt_decrypt("encrypt", $randomToken));
        $guitoi = $email;
        $subject = 'Kích hoạt email tài khoản ' . $Database->site('TenWeb');
        $bcc = $Database->site('TenWeb');
        $hoten = $Database->site('TenWeb');
        $noi_dung = '<div style="width:100%;font-family:arial,"helvetica neue",helvetica,sans-serif;padding:0;Margin:0">
    <div style="background-color:#ebedee">
     <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#ebedee">
       <tbody><tr height="0">
        <td style="padding:0;Margin:0">
         <table cellpadding="0" cellspacing="0" border="0" align="center" style="border-collapse:collapse;border-spacing:0px;width:600px">
           <tbody><tr>
            <td cellpadding="0" cellspacing="0" border="0" height="0" style="padding:0;Margin:0;line-height:1px;min-width:600px"><img src="https://ci3.googleusercontent.com/proxy/mEwrBLRqRs4U3ih0k5bwvexxwbixRuiyxCpDdvF5a6Gs8XIJMxed2-RgcT-zgcGjse9m9272dPTp9X6kSFXHCyMJNFtQN6eivXkDbCfNPHk=s0-d-e1-ft#https://esputnik.com/repository/applications/images/blank.gif" width="600" height="1" style="display:block;border:0;outline:none;text-decoration:none;max-height:0px;min-height:0px;min-width:600px;width:600px" alt="" class="CToWUd" data-bit="iit"></td>
           </tr>
         </tbody></table></td>
       </tr>
       <tr>
        <td valign="top" style="padding:0;Margin:0">
         <table cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse;border-spacing:0px;table-layout:fixed!important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
           <tbody><tr>
            <td align="center" style="padding:0;Margin:0">
             <table bgcolor="#ffffff" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;background-color:#ffffff;width:600px">
               <tbody><tr>
                <td align="left" style="padding:20px;Margin:0;border-radius:10px">
                 <table cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse;border-spacing:0px;float:left">
                   <tbody><tr>
                    <td valign="top" align="center" style="padding:0;Margin:0;width:241px">
                     <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-collapse:collapse;border-spacing:0px">
                       <tbody><tr>
                        <td align="left" style="padding:0;Margin:0"><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:42px;color:#38363a;font-size:28px"><strong>' . $Database->site('TenWeb') . '</strong></p></td>
                       </tr>
                     </tbody></table></td>
                   </tr>
                 </tbody></table>
                 <table cellpadding="0" cellspacing="0" align="right" style="border-collapse:collapse;border-spacing:0px">
                   <tbody><tr>
                    <td align="left" style="padding:0;Margin:0;width:299px">
                     <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-collapse:collapse;border-spacing:0px">
                       <tbody><tr>
                        <td style="padding:0;Margin:0">
                         <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-collapse:collapse;border-spacing:0px">
                           <tbody><tr>
                            <td align="center" valign="top" width="100%" style="Margin:0;padding-left:5px;padding-right:5px;padding-top:10px;padding-bottom:0px;border:0" id="m_-2865070109667270028m_-3977623148845986925esd-menu-id-0"><a href="' . $Database->site('BASE_URL') . '" style="text-decoration:none;display:block;font-family: open sans,helvetica neue,helvetica,arial,sans-serif;color:#235390;font-size:18px" target="_blank" data-saferedirecturl="' . $Database->site('BASE_URL') . '">Học ngoại ngữ trực tuyến<img src="https://ci3.googleusercontent.com/proxy/T1uoWPDirzvfujUFeejbE7fCqioQzuTi_AsDJLeR5YV7ovkvyHCTjLGuzpNQHCAUqRPmhcbc3S_SUE1zG9Ri57un2tbSLRYCnnfDK4kxN-FsDblCrh3D_YYO4s04VCzHMW3hxzibTRyPDYkA5cbaTzHLieLeThJYsrS7wg0rETrso2_4W5kiLSewxHE3bKmOi1wOPH5j7vINMU7k=s0-d-e1-ft#https://gwiknr.stripocdn.email/content/guids/CABINET_a9ff702ef502a8e186be95bba0bd07842315a49708d79afadde0057938bd698e/images/gf5ugjs.png" alt="Học ngoại ngữ trực tuyến" title="Học ngoại ngữ trực tuyến" align="absmiddle" width="42" style="display:inline-block!important;border:0;outline:none;text-decoration:none;padding-left:15px;vertical-align:middle" class="CToWUd" data-bit="iit"></a></td>
                           </tr>
                         </tbody></table></td>
                       </tr>
                     </tbody></table></td>
                   </tr>
                 </tbody></table></td>
               </tr>
             </tbody></table></td>
           </tr>
         </tbody></table>
         <table cellspacing="0" cellpadding="0" align="center" style="border-collapse:collapse;border-spacing:0px;table-layout:fixed!important;width:100%">
           <tbody><tr>
            <td align="center" style="padding:0;Margin:0">
             <table style="border-collapse:collapse;border-spacing:0px;background-color:#ffffff;width:600px" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
               <tbody><tr>
                <td align="left" style="padding:40px;Margin:0">
                 <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-spacing:0px">
                   <tbody><tr>
                    <td align="center" valign="top" style="padding:0;Margin:0;width:520px">
                     <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#235390" style="border-collapse:separate;border-spacing:0px;background-color:#235390;border-radius:20px" role="presentation">
                       <tbody><tr>
                        <td align="center" style="Margin:0;padding-bottom:10px;padding-left:20px;padding-right:20px;padding-top:30px"><h1 style="Margin:0;line-height:48px;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;font-size:40px;font-style:normal;font-weight:normal;color:#ffffff"><strong>Kích hoạt tài khoản email</strong></h1></td>
                       </tr>
                       <tr>
                        <td align="center" style="padding:0;Margin:0;padding-bottom:30px"><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:24px;color:#ffffff;font-size:16px">cho việc học ngoại ngữ của bạn!</p></td>
                       </tr>
                     </tbody></table></td>
                   </tr>
                 </tbody></table></td>
               </tr>
               <tr>
                <td align="left" style="padding:0;Margin:0;padding-bottom:40px;padding-left:40px;padding-right:40px">
                 <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-spacing:0px">
                   <tbody><tr>
                    <td align="center" valign="top" style="padding:0;Margin:0;width:520px">
                     <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-collapse:collapse;border-spacing:0px">
                       <tbody><tr>
                        <td align="left" style="padding:0;Margin:0;padding-top:5px;padding-bottom:5px"><h3 style="Margin:0;line-height:24px;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#2d033a">Xin chào ' . $_SESSION["account"] . ',</h3>
                        <p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px"><br></p>
                        <p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px">Bạn đã yêu cầu xác thực tài khoản. Nếu là bạn, hãy click vào link dưới đây để tiến hành xác thực email:</p>
                        <p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px"><br></p>
                        <p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px">  <a href="' . $linkActiveEmail . '">Click vào đây</a></p>
                        <p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px"><br></p>

                        <p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px"><br></p><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px">Lưu ý rằng: link chỉ tồn tại trong vòng 10 phút kể từ lúc được gửi<br><br></p>
                        <p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px">Chúc bạn có một ngày làm việc và học tập vui vẻ,<br>' . $Database->site('TenWeb') . '.</p></td>
                       </tr>
                       <tr>
                        <td align="center" style="padding:0;Margin:0;padding-top:20px"><span style="border-style:solid;border-color:#2cb543;background:#58cc02;border-width:0px;display:inline-block;border-radius:30px;width:auto"><a href="' . $Database->site('BASE_URL') . '" style="text-decoration:none;color:#ffffff;font-size:18px;display:inline-block;background:#58cc02;border-radius:30px;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;font-weight:bold;font-style:normal;line-height:22px;width:auto;text-align:center;padding:10px 20px 10px 20px;border-color:#58cc02" target="_blank" data-saferedirecturl="' . $Database->site('BASE_URL') . '">Bắt đầu học</a></span></td>
                       </tr>
                     </tbody></table></td>
                   </tr>
                 </tbody></table></td>
               </tr>
             </tbody></table></td>
           </tr>
         </tbody></table>
         <table cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse;border-spacing:0px;table-layout:fixed!important;width:100%">
           <tbody><tr>
            <td align="center" style="padding:0;Margin:0">
             <table bgcolor="#ffffff" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border-spacing:0px;background-color:#ffffff;width:600px">
               <tbody><tr>
                <td align="left" bgcolor="#235390" style="Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;padding-bottom:30px;background-color:#235390;border-radius:0px 0px 10px 10px">
                 <table cellpadding="0" cellspacing="0" align="right" style="border-collapse:collapse;border-spacing:0px;float:right">
                   <tbody><tr>
                    <td align="center" valign="top" style="padding:0;Margin:0;width:257px">
                     <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-collapse:collapse;border-spacing:0px">
                       <tbody><tr>
                        <td align="center" style="padding:0;Margin:0;font-size:0px"><a href="https://viewstripo.email" style="text-decoration:none;color:#3b8026;font-size:14px" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://viewstripo.email&amp;source=gmail&amp;ust=1681993063118000&amp;usg=AOvVaw0v1sfB7onb9X7ZukqlRNGq"><img src="https://ci6.googleusercontent.com/proxy/ZoYDApj1vbmf9nbW6z7bAN8PymCSmXEdoRrMRKaKFkGCGKr2l0HQkRkTeaelUEbAaBp6TinjfCKSEES-9JLSZxnEXbaYxEoOy1ecwLo1hs0jXbYVxjOAzYF9dwr-TkYN335odFCVJuoHdoz9U2TTTJY9c0VQDh7djxk9sx7LVhDTctaaqN-Dckv5yzS1JLHJaDjU_bGNhh3NGKD4=s0-d-e1-ft#https://gwiknr.stripocdn.email/content/guids/CABINET_a9ff702ef502a8e186be95bba0bd07842315a49708d79afadde0057938bd698e/images/dgncisl.png" alt="" style="display:block;border:0;outline:none;text-decoration:none;border-radius:10px" width="257" class="CToWUd" data-bit="iit"></a></td>
                       </tr>
                     </tbody></table></td>
                   </tr>
                 </tbody></table>
                 <table cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse;border-spacing:0px;float:left">
                   <tbody><tr>
                    <td align="left" style="padding:0;Margin:0;width:298px">
                     <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="border-collapse:collapse;border-spacing:0px">
                       <tbody><tr>
                        <td align="center" style="padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-top:15px"><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#ffffff;font-size:14px"><strong>' . $Database->site('TenWeb') . ' - Nền tảng học ngoại ngữ online</strong></p></td>
                       </tr>
                       <tr>
                        <td align="center" style="padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-top:15px"><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#ffffff;font-size:14px">About us</p></td>
                       </tr>
                       <tr>
                        <td align="center" style="padding:0;Margin:0;padding-top:20px"><span style="border-style:solid;border-color:#2cb543;background:#58cc02;border-width:0px;display:inline-block;border-radius:30px;width:auto"><a href="' . $Database->site('BASE_URL') . '" style="text-decoration:none;color:#ffffff;font-size:18px;display:inline-block;background:#58cc02;border-radius:30px;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;font-weight:bold;font-style:normal;line-height:22px;width:auto;text-align:center;padding:10px 20px 10px 20px;border-color:#58cc02" target="_blank" data-saferedirecturl="' . $Database->site('BASE_URL') . '">Học ngay</a></span></td>
                       </tr>
                     </tbody></table></td>
                   </tr>
                 </tbody></table></td>
               </tr>
             </tbody></table></td>
           </tr>
         </tbody></table></td>
       </tr>
     </tbody></table><div class="yj6qo"></div><div class="adL">
    </div></div><div class="adL">
   </div></div>';

        $sendMail = sendCSM($guitoi, $hoten, $subject, $noi_dung, $bcc);
        if ($sendMail) {
            // thêm vào hoạt động 
            $HoatDong->insertHoatDong([
                'MaLoaiHoatDong' => 3,
                'TenHoatDong' => 'Cập nhật tài khoản',
                'NoiDung' => 'Gửi mail kích hoạt tài khoản',
                'TaiKhoan' => $_SESSION["account"]
            ]);
            $result = array(
                'status' => 'success',
                'message' => getMessageSuccess2('Đã gửi mail kích hoạt hành công, vui lòng kiểm tra hộp thư (bao gồm thư rác, quảng cáo,..)'),
            );
        } else {
            $result = array(
                'status' => 'error',
                'message' => getMessageError2('Không gửi được email'),
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
if ($_POST['type'] == 'updateThongTin') {
    try {
        if (!isset($_POST['tenHienThi'])) {
            throw new Exception(getMessageError2('Vui lòng điền tên hiển thị'));
        }
        $tenHienThi = check_string($_POST['tenHienThi']);
        if ($_FILES) {
            $anhDaiDien = $_FILES['avatarProfile'];
        } else {
            $anhDaiDien = null;
        }
        // Nếu không có chọn ảnh đại diện
        if (empty($anhDaiDien)) {
            $checkValidTenHienThi = $User->checkValidTenHienThi($tenHienThi);
            if ($checkValidTenHienThi["status"] == false) {
                throw new Exception(getMessageError2($checkValidTenHienThi["message"]));
            }
            $Database->update("nguoidung", [
                'TenHienThi' => $tenHienThi
            ], "TaiKhoan = '" . $_SESSION["account"] . "' ");
            // thêm vào hoạt động 
            $HoatDong->insertHoatDong([
                'MaLoaiHoatDong' => 3,
                'TenHoatDong' => 'Cập nhật tài khoản',
                'NoiDung' => 'Cập nhật thông tin tài khoản',
                'TaiKhoan' => $_SESSION["account"]
            ]);
            $result = array(
                'status' => 'success',
                'message' => getMessageSuccess2('Cập nhật thông tin thành công'),
            );
        } else 
        if ($anhDaiDien) {
            $checkValidTenHienThi = $User->checkValidTenHienThi($tenHienThi);
            if ($checkValidTenHienThi["status"] == false) {
                throw new Exception(getMessageError2($checkValidTenHienThi["message"]));
            }
            $path = __DIR__ . '/../uploads/'; // upload directory
            $img = $anhDaiDien['name'];
            $tmp = $anhDaiDien['tmp_name'];
            $final_image = rand(1000, 1000000) . $img;
            if (checkImg($anhDaiDien)) {
                $path = $path . strtolower($final_image);
                $pathDatabase = BASE_URL("assets/uploads/" . $final_image);
                if (move_uploaded_file($tmp, $path)) {
                    $Database->update("nguoidung", [
                        'TenHienThi' => $tenHienThi,
                        'AnhDaiDien' =>  strtolower($pathDatabase)
                    ], "TaiKhoan = '" . $_SESSION["account"] . "' ");
                    // thêm vào hoạt động 
                    $HoatDong->insertHoatDong([
                        'MaLoaiHoatDong' => 3,
                        'TenHoatDong' => 'Cập nhật tài khoản',
                        'NoiDung' => 'Cập nhật thông tin tài khoản',
                        'TaiKhoan' => $_SESSION["account"]
                    ]);
                    $result = array(
                        'status' => 'success',
                        'message' => getMessageSuccess2('Cập nhật thông tin thành công'),
                    );
                }
            } else {
                $result = array(
                    'status' => 'error',
                    'message' => getMessageError2('Định dạng file ảnh không hợp lệ'),
                );
            }
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

if ($_POST['type'] == 'thayDoiMatKhau') {
    try {
        if (!isset($_POST['oldPassword']) || !isset($_POST['newPassword'])) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ thông tin'));
        }
        $oldPassword = check_string($_POST['oldPassword']);
        $newPassword = check_string($_POST['newPassword']);
        $checkValidOldPassword = $User->checkValidPassword($oldPassword);
        if ($checkValidOldPassword["status"] == false) {
            throw new Exception(getMessageError2($checkValidOldPassword["message"]));
        }
        $checkValidNewPassword = $User->checkValidPassword($newPassword);
        if ($checkValidNewPassword["status"] == false) {
            throw new Exception(getMessageError2($checkValidNewPassword["message"]));
        }

        if ($oldPassword == $newPassword) {
            throw new Exception(getMessageError2('Mật khẩu mới không được trùng với mật khẩu cũ'));
        }
        $checkUser = $Database->get_row("select * from nguoidung where TaiKhoan = '" . $_SESSION["account"] . "' ");
        if (md5($oldPassword) != $checkUser["MatKhau"]) {
            throw new Exception(getMessageError2('Mật khẩu cũ không chính xác'));
        }
        $Database->update("nguoidung", [
            'MatKhau' => md5($newPassword)
        ], "TaiKhoan = '" . $_SESSION["account"] . "' ");
        // thêm vào hoạt động 
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 3,
            'TenHoatDong' => 'Cập nhật tài khoản',
            'NoiDung' => 'Cập nhật mật khẩu tài khoản',
            'TaiKhoan' => $_SESSION["account"]
        ]);
        $result = array(
            'status' => 'success',
            'message' => getMessageSuccess2('Cập nhật mật khẩu thành công'),
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
if ($_POST['type'] == 'thongBaoEmail') {
    try {
        if (!isset($_POST['capNhatMoi']) || !isset($_POST['baoCaoTienTrinhHocTap']) || !isset($_POST['nhacNhoTienTrinhHocTap'])) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ thông tin'));
        }
        $capNhatMoi = check_string($_POST['capNhatMoi']);
        $baoCaoTienTrinhHocTap = check_string($_POST['baoCaoTienTrinhHocTap']);
        $nhacNhoTienTrinhHocTap = check_string($_POST['nhacNhoTienTrinhHocTap']);

        $checkUser = $Database->get_row("select * from thongbaoemail where TaiKhoan = '" . $_SESSION["account"] . "' ");
        if (!$checkUser) {
            throw new Exception(getMessageError2('Lỗi hệ thống'));
        }
        $Database->update("thongbaoemail", [
            'CapNhatMoi' => $capNhatMoi,
            'BaoCaoTienTrinhHocTap' => $baoCaoTienTrinhHocTap,
            'NhacNhoTienTrinhHocTap' => $nhacNhoTienTrinhHocTap,
        ], "TaiKhoan = '" . $_SESSION["account"] . "' ");
        // thêm vào hoạt động 
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 3,
            'TenHoatDong' => 'Cập nhật tài khoản',
            'NoiDung' => 'Cập nhật thông báo nhận email',
            'TaiKhoan' => $_SESSION["account"]
        ]);
        $result = array(
            'status' => 'success',
            'message' => getMessageSuccess2('Cập nhật thành công'),
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

if ($_POST['type'] == 'capNhatMucTieuHocTap') {
    try {
        if (!isset($_POST['maMucTieuHocTap'])) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ thông tin'));
        }
        $maMucTieu = check_string($_POST['maMucTieuHocTap']);
        if (empty($maMucTieu)) {
            throw new Exception(getMessageError2('Vui lòng điền đầy đủ thông tin'));
        }
        $checkMucTieu = $Database->get_row("select * from muctieuhoctap where MaMucTieu = '" . $maMucTieu . "' ");
        if ($checkMucTieu <= 0) {
            throw new Exception(getMessageError2('Mục tiêu học tập không tồn tại'));
        }
        $Database->update("nguoidung", [
            'MaMucTieu' => $maMucTieu
        ], "TaiKhoan = '" . $_SESSION["account"] . "' ");
        // thêm vào hoạt động 
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 3,
            'TenHoatDong' => 'Cập nhật tài khoản',
            'NoiDung' => 'Cập nhật mục tiêu học tập sang "' . $checkMucTieu["TenMucTieu"] . ' ."',
            'TaiKhoan' => $_SESSION["account"]
        ]);
        $result = array(
            'status' => 'success',
            'message' => getMessageSuccess2('Cập nhật mục tiêu học tập thành công'),
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

if ($_POST['type'] == 'GetHoatDongTaiKhoan') {
    try {
        $baseURL = BASE_URL("assets/ajaxs/User.php");
        $offset = !empty($_POST['page']) ? $_POST['page'] : 0;
        $limit = 5;
        $typeAjax = 'GetHoatDongTaiKhoan';
        // Count of all records 
        $rowCount = $Database->num_rows(" SELECT * FROM hoatdong WHERE TaiKhoan = '" . $_SESSION["account"] . "'");

        // Initialize pagination class 
        $pagConfig = array(
            'baseURL' => $baseURL,
            'totalRows' => $rowCount,
            'perPage' => $limit,
            'typeAjax' => $typeAjax,
            'currentPage' => $offset,
            'contentDiv' => 'profile__active-container'
        );
        $pagination =  new Pagination($pagConfig);
        foreach ($Database->get_list("SELECT * FROM hoatdong INNER JOIN loaihoatdong on hoatdong.TaiKhoan = '" . $_SESSION["account"] . "' AND hoatdong.MaLoaiHoatDong = loaihoatdong.MaLoaiHoatDong ORDER BY ThoiGian DESC LIMIT $offset,$limit") as $hoatDong) {

?>
            <div class="profile__active-content">
                <div class="profile__active-content-left">
                    <div class="profile__active-content-title"><?= $hoatDong["TenHoatDong"] ?></div>
                    <div class="profile__active-content-text"><?= $hoatDong["NoiDung"] ?></div>

                    <div class="profile__active-content-time"><?= timeAgo($hoatDong["ThoiGian"]) ?></div>
                </div>

                <img src="<?= $hoatDong["LinkAnh"] ?>" alt="" class="profile__active-content-img">
            </div>
        <?php

        }
        ?>
        <div class="profile__pagination">
            <div class="profile__pagination-list">
                <?php echo $pagination->createLinks(); ?>
            </div>
        </div>
<?php

    } catch (Exception $err) {
    }
}

if ($_POST['type'] == 'getListBXH') {
    try {
        if (!isset($_POST["typeBXH"])) {
            throw new Exception(getMessageError2('Vui lòng điền đủ dữ liệu'));
        }
        $typeBXH = check_string($_POST["typeBXH"]);
        $itemsPerPage = check_string($_POST["itemsPerPage"]);
        $currentPage = check_string($_POST["currentPage"]);
        if (empty($typeBXH)) {
            throw new Exception(getMessageError2('Vui lòng điền đủ dữ liệu'));
        }
        $skipResult = ($currentPage - 1) *  $itemsPerPage;
        $getList = array();
        if ($typeBXH == "tabCapDo") {
            $getList = $Database->get_list(" SELECT TaiKhoan, AnhDaiDien, TenHienThi, CapDo as SoLuong from nguoidung order by CapDo desc, KinhNghiem desc limit $skipResult,  $itemsPerPage ");
        } else if ($typeBXH == "tabTuVung") {
            $getList = $Database->get_list(" SELECT A.AnhDaiDien, A.TaiKhoan, A.TenHienThi, count(B.TaiKhoan) as SoLuong FROM nguoidung A inner join hoctuvung B on A.TaiKhoan = B.TaiKhoan group by B.TaiKhoan order by SoLuong desc limit $skipResult,  $itemsPerPage ");
        }
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
