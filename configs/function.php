<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once(__DIR__ . '/../vendor/PHPMailer/src/Exception.php');
require_once(__DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php');
require_once(__DIR__ . '/../vendor/PHPMailer/src/SMTP.php');

$config = [
    'project' => '5FsGroup',
    'url' => BASE_URL,
    'version' => '1.0.0',
    'ip_server' => ''
];

function checkLogin()
{
    global $Database;
    if (!isset($_SESSION["account"])) {
        return die('<script type="text/javascript">
            setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangNhap') . '" }, 0);
            </script>
            ');
    } else {
        checkAccountExist();
        $row = $Database->get_row("SELECT * FROM `dangkykhoahoc` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "'  ");
        if (!$row) {
            return die('<script type="text/javascript">
                setTimeout(function(){ location.href = "' . BASE_URL('Page/KhoiTaoTaiKhoan') . '" }, 0);
                </script>
                ');
        }
    }
}

function checkAccountExist()
{
    global $Database;
    if (isset($_SESSION["account"])) {
        $row = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "'  ");
        if (!$row) {
            return die('<script type="text/javascript">
                setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangXuat') . '" }, 0);
                </script>
                ');
        } else {
            if ($row["TrangThai"] == 0) {
                return die('<script type="text/javascript">toastr.error("Tài khoản đã bị cấm", "Lỗi hệ thống!");
                setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangXuat') . '" }, 1000);</script>');
            }
        }
    }
}


function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = '566d7ca789ea4196909d2e703242fef5a00dc85d8fea7bee8733b31b4019f335'; // 32 characters
    $secret_iv = 'ab55b36ebf363980d89d4e72434333cb'; // 16 characters
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes 
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
function hashString($plaintext)
{
    $cipher = "aes-256-ctr";
    $key = '566d7ca789ea4196909d2e703242fef5a00dc85d8fea7bee8733b31b4019f335';
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
        return $ciphertext;
    }
    return null;
}
function unHashString($ciphertext)
{
    $cipher = "aes-256-ctr";
    $key = '566d7ca789ea4196909d2e703242fef5a00dc85d8fea7bee8733b31b4019f335';
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options = 0, $iv, $tag = null);
        return $original_plaintext;
    }
    return null;
}

function stripUnicode($str)
{
    if (!$str) return false;
    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
    );
    foreach ($unicode as $nonUnicode => $uni)
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    return $str;
}

function removeItemDuplicate($array, $key)
{
    $found = [];
    foreach ($array as $index => [$key => $ref]) {
        if (!isset($found[$ref])) {
            $found[$ref] = $index;
        } else {
            unset($array[$index], $array[$found[$ref]]);
        }
    }
    return $array;
}
function uniqueMultiArray($array, $key)
{
    // key = MaTuVung
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

function checkRequireCapDo($capdo)
{
    $result = 0;
    switch ($capdo) {
        case 2:
            $result = 100;
            break;
        case 3:
            $result = 200;
            break;
        case 4:
            $result = 300;
            break;
        case 5:
            $result = 500;
            break;
        case 6:
            $result = 1000;
            break;
        case 7:
            $result = 2000;
            break;
        case 8:
            $result = 3000;
            break;
        case 9:
            $result = 5000;
            break;
        case 10:
            $result = 10000;
            break;
        case 11:
            $result = 20000;
            break;
        case 12:
            $result = 30000;
            break;
        case 13:
            $result = 100000;
            break;
        case 14:
            $result = 200000;
            break;
        case 15:
            $result = 500000;
            break;
        case 16:
            $result = 1500000;
            break;
        case 17:
            $result = 5000000;
            break;
        case 18:
            $result = 10000000;
            break;
        case 19:
            $result = 30000000;
            break;
        case 20:
            $result = 1000000000;
            break;
        default:
            $result = 0;
    }
    return $result;
}
function formatNumber($number)
{
    return number_format($number, 0, '.', '.');
}
function updateCapDo()
{
    try {

        global $Database;
        if (isset($_SESSION["account"])) {
            $taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
            if (!$taikhoan) {
                throw new Exception("Không tồn tại tài khoản trong hệ thống");
            }
            $kinhnghiemhientai = $taikhoan["KinhNghiem"];
            $capdo = $taikhoan["CapDo"];
            while ($kinhnghiemhientai >= checkRequireCapDo($capdo + 1) && $capdo < 20) {
                $Database->query("UPDATE `nguoidung` SET `KinhNghiem` = `KinhNghiem` - '" . checkRequireCapDo($capdo + 1) . "', `CapDo` = `CapDo` + 1  WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
                $taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
                $kinhnghiemhientai =  $taikhoan["KinhNghiem"];
                $capdo = $taikhoan["CapDo"];
            }
        }
    } catch (Exception $err) {
        return die('<script type="text/javascript">toastr.error("' . $err->getMessage() . '", "Lỗi hệ thống!");setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangXuat') . '" }, 1000);</script>');
    }
}

function sendCSM($mail_nhan, $ten_nhan, $chu_de, $noi_dung, $bcc)
{
    global $Database;
    // PHPMailer Modify
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $Database->site("Email"); // GMAIL STMP
    $mail->Password = $Database->site("PassEmail"); // PASS STMP 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom($Database->site('Email'), $bcc);
    $mail->addAddress($mail_nhan, $ten_nhan);
    $mail->addReplyTo($Database->site('Email'), $bcc);
    $mail->isHTML(true);
    $mail->Subject = $chu_de;
    $mail->Body = $noi_dung;
    $mail->CharSet = 'UTF-8';
    $send = $mail->send();
    return $send;
}
function BASE_URL($url)
{
    global $config;
    $a = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
    if ($a == 'http://localhost') {
        $a = BASE_URL;
    }
    if ($url == "/") {
        return $a;
    }
    return $a . '/' . $url;
}
function getTime()
{
    return date('Y-m-d H:i:s', time());
}
function displayStatusActiveEmailAccount($data)
{
    if ($data == 0) {
        $show = '<span class="badge badge-danger">Chưa kích hoạt</span>';
    } else if ($data == 1) {
        $show = '<span class="badge badge-success">Đã kích hoạt</span>';
    }
    return $show;
}
function checkUrlExists($url)
{
    // Use get_headers() function
    $headers = @get_headers($url);
    // Use condition to check the existence of URL
    if ($headers && strpos($headers[0], '200')) {
        $status = true;
    } else {
        $status = false;
    }

    return $status;
}
function displayStatusAccount($data)
{
    if ($data == 0) {
        $show = '<span class="badge badge-danger">Banned</span>';
    } else if ($data == 1) {
        $show = '<span class="badge badge-success">Hoạt động</span>';
    }
    return $show;
}

function removeSpecialCharacter($string)
{
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}
function checkContainSpecialCharacter($string)
{
    $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
    if (preg_match($pattern, $string)) {
        return true;
    }
    return false;
}
function check_string($data)
{
    return trim(htmlspecialchars(addslashes($data)));
    //return str_replace(array('<',"'",'>','?','/',"\\",'--','eval(','<php'),array('','','','','','','','',''),htmlspecialchars(addslashes(strip_tags($data))));
}
function encryptString($string)
{
    $ciphering = "AES-128-CTR";
    $options = 0;
    $encryption_iv = '3439727867766855';
    $encryption_key = "4236073900";
    $result = openssl_encrypt(
        $string,
        $ciphering,
        $encryption_key,
        $options,
        $encryption_iv
    );
    return $result;
}
function decryptString($encryption)
{
    $ciphering = "AES-128-CTR";
    $options = 0;
    $decryption_iv  = '3439727867766855';
    $decryption_key  = "4236073900";
    $result = openssl_decrypt(
        $encryption,
        $ciphering,
        $decryption_key,
        $options,
        $decryption_iv
    );
    return $result;
}
function curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);

    curl_close($ch);
    return $data;
}
function chatGPT($content)
{

    $apiKey = OPENAI_API_KEY;
    $url = 'https://api.openai.com/v1/chat/completions';

    $headers = array(
        "Authorization: Bearer {$apiKey}",
        "OpenAI-Organization: org-uwibhod0YLiCEnlQLEakzgpT",
        "Content-Type: application/json"
    );

    // Define messages
    $messages = array();
    $messages[] = array("role" => "user", "content" => $content);

    // Define data
    $data = array();
    $data["model"] = "gpt-3.5-turbo";
    $data["messages"] = $messages;
    $data["max_tokens"] = 50;

    // init curl
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result);
}
function randomString($string, $int)
{
    return substr(str_shuffle($string), 0, $int);
}
function checkImg($img)
{
    $filename = $img['name'];
    $ext = explode(".", $filename);
    $ext = end($ext);
    $valid_ext = array("png", "jpeg", "jpg", "PNG", "JPEG", "JPG");
    if (in_array($ext, $valid_ext)) {
        return true;
    }
    return false;
}
function msg_html_success($text)
{
    return die($text);
}

function msg_error3($text)
{
    return '<div class="alert alert-danger alert-dismissible error-messages">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div>';
}
function msg_success3($text)
{
    return '<div class="alert alert-success alert-dismissible error-messages">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div>';
}


function msg_success2($text)
{
    return die('<script type="text/javascript">toastr.success("' . $text . '", "Thành công!");</script>');
}
function getMessageSuccess2($text)
{
    return ('<script type="text/javascript">toastr.success("' . $text . '", "Thành công!");</script>');
}
function getMessageError2($text)
{
    return ('<script type="text/javascript">toastr.error("' . $text . '", "Lỗi hệ thống!");</script>');
}
function msg_error2($text)
{
    return die('<script type="text/javascript">toastr.error("' . $text . '", "Lỗi hệ thống!");</script>');
}
function msg_warning2($text)
{
    return die('<div class="alert alert-warning alert-dismissible error-messages">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div>');
}
function msg_success($text, $url, $time)
{
    return die('<script type="text/javascript">toastr.success("' . $text . '", "Thành công!");    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function msg_error($text, $url, $time)
{
    return die('<div class="alert alert-danger alert-dismissible error-messages">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div><script type="text/javascript">setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function msg_warning($text, $url, $time)
{
    return die('<div class="alert alert-warning alert-dismissible error-messages">
    <a href="#" class="close" data-dismiss="alert" aria-badge="close">×</a>' . $text . '</div><script type="text/javascript">setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function admin_msg_success($text, $url, $time)
{
    return die('<script type="text/javascript">Swal.fire("Thành Công", "' . $text . '","success");
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function admin_msg_error($text, $url, $time)
{
    return die('<script type="text/javascript">Swal.fire("Thất Bại", "' . $text . '","error");
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function admin_msg_warning($text, $url, $time)
{
    return die('<script type="text/javascript">Swal.fire("Thông Báo", "' . $text . '","warning");
    setTimeout(function(){ location.href = "' . $url . '" },' . $time . ');</script>');
}
function xoaDauCach($text)
{
    return trim(preg_replace('/\s+/', '', $text));
}
function getHeader()
{
    $headers = array();
    $copy_server = array(
        'CONTENT_TYPE' => 'Content-Type',
        'CONTENT_LENGTH' => 'Content-Length',
        'CONTENT_MD5' => 'Content-Md5',
    );
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) === 'HTTP_') {
            $key = substr($key, 5);
            if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                $headers[$key] = $value;
            }
        } elseif (isset($copy_server[$key])) {
            $headers[$copy_server[$key]] = $value;
        }
    }
    if (!isset($headers['Authorization'])) {
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
            $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
            $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
        } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
            $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
        }
    }
    return $headers;
}

function checkUsername($data)
{
    if (preg_match('/^[a-zA-Z0-9_-]{3,16}$/', $data, $matches)) {
        return True;
    } else {
        return False;
    }
}
function checkEmail($data)
{
    if (preg_match('/^.+@.+$/', $data, $matches)) {
        return True;
    } else {
        return False;
    }
}
function checkPhone($data)
{
    if (preg_match('/^\+?(\d.*){3,}$/', $data, $matches)) {
        return True;
    } else {
        return False;
    }
}
function checkUrl($url)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_HEADER, 1);
    curl_setopt($c, CURLOPT_NOBODY, 1);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_FRESH_CONNECT, 1);
    if (!curl_exec($c)) {
        return false;
    } else {
        return true;
    }
}
function checkZip($img)
{
    $filename = $_FILES[$img]['name'];
    $ext = explode(".", $filename);
    $ext = end($ext);
    $valid_ext = array("zip", "ZIP");
    if (in_array($ext, $valid_ext)) {
        return true;
    }
}
function encryptPassword($string)
{
    return md5($string);
}
function phantrang($url, $start, $total, $kmess)
{
    $out[] = '<nav aria-badge="Page navigation example"><ul class="pagination pagination-lg">';
    $neighbors = 2;
    if ($start >= $total)
        $start = max(0, $total - (($total % $kmess) == 0 ? $kmess : ($total % $kmess)));
    else
        $start = max(0, (int) $start - ((int) $start % (int) $kmess));
    $base_link = '<li class="page-item"><a class="page-link" href="' . strtr($url, array('%' => '%%')) . 'page=%d' . '">%s</a></li>';
    $out[] = $start == 0 ? '' : sprintf($base_link, $start / $kmess, '<i class="fas fa-angle-left"></i>');
    if ($start > $kmess * $neighbors)
        $out[] = sprintf($base_link, 1, '1');
    if ($start > $kmess * ($neighbors + 1))
        $out[] = '<li class="page-item"><a class="page-link">...</a></li>';
    for ($nCont = $neighbors; $nCont >= 1; $nCont--)
        if ($start >= $kmess * $nCont) {
            $tmpStart = $start - $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
    $out[] = '<li class="page-item active"><a class="page-link">' . ($start / $kmess + 1) . '</a></li>';
    $tmpMaxPages = (int) (($total - 1) / $kmess) * $kmess;
    for ($nCont = 1; $nCont <= $neighbors; $nCont++)
        if ($start + $kmess * $nCont <= $tmpMaxPages) {
            $tmpStart = $start + $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
    if ($start + $kmess * ($neighbors + 1) < $tmpMaxPages)
        $out[] = '<li class="page-item"><a class="page-link">...</a></li>';
    if ($start + $kmess * $neighbors < $tmpMaxPages)
        $out[] = sprintf($base_link, $tmpMaxPages / $kmess + 1, $tmpMaxPages / $kmess + 1);
    if ($start + $kmess < $total) {
        $display_page = ($start + $kmess) > $total ? $total : ($start / $kmess + 2);
        $out[] = sprintf($base_link, $display_page, '<i class="fas fa-angle-right"></i>');
    }
    $out[] = '</ul></nav>';
    return implode('', $out);
}
function myIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return check_string($ip_address);
}
function timeAgo($time_ago)
{
    //$time_ago = date("Y-m-d H:i:s", $time_ago);
    $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "$seconds giây trước";
    }
    //Minutes
    else if ($minutes <= 60) {
        return "$minutes phút trước";
    }
    //Hours
    else if ($hours <= 24) {
        return "$hours tiếng trước";
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "Hôm qua";
        } else {
            return "$days ngày trước";
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        return "$weeks tuần trước";
    }
    //Months
    else if ($months <= 12) {
        return "$months tháng trước";
    }
    //Years
    else {
        return "$years năm trước";
    }
}

function CheckAdmin()
{
    global $Database;
    if (!isset($_SESSION["account"])) {
        return die('<script type="text/javascript">setTimeout(function(){ location.href = "' . BASE_URL('/') . '" }, 0);</script>');
    }
    $taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");

    if ($taikhoan["MaQuyenHan"] != 2) {
        return die('<script type="text/javascript">setTimeout(function(){ location.href = "' . BASE_URL('/') . '" }, 0);</script>');
    }
}


global $Database;
if ($Database->site("BaoTri") == "ON") {
    if (isset($_SESSION["account"])) {
        $taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
        if ($taikhoan["MaQuyenHan"] == 1) {
            die($Database->site("NoiDungBaoTri"));
        }
    }
}
