<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

    <title>
        <?= $title; ?>
    </title>
    <meta name="robots" content="index,follow" />
    <meta name="description" content="<?= $Database->site('MoTa'); ?>">
    <meta name="keywords" content="<?= $Database->site('TuKhoa'); ?>">
    <!-- Open Graph data -->
    <meta property="og:locale" content="vi_VN" />
    <meta property="og:title" content="<?= isset($META_TITLE) ? $META_TITLE : $Database->site('TenWeb'); ?>">
    <meta property="og:type" content="Website">
    <meta property="og:url" content="<?= isset($META_SITE) ? $META_SITE :  BASE_URL(''); ?>">
    <meta property="og:image" content="<?= isset($META_IMAGE) ? $META_IMAGE :  $Database->site('Thumbnail'); ?>">
    <meta property="og:image:width" content="800" />
    <meta property="og:image:height" content="800" />
    <meta property="og:description" content="<?= isset($META_DESCRIPTION) ? $META_DESCRIPTION :  $Database->site('MoTa'); ?>">
    <meta property="og:site_name" content="<?= $Database->site('TenWeb'); ?>">
    <meta property="article:section" content="<?= $Database->site('MoTa'); ?>">
    <meta property="article:tag" content="<?= $Database->site('TuKhoa'); ?>">
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="<?= isset($META_IMAGE) ? $META_IMAGE : $Database->site('Thumbnail'); ?>">
    <meta name="twitter:site" content="<?= isset($META_SITE) ? $META_SITE :  BASE_URL(''); ?>">
    <meta name="twitter:title" content="<?= isset($META_TITLE) ? $META_TITLE :  $Database->site('TenWeb'); ?>">
    <meta name="twitter:description" content="<?= isset($META_DESCRIPTION) ? $META_DESCRIPTION :  $Database->site('MoTa'); ?>">
    <meta name="twitter:creator" content="<?= $Database->site('Author'); ?>">
    <meta name="twitter:image:src" content="<?= isset($META_IMAGE) ? $META_IMAGE :   $Database->site('Thumbnail'); ?>">

    <link rel="icon" href="<?= $Database->site('LinkIcon'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <!-- Bulma io -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <!-- Bulma io -->
    <link rel="stylesheet" href="<?= BASE_URL("/") ?>/assets/css/base.css?id=<?= rand(0, 1000000) ?>">
    <link rel="stylesheet" href="<?= BASE_URL("/") ?>/assets/css/grid.css?id=<?= rand(0, 1000000) ?>">
    <link rel="stylesheet" href="<?= BASE_URL("/") ?>/assets/css/custom.css?id=<?= rand(0, 1000000) ?>">
    <link rel="stylesheet" href="<?= BASE_URL("/") ?>/assets/css/modal.css?id=<?= rand(0, 1000000) ?>">
    <link rel="stylesheet" href="<?= BASE_URL("/") ?>/assets/css/loading.css?id=<?= rand(0, 1000000) ?>">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Toastr -->

    <!-- Moment -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script> <!-- Moment -->
    <!-- Moment -->

    <!-- Anime js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js" integrity="sha512-z4OUqw38qNLpn1libAN9BsoDx6nbNFio5lA6CuTp9NlK83b89hgyCVq+N5FdBJptINztxn1Z3SaKSKUS5UP60Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Anime js -->

    <!-- Chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <!-- Chart js -->

    <!-- Slick -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <!-- Slick -->

    <!-- interact js -->
    <script src="https://cdn.interactjs.io/v1.10.17/interactjs/index.js"></script>
    <!-- interact js -->



</head>

<body>
    <div class="main">
        <div id="thongbao"></div>
        <div class="loading" id="loading_modal">
            <div class="loading__wrap-img">
                <img src="<?= BASE_URL("/") ?>/assets/img/Loading.gif" alt="" class="loading__img">
            </div>
        </div>
        <?php
        if (isset($_SESSION["account"])) {
            checkAccountExist();
            updateCapDo();
        }
        ?>