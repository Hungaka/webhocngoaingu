<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");
require_once(__DIR__ . "/../../vendor/google-api/vendor/autoload.php");
// Login with Google

$client = new Google_Client();
$client->setClientId(GOOGLE_APP_ID);
$client->setClientSecret(GOOGLE_APP_SECRET);
$client->setRedirectUri(GOOGLE_APP_CALLBACK_URL);
$client->addScope("email");
$client->addScope("profile");
if (isset($_GET['code'])) {
  try {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;
    $picture =  $google_account_info->picture;
    $check = $Database->get_row(" SELECT * FROM `nguoidung` WHERE `Email` = '$email' ");
    if ($check) {
      $_SESSION["account"] = $check["TaiKhoan"];
      $BASE_URL = BASE_URL("Page/Home");
      header("Location: {$BASE_URL}");
      exit;
    } else {
      // Create new user

      $randomNumber = randomString('0123456789', '6');
      $removeAllSpecialCharacterAccount = strtolower(xoaDauCach(removeSpecialCharacter(stripUnicode($name))));
      $generateAccount =  $removeAllSpecialCharacterAccount . $randomNumber;
      $checkExistAccount =  $Database->get_row(" SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '$generateAccount'");
      while ($checkExistAccount) {
        $randomNumber = randomString('0123456789', '6');
        $generateAccount =  $removeAllSpecialCharacterAccount . $randomNumber;
        $checkExistAccount =  $Database->get_row(" SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '$generateAccount'");
      }
      $randomPassword = randomString('0123456789zxcvbnmasdfghjklqwerttyuiopQWERTYUIOPASDGHJKLZXCVBNM', '10');
      $Database->insert("nguoidung", [
        'TaiKhoan' => $generateAccount,
        'Email' => $email,
        'TenHienThi' => $name,
        'AnhDaiDien' => $picture,
        'MatKhau' => encryptPassword($randomPassword),
        'IPAddress' => myIP(),
        'MaQuyenHan' => 1,
        'MaMucTieu' => 1,
        'KichHoatEmail' => 1,

      ]);
      $Database->insert("thongbaoemail", [
        'TaiKhoan' => $generateAccount,
      ]);

      // Gửi mail thông tin tài khoản cho người đùng đăng ký mới
      $guitoi = $email;
      $subject = 'Thông tin tài khoản đăng nhập trên ' . $Database->site('TenWeb');
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
                        <td align="center" style="Margin:0;padding-bottom:10px;padding-left:20px;padding-right:20px;padding-top:30px"><h1 style="Margin:0;line-height:48px;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;font-size:40px;font-style:normal;font-weight:normal;color:#ffffff"><strong>Thông tin tài khoản đăng nhập</strong></h1></td>
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
                        <td align="left" style="padding:0;Margin:0;padding-top:5px;padding-bottom:5px"><h3 style="Margin:0;line-height:24px;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#2d033a">Xin chào ' . $name . ',</h3><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px"><br></p><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px">Cảm ơn bạn đã đăng ký tham gia nền tảng học ngoại ngữ online bằng tài khoản Google, sau đây là thông tin đăng nhập của bạn:</p><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px"><br></p><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px;margin-left:40px"><strong>Tài khoản: </strong><strong></strong>' . $generateAccount . '<br><strong>Mật khẩu:</strong> ' . $randomPassword . '</p><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px"><br></p><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px">Lưu ý rằng: bạn có thể dùng tài khoản và mật khẩu được cấp ở trên đăng nhập hoặc đăng nhập trực tiếp vào hệ thống bằng Google.<br><br></p><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#38363a;font-size:14px">Chúc bạn có một ngày làm việc và học tập vui vẻ,<br>5Fs Group.</p></td>
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
                        <td align="center" style="padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-top:15px"><p style="Margin:0;font-family:open sans,helvetica neue,helvetica,arial,sans-serif;line-height:21px;color:#ffffff;font-size:14px"><strong>5Fs Group - Nền tảng học ngoại ngữ online</strong></p></td>
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
      $_SESSION["account"] = $generateAccount;
      $BASE_URL = BASE_URL("Page/Home");
      header("Location: {$BASE_URL}");
      exit;
    }
  } catch (Exception $err) {
    echo $err->getMessage();
    $BASE_URL = BASE_URL("Auth/DangNhap");
    header("Location: {$BASE_URL}");
    exit;
  }
}
