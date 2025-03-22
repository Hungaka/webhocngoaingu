<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="<?= BASE_URL("/") ?>/assets/css/base.css?id=<?= rand(0, 1000000) ?>">
    <link rel="stylesheet" href="<?= BASE_URL("/") ?>/assets/css/grid.css?id=<?= rand(0, 1000000) ?>">
    <link rel="stylesheet" href="<?= BASE_URL("/") ?>/assets/css/responsive.css?id=<?= rand(0, 1000000) ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <title>
        <?= $title; ?>
    </title>
</head>

<body>
    <div class="main">
        <div class="loading" id="loading_modal">
            <div class="loading__wrap-img">
                <img src="<?= BASE_URL("/") ?>/assets/img/Loading.gif" alt="" class="loading__img">
            </div>
        </div>
        <?php
        if (!isset($_SESSION["account"])) {
        ?>
            <div class="header">
                <div class="grid wide">
                    <div class="header_wrap">
                        <a href="<?= BASE_URL("/") ?>">
                            <h2 class="header__name"><?= $Database->site("TenWeb") ?></h2>
                        </a>
                        <div class="nav">
                            <a href="#" class="nav__course">Các khóa học</a>
                            <a href="./Auth/DangNhap" class="nav__statr btn">Bắt đầu học</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } else {
            checkAccountExist();
            updateCapDo();
        ?>



        <?php
        }
        ?>