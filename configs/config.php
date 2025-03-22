<?php
session_start();

date_default_timezone_set('Asia/Ho_Chi_Minh');

define('HOST_DB', "localhost");
define('USER_DB', "root");
define('PASS_DB', "");
define('DBNAME_DB', "hocngoaingu");


$danhsachlevel = [
    [

        "level" => 1,
        "kinhnghiem" => 100

    ],
    [

        "level" => 2,
        "kinhnghiem" => 200

    ],
    [

        "level" => 3,
        "kinhnghiem" => 400

    ],
    [

        "level" => 4,
        "kinhnghiem" => 1000

    ],
    [

        "level" => 5,
        "kinhnghiem" => 2000

    ],
    [

        "level" => 6,
        "kinhnghiem" => 3000

    ],
    [

        "level" => 7,
        "kinhnghiem" => 4000

    ],
    [

        "level" => 8,
        "kinhnghiem" => 6000

    ],
    [

        "level" => 9,
        "kinhnghiem" => 8000

    ],
    [

        "level" => 10,
        "kinhnghiem" => 10000

    ],
    [

        "level" => 11,
        "kinhnghiem" => 20000

    ],
    [

        "level" => 12,
        "kinhnghiem" => 30000

    ],
    [

        "level" => 13,
        "kinhnghiem" => 50000

    ],
    [

        "level" => 14,
        "kinhnghiem" => 100000

    ],
    [

        "level" => 15,
        "kinhnghiem" => 200000

    ],
    [

        "level" => 16,
        "kinhnghiem" => 500000

    ],
    [

        "level" => 17,
        "kinhnghiem" => 1000000

    ],
    [

        "level" => 18,
        "kinhnghiem" => 2000000

    ],
    [

        "level" => 19,
        "kinhnghiem" => 3000000

    ],
    [

        "level" => 20,
        "kinhnghiem" => 1000000000

    ],
];
//error_reporting(0);
require_once(__DIR__ . "/../class/Database.php");
$Database = new Database();

define('BASE_URL', $Database->site('BASE_URL'));

define('GOOGLE_APP_ID', $Database->site('GOOGLE_APP_ID'));
define('GOOGLE_APP_SECRET', $Database->site('GOOGLE_APP_SECRET'));
define('GOOGLE_APP_CALLBACK_URL', $Database->site('GOOGLE_APP_CALLBACK_URL'));

define('FACEBOOK_APP_ID', $Database->site('FACEBOOK_APP_ID'));
define('FACEBOOK_APP_SECRET', $Database->site('FACEBOOK_APP_SECRET'));
define('FACEBOOK_APP_CALLBACK_URL', $Database->site('FACEBOOK_APP_CALLBACK_URL'));

define('OPENAI_API_KEY', $Database->site('OPENAI_API_KEY'));


require_once(__DIR__ . "/../class/Study.php");
$Study = new Study();

require_once(__DIR__ . "/../class/Course.php");
$Course = new Course();

require_once(__DIR__ . "/../class/HoatDong.php");
$HoatDong = new HoatDong();

require_once(__DIR__ . "/../class/User.php");
$User = new User();

require_once(__DIR__ . "/../class/Pagination.php");

require_once(__DIR__ . "/../vendor/autoload.php");
