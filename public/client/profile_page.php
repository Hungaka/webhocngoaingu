<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Trang cá nhân | ' . $Database->site("TenWeb") . '';
$locationPage = 'profile_page';
require_once(__DIR__ . "/../../public/client/header.php");
require_once(__DIR__ . "/../../class/Pagination.php");

checkLogin();
$checkTaiKhoan = $Database->get_row("SELECT * FROM nguoidung WHERE TaiKhoan = '" . check_string($_GET["taiKhoan"]) . "' ");
if ($checkTaiKhoan <= 0) {
    return die('<script type="text/javascript">
    setTimeout(function(){ location.href = "' . BASE_URL('/') . '" }, 0);
    </script>
    ');
}
$tientrinh_level = $checkTaiKhoan["CapDo"] >= 20 ? 100 : floor($checkTaiKhoan["KinhNghiem"] / (checkRequireCapDo($checkTaiKhoan["CapDo"] + 1)) * 100);
$kinh_nghiem_remain = $checkTaiKhoan["CapDo"] >= 20 ? 0 : checkRequireCapDo($checkTaiKhoan["CapDo"] + 1) - $checkTaiKhoan["KinhNghiem"];
$soTuVungDaHoc = $Database->get_row("SELECT COUNT(*) as SoLuong FROM hoctuvung WHERE TaiKhoan = '" . $checkTaiKhoan["TaiKhoan"] . "' ")["SoLuong"];
$mucTieuHocTap = $Database->get_row("SELECT * FROM `muctieuhoctap` WHERE `MaMucTieu` = '" . $checkTaiKhoan["MaMucTieu"] . "' ");
$currentDate = date("Y-m-d");
$soLuongTuDaHoc = $Database->get_row(" SELECT count(*) as SoLuongTuDaHoc FROM hoctuvung WHERE `TaiKhoan`  = '" . $checkTaiKhoan["TaiKhoan"] . "' AND  DATE(THOIGIAN) = '" . $currentDate . "'  ");
if ($soLuongTuDaHoc == 0) {
    $tienTrinhHocTap = 0;
} else {
    $tienTrinhHocTap = floor($soLuongTuDaHoc["SoLuongTuDaHoc"] / $mucTieuHocTap["SoLuongTuMoi"] * 100);
}

if (!empty($_GET["type"])) {
    $type = check_string($_GET["type"]);
} else {
    $type = 'tongquan';
}

$baseURL =  BASE_URL("assets/ajaxs/User.php");
$limit = 5;
$typeAjax = 'GetHoatDongTaiKhoan';
// Count of all records 
$rowCount = $Database->num_rows(" SELECT * FROM hoatdong WHERE TaiKhoan = '" . $checkTaiKhoan["TaiKhoan"] . "'");

// Initialize pagination class 
$pagConfig = array(
    'baseURL' => $baseURL,
    'totalRows' => $rowCount,
    'perPage' => $limit,
    'typeAjax' => $typeAjax,
    'contentDiv' => 'profile__active-container'
);
$pagination =  new Pagination($pagConfig);

?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/profile_page.css");
    ?>
</style>
<div class="grid">
    <div class="row main-page">
        <div class="nav-container">
            <?php
            include_once(__DIR__ . "/../../public/client/navigation.php");
            ?>
        </div>

        <div class="main_content-container">
            <div class="profile">
                <div class="page__title">Hồ sơ của <?= $checkTaiKhoan["TenHienThi"] ?></div>
                <div class="profile__header">
                    <div class="profile__header-avatar">
                        <img src="<?= $checkTaiKhoan["AnhDaiDien"] ?>" alt="" class="profile__header-avatar-img">
                        <div class="profile__header-avatar-level"><?= $checkTaiKhoan["CapDo"] ?></div>
                    </div>
                    <div class="profile__header-info">
                        <div class="profile__header-info-name"><?= $checkTaiKhoan["TenHienThi"] ?></div>
                        <div class="profile__header-info-position"><?= $checkTaiKhoan["TaiKhoan"] ?></div>
                        <div class="profile__header-info-time-join">
                            <img src="<?= BASE_URL('/') ?>/assets/img/profile-time.svg" alt="" class="profile__header-info-time-join-img">
                            <div class="profile__header-info-time-join-text" title="<?= $checkTaiKhoan["NgayDangKy"] ?>">Đã tham gia <?= timeAgo($checkTaiKhoan["NgayDangKy"]) ?></div>
                        </div>
                        <?php
                        if ($checkTaiKhoan["TaiKhoan"] == $_SESSION["account"]) {
                        ?>
                            <a href="<?= BASE_URL("setting.php/") ?>">
                                <div class="btn btn--primary">Sửa hồ sơ</div>
                            </a>
                        <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="profile__content">
                    <div class="tabcontrol-nav">
                        <div class="tablinks tabcontrol-item <?= $type == 'tongquan' ? "tab-active" : "" ?> " onclick="openTab(event, 'tabTongQuan')">Tổng quan</div>
                        <?php
                        if ($checkTaiKhoan["TaiKhoan"] == $_SESSION["account"]) {
                        ?>
                            <div class="tablinks tabcontrol-item <?= $type == 'hoatdong' ? "tab-active" : "" ?>" onclick="openTab(event, 'tabHoatDong')">Hoạt động</div>
                        <?php
                        }
                        ?>
                    </div>
                    <div id="tabTongQuan" class="tabcontent profile__overview" style="display: <?= $type == 'tongquan' ? "block" : "none" ?>;">

                        <div class="profile__statistical">
                            <div class="profile__statistical-title">Thống kê</div>
                            <div class="profile__statistical-content">
                                <div class="profile__statistical-studied card">
                                    <img src="<?= BASE_URL('/') ?>/assets/img/profile-studied.svg" alt="" class="profile__statistical-studied-img">
                                    <div class="profile__statistical-studied-content">
                                        <div class="profile__statistical-studied-number"><?= formatNumber($soTuVungDaHoc) ?></div>
                                        <div class="profile__statistical-studied-title">Từ đã học</div>
                                    </div>
                                </div>
                                <div class="profile__statistical-experience card">
                                    <img src="<?= BASE_URL('/') ?>/assets/img/star.svg" alt="" class="profile__statistical-experience-img">
                                    <div class="profile__statistical-experience-content">
                                        <div class="profile__statistical-experience-number"><?= formatNumber($checkTaiKhoan["KinhNghiem"]) ?> / <?= formatNumber($checkTaiKhoan["TongKinhNghiem"]) ?></div>
                                        <div class="profile__statistical-experience-title">Kinh nghiệm</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile__overview-level">
                            <div class="profile__overview-level-title">Cấp độ</div>
                            <div class="profile__overview-level-bar">
                                <div class="profile__overview-level-bar-value" style="width: <?= $tientrinh_level <= 100 ? $tientrinh_level : 100 ?>%"><span class="profile__overview-level-bar-percent"><?= ($tientrinh_level) ?>%</span></div>
                            </div>
                            <?php
                            if ($checkTaiKhoan["CapDo"] >= 20) {
                            ?>
                                <div class="profile__overview-level-text">Bạn đã đạt cấp độ cao nhất</div>

                            <?php
                            } else {
                            ?>
                                <div class="profile__overview-level-text">Cần <?= formatNumber($kinh_nghiem_remain) ?> sao nữa để đạt cấp độ <?= ($checkTaiKhoan["CapDo"] + 1) ?></div>

                            <?php
                            }
                            ?>
                        </div>
                        <div class="profile__overview-targer" id="target_study">
                            <div class="profile__overview-targer-title">Mục tiêu học tập</div>
                            <?php
                            if ($soLuongTuDaHoc["SoLuongTuDaHoc"] < $mucTieuHocTap["SoLuongTuMoi"]) {
                            ?>

                                <div class="profile__overview-targer-text">Mục tiêu hôm nay chưa đạt yêu cầu</div>
                            <?php
                            }
                            ?>
                            <div class="profile__overview-targer-word">
                                <img src="<?= BASE_URL('/') ?>/assets/img/Targer.png" alt="" class="profile__overview-targer-word-img">
                                <div class="profile__overview-targer-wrap-targer">
                                    <div class="profile__overview-targer-new-word"><?= $mucTieuHocTap["NoiDungMucTieu"] ?></div>
                                    <div class="profile__overview-targer-wrap-bar">
                                        <div class="profile__overview-targer-bar">
                                            <div class="profile__overview-targer-bar-value" style="width: <?= $tienTrinhHocTap <= 100 ? $tienTrinhHocTap : 100  ?>%"><span class="profile__overview-targer-bar-percent"><?= $tienTrinhHocTap ?>%</span></div>
                                        </div>
                                        <div class="profile__overview-targer-number"><?= $soLuongTuDaHoc["SoLuongTuDaHoc"] ?>/<?= $mucTieuHocTap["SoLuongTuMoi"] ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile__overview-level-text">Tiến độ học tập trong 7 ngày gần nhất</div>

                            <canvas style="max-width: 100%" id="myChart" width="400" height="200"></canvas>

                        </div>


                    </div>
                    <?php
                    if ($checkTaiKhoan["TaiKhoan"] == $_SESSION["account"]) {
                    ?>
                        <div id="tabHoatDong" class="tabcontent profile__active" style="display:  <?= $type == 'hoatdong' ? "block" : "none" ?>;">
                            <div class="profile__active-title">Hoạt động hệ thống</div>

                            <div class="profile__active-container" id="profile__active-container">

                                <?php
                                foreach ($Database->get_list(" SELECT * FROM hoatdong INNER JOIN loaihoatdong on hoatdong.TaiKhoan = '" . $checkTaiKhoan["TaiKhoan"] . "' AND hoatdong.MaLoaiHoatDong = loaihoatdong.MaLoaiHoatDong ORDER BY ThoiGian DESC LIMIT 5") as $hoatDong) {

                                ?>
                                    <div class="profile__active-content card">
                                        <div class="profile__active-content-left">
                                            <div class="profile__active-content-title"><?= $hoatDong["TenHoatDong"] ?></div>
                                            <div class="profile__active-content-text"><?= $hoatDong["NoiDung"] ?></div>

                                            <div class="profile__active-content-time" title="<?= $hoatDong["ThoiGian"] ?>"><?= timeAgo($hoatDong["ThoiGian"]) ?></div>
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

                            </div>

                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php
        include_once(__DIR__ . "/../../public/client/menu_right_profile.php");
        include_once(__DIR__ . "/../../public/client/navigation_mobile.php");
        ?>
        <script src="<?= BASE_URL("/") ?>/assets/javascript/tab_control.js?t=<?= rand(0, 99999) ?>"></script>

        <script>
            let TIENDOHOCTAP = [];



            function handleGetTienDoHocTap() {
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/User.php"); ?>",
                    method: "POST",
                    data: {
                        type: 'GetTienDoHocTap',
                        taiKhoan: '<?= $checkTaiKhoan["TaiKhoan"] ?>',
                    },

                    success: function(response) {
                        let json = $.parseJSON(response);

                        if (json && json.message) {
                            if (json.status !== "success") {
                                $("#thongbao").empty().append(json.message);
                            }

                            if (json.data && json.status == "success") {
                                TIENDOHOCTAP = json.data;
                                handleUpdateChartTienDoHocTap();
                            }
                        }

                    }
                });
            }

            function handleUpdateChartTienDoHocTap() {
                console.log(TIENDOHOCTAP)
                const ctx = document.getElementById('myChart');
                const labels = [];
                const today = new Date();
                for (let i = 0; i < 7; i++) {
                    const date = new Date(today.getFullYear(), today.getMonth(), today.getDate() - i);
                    labels.push(`${date.getDate()}/${date.getMonth()+1}`);
                }
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels.reverse(),
                        datasets: [{
                            label: 'Số từ đã học',
                            maxBarLength: 10,
                            barPercentage: 1,
                            data: TIENDOHOCTAP,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }


            $(document).ready(function() {

                handleGetTienDoHocTap();



            });
        </script>

        <?php
        require_once(__DIR__ . "/../../public/client/footer.php"); ?>