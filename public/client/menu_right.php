<?php
$taikhoan = $Database->get_row("SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . $_SESSION["account"] . "' ");
if (!$taikhoan) {
    return die('<script type="text/javascript">
    setTimeout(function(){ location.href = "' . BASE_URL('Auth/DangNhap') . '" }, 0);
    </script>
    ');
}
$tientrinh_level =  $taikhoan["CapDo"] >= 20 ? 100 : floor($taikhoan["KinhNghiem"] / (checkRequireCapDo($taikhoan["CapDo"] + 1)) * 100);
$kinh_nghiem_remain = $taikhoan["CapDo"] >= 20 ? 0 :  checkRequireCapDo($taikhoan["CapDo"] + 1) - $taikhoan["KinhNghiem"];

$muctieuhoctap = $Database->get_row("SELECT * FROM `muctieuhoctap` WHERE `MaMucTieu` = '" . $taikhoan["MaMucTieu"] . "' ");
$currentDate = date("Y-m-d");
$soluongtudahoc = $Database->get_row(" SELECT count(*) as SoLuongTuDaHoc FROM hoctuvung WHERE `TaiKhoan`  = '" . $_SESSION["account"] . "' AND  DATE(THOIGIAN) = '" . $currentDate . "'  ");
$tientrinh_muctieuhoctap = floor($soluongtudahoc["SoLuongTuDaHoc"] / $muctieuhoctap["SoLuongTuMoi"] * 100);

?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/menu_right.css");
    ?>
</style>

<div class="menu_right-container">
    <div class="statistical">
        <div class="individual card">
            <div class="individual__heading">Cá nhân</div>
            <div class="individual__avatar">
                <img src=<?= $taikhoan["AnhDaiDien"] ?> alt=<?= $taikhoan["TenHienThi"] ?> class="individual__avatar-img">
                <div class="individual__avatar-notifi"><?= $taikhoan["CapDo"] ?></div>
            </div>
            <div class="individual__name"><?= $taikhoan["TenHienThi"] ?></div>
            <div class="individual__level">
                <img src="<?= BASE_URL("") ?>/assets/img/star.svg" alt="" class="individual__level-img">
                <div class="individual__level-number"><?= formatNumber($taikhoan["KinhNghiem"]) ?>/<?= formatNumber(checkRequireCapDo($taikhoan["CapDo"] + 1)) ?> </div>
            </div>
            <div class="individual__bar">
                <div class="individual__bar-value" style="width: <?= $tientrinh_level <= 100 ? $tientrinh_level : 100 ?>%"><span class="individual__bar-percent"><?= $tientrinh_level ?>%</span></div>
            </div>
            <div class="individual__level-targer">
                <?php
                if ($taikhoan["CapDo"] >= 20) {
                ?>
                    Bạn đã đạt cấp độ tối đa

                <?php
                } else {

                ?>
                    Cần <?= formatNumber($kinh_nghiem_remain) ?> sao nữa để đạt cấp độ <?= $taikhoan["CapDo"] + 1 ?>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="daily-goals card">
            <div class="daily-goals__heading">Mục tiêu ngày</div>
            <div class="daily-goals__targer">
                <img src="<?= BASE_URL("") ?>/assets/img/Targer.png" alt="" class="daily-goals__targer-img">
                <div class="daily-goals__targer-percent">
                    <div class="daily-goals__targer-new-word"><?= $muctieuhoctap["NoiDungMucTieu"] ?></div>
                    <div class="daily-goals__targer-wrap">
                        <div class="daily-goals__targer-bar">
                            <div class="daily-goals__targer-bar-value" style="width: <?= $tientrinh_muctieuhoctap <= 100 ? $tientrinh_muctieuhoctap : 100 ?>%"><span class="daily-goals__targer-bar-percent"><?= $tientrinh_muctieuhoctap <= 100 ? $tientrinh_muctieuhoctap : 100 ?>%</span></div>
                        </div>
                        <div class="daily-goals__targer-number"><?= $soluongtudahoc["SoLuongTuDaHoc"] ?>/<?= $muctieuhoctap["SoLuongTuMoi"] ?></div>
                    </div>
                </div>
            </div>
            <div class="daily-goals__detail-btn btn btn--primary"><a href="<?= BASE_URL("Page/TrangCaNhan") ?>/<?= $taikhoan["TaiKhoan"] ?>/">
                    Chi tiết</a>
            </div>
        </div>

        <div class="ratings card">
            <div class="ratings__heading">Bảng xếp hạng</div>
            <?php
            foreach ($Database->get_list(" SELECT * FROM nguoidung ORDER BY CapDo DESC, TongKinhNghiem DESC LIMIT 5") as $row) {

            ?>
                <div class="ratings__person">
                    <img src=<?= $row["AnhDaiDien"] ?> alt=<?= $row["TenHienThi"] ?> class="ratings__person-img">
                    <div class="ratings__person-name"><a href="<?= BASE_URL("Page/TrangCaNhan") ?>/<?= $row["TaiKhoan"] ?>/"><?= $row["TenHienThi"] ?></a></div>
                    <div class="ratings__person-point"><?= $row["CapDo"] ?></div>
                </div>
            <?php
            }
            ?>
            <div class="ratings__btn btn btn--primary"><a href="<?= BASE_URL("Page/BangXepHang") ?>">Xem thêm</a></div>
        </div>
    </div>
</div>