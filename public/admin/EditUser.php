<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Quản lý thành viên';
require_once(__DIR__ . "/Header.php");
require_once(__DIR__ . "/Sidebar.php");
?>
<?php
if (isset($_GET['account'])) {
    $row = $Database->get_row(" SELECT * FROM `nguoidung` WHERE `TaiKhoan` = '" . check_string($_GET['account']) . "'  ");
    if (!$row) {
        admin_msg_error("Người dùng này không tồn tại", BASE_URL(''), 500);
    }
} else {
    admin_msg_error("Liên kết không tồn tại", BASE_URL(''), 0);
}
if (isset($_POST['btnSaveUser']) && isset($_GET['account'])) {
    if (!isset($_POST['tenHienThi']) || !isset($_POST['anhDaiDien']) || !isset($_POST['trangThai'])  || !isset($_POST['quyenHan'])) {
        admin_msg_error("Vui lòng nhập đầy đủ thông tin", BASE_URL(''), 500);
    }
    $tenHienThi = check_string($_POST['tenHienThi']);
    $anhDaiDien = check_string($_POST['anhDaiDien']);
    $trangThai = check_string($_POST['trangThai']);
    $quyenHan = check_string($_POST['quyenHan']);
    $Database->update("nguoidung", array(
        'TenHienThi' => $tenHienThi,
        'AnhDaiDien'           => $anhDaiDien,
        'TrangThai'  => $trangThai,
        'MaQuyenHan'         => $quyenHan

    ), " `TaiKhoan` = '" . $row['TaiKhoan'] . "' ");
    admin_msg_success("Thay đổi user thành công", "", 1000);
}
?>



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chỉnh sửa thành viên</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">CHỈNH SỬA THÀNH VIÊN</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tài khoản</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" value="<?= $row['TaiKhoan']; ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" value="<?= $row['Email']; ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tên hiển thị</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="tenHienThi" value="<?= $row['TenHienThi']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Ảnh đại diện</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="anhDaiDien" value="<?= $row['AnhDaiDien']; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">Trạng thái</label>
                                <div class="col-sm-10">
                                    <select class="custom-select" name="trangThai">
                                        <option value="<?= $row['TrangThai']; ?>">
                                            <?php
                                            if ($row['TrangThai'] == "1") {
                                                echo 'Hoạt động';
                                            }
                                            if ($row['TrangThai'] == "0") {
                                                echo 'Banned';
                                            }
                                            ?>
                                        </option>
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Banned</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">Quyền hạn</label>
                                <div class="col-sm-10">
                                    <select class="custom-select" name="quyenHan">
                                        <option value="<?= $row['MaQuyenHan']; ?>">
                                            <?php
                                            $getQuyenHan = $Database->get_row("select * from quyenhan where MaQuyenHan = '" . $row['MaQuyenHan'] . "' ");
                                            echo $getQuyenHan["TenQuyenHan"];
                                            ?>
                                        </option>
                                        <?php
                                        foreach ($Database->get_list(" SELECT * FROM quyenhan ") as $quyenHan) {
                                        ?>
                                            <option value="<?= $quyenHan["MaQuyenHan"] ?>"><?= $quyenHan["TenQuyenHan"] ?></option>
                                        <?php
                                        }
                                        ?>


                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Ngày đăng ký</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" value="<?= $row['NgayDangKy']; ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="btnSaveUser" class="btn btn-primary btn-block waves-effect">
                                <span>LƯU</span>
                            </button>
                            <a type="button" href="<?= BASE_URL('admin/users'); ?>" class="btn btn-danger btn-block waves-effect">
                                <span>TRỞ LẠI</span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Hoạt động tài khoản</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã hoạt động</th>
                                        <th>Tên hoạt động</th>
                                        <th>Loại hoạt động</th>
                                        <th>Nội dung</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ($Database->get_list(" SELECT * FROM hoatdong A inner join loaihoatdong B on A.MaLoaiHoatDong = B.MaLoaiHoatDong WHERE A.TaiKhoan = '" . $row['TaiKhoan'] . "' ORDER BY A.ThoiGian DESC ") as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $row['MaHoatDong']; ?></td>
                                            <td><?= $row['TenHoatDong']; ?></td>
                                            <td><?= $row['TenLoaiHoatDong']; ?></td>
                                            <td><?= $row['NoiDung']; ?></td>
                                            <td><span class="badge badge-dark px-3"><?= $row['ThoiGian']; ?></span></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<script>
    $(function() {
        $("#datatable").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>






<?php
require_once(__DIR__ . "/Footer.php"); ?>?>