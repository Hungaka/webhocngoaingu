<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");

// Login with Facebook
$fb = new Facebook\Facebook([
    'app_id' => FACEBOOK_APP_ID,
    'app_secret' => FACEBOOK_APP_SECRET,
    'default_graph_version' => 'v2.5',
]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // optional
try {
    $accessToken = $helper->getAccessToken();
    $fb->setDefaultAccessToken($accessToken);
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in


// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId(FACEBOOK_APP_ID); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (!$accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        $fb->setDefaultAccessToken($accessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
        exit;
    }
}

$profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
$requestPicture = $fb->get('/me/picture?redirect=false&height=200'); //getting user picture
$picture = $requestPicture->getGraphUser();
$profile = $profile_request->getGraphUser();
$fbid = $profile->getProperty('id');           // To Get Facebook ID
$fbfullname = $profile->getProperty('name');   // To Get Facebook full name
$fbemail = $profile->getProperty('email');    //  To Get Facebook email

try {
    // Check 
    $checkUser = $Database->get_row("select * from nguoidung where FacebookID = '$fbid'");
    if (!$checkUser) {
        // Create new user
        $randomNumber = randomString('0123456789', '6');
        $removeAllSpecialCharacterAccount = strtolower(xoaDauCach(removeSpecialCharacter(stripUnicode($fbfullname))));
        $generateAccount = $removeAllSpecialCharacterAccount . $randomNumber;
        $checkExistAccount =  $Database->get_row(" SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '$generateAccount'");

        while ($checkExistAccount) {
            $randomNumber = randomString('0123456789', '6');
            $generateAccount =  $removeAllSpecialCharacterAccount . $randomNumber;
            $checkExistAccount =  $Database->get_row(" SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '$generateAccount'");
        }
        $randomPassword = randomString('0123456789zxcvbnmasdfghjklqwerttyuiopQWERTYUIOPASDGHJKLZXCVBNM', '10');

        $create = $Database->insert("nguoidung", [
            'TaiKhoan' => $generateAccount,
            'TenHienThi' => $fbfullname,
            'AnhDaiDien' => $picture["url"],
            'MatKhau' => encryptPassword($randomPassword),
            'IPAddress' => myIP(),
            'MaQuyenHan' => 1,
            'MaMucTieu' => 1,
            'FacebookID' => $fbid

        ]);
        $Database->insert("thongbaoemail", [
            'TaiKhoan' => $generateAccount,
        ]);
        $_SESSION["account"] =  $generateAccount;

        header("Location: " . BASE_URL("Page/Home"));
    } else {
        $_SESSION["account"] =  $checkUser["TaiKhoan"];
        header("Location: " . BASE_URL("Page/Home"));
    }
} catch (Exception $err) {
    header("Location: " . BASE_URL("Auth/DangNhap"));
    exit;
}
