<?php

require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Quản lý khóa học';
require_once(__DIR__ . "/Header.php");
require_once(__DIR__ . "/Sidebar.php");

if (isset($_GET["delete_danh_gia"])) {
    $getTaiKhoan = $_GET["taiKhoan"];
    $getKhoaHoc = $_GET["khoaHoc"];
    $Database->query("delete from danhgiakhoahoc where TaiKhoan = '" . $getTaiKhoan . "' and MaKhoaHoc = '" . $getKhoaHoc . "' ");
    admin_msg_success("Xóa thành công", "courses", 1000);
}
if (isset($_POST['btnThemKhoaHoc'])) {

    if (empty($_POST['tenKhoaHoc']) || empty($_POST['linkAnhKhoaHoc']) || empty($_POST['moTaKhoaHoc'])) {
        admin_msg_error("Vui lòng nhập đầy đủ thông tin", "", 500);
    }
    $tenKhoaHoc = ($_POST['tenKhoaHoc']);
    $linkAnh = ($_POST['linkAnhKhoaHoc']);
    $moTa = ($_POST['moTaKhoaHoc']);
    $Database->insert("khoahoc", array(
        'TenKhoaHoc' => $tenKhoaHoc,
        'LinkAnh' => $linkAnh,
        'NguoiTao' => $_SESSION["account"],
        'NoiDung' => $moTa,

    ));
    admin_msg_success("Thêm thành công", "", 1000);
}

?>



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quản lý khóa học</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">DANH SÁCH KHÓA HỌC</h3>
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
                                        <th>Mã khóa học</th>
                                        <th>Tên khóa học</th>
                                        <th>Ảnh khóa học</th>
                                        <th>Số học viên</th>
                                        <th>Người tạo</th>
                                        <th>Ngày tạo</th>
                                        <th>Trạng thái</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ($Database->get_list(" SELECT * FROM khoahoc ORDER BY MaKhoaHoc ASC ") as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $row['MaKhoaHoc']; ?></td>
                                            <td><?= $row['TenKhoaHoc']; ?></td>
                                            <td><img style="border: 1px solid;
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;" src="<?= $row['LinkAnh']; ?>" /></td>

                                            <td><?= $Database->num_rows("SELECT * FROM dangkykhoahoc WHERE MaKhoaHoc = '" . $row["MaKhoaHoc"] . "'  "); ?></td>
                                            <td><a href="<?= BASE_URL('admin/users/edit/'); ?><?= $row['NguoiTao']; ?>"><?= $row['NguoiTao']; ?></a></td>
                                            <td><span class="badge badge-dark"><?= $row['ThoiGianTaoKhoaHoc']; ?></span></td>
                                            <td><?= displayStatusAccount($row['TrangThaiKhoaHoc']); ?></td>

                                            <td>
                                                <a type="button" href="<?= BASE_URL('admin/courses/edit/'); ?><?= $row['MaKhoaHoc']; ?>" class="btn btn-primary"><i class="fas fa-edit"></i>
                                                    <span>EDIT</span></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
                   <div class="col-md-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">THÊM KHÓA HỌC</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                       
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Tên khóa học</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="tenKhoaHoc" value="">
                                    </div>
                                </div>
                            </div>
                                   <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Link ảnh khóa học</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="linkAnhKhoaHoc" value="">
                                    </div>
                                </div>
                            </div>
                                   <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Mô tả khóa học</label>
                                <div class="col-sm-8">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="inputEmail3" name="moTaKhoaHoc" value="">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="btnThemKhoaHoc" class="btn btn-primary btn-block waves-effect">
                                <span>XÁC NHẬN</span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách đánh giá các khóa học</h3>
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
                                        <th>Tài khoản</th>
                                        <th>Khóa học</th>
                                        <th>Nội dung</th>
                                        <th>Rating</th>
                                        <th>Thời gian</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ($Database->get_list(" SELECT * FROM danhgiakhoahoc A inner join khoahoc B on A.MaKhoaHoc = B.MaKhoaHoc  ORDER BY A.ThoiGian DESC ") as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><a href="<?= BASE_URL('admin/users/edit/'); ?><?= $row['TaiKhoan']; ?>"><?= $row['TaiKhoan']; ?></a></td>
                                            <td><?= $row['TenKhoaHoc']; ?></td>
                                            <td><?= $row['NoiDungDanhGia']; ?></td>
                                            <td><?= $row['Rating']; ?></td>
                                            <td><span class="badge badge-dark px-3"><?= $row['ThoiGian']; ?></span></td>
                                            <td>
                                                <a type="button" href="?delete_danh_gia&taiKhoan=<?= $row['TaiKhoan']; ?>&khoaHoc=<?= $row['MaKhoaHoc']; ?>" class="btn btn-primary"><i class="fas fa-trash"></i>
                                                    <span>Delete</span></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
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
require_once(__DIR__ . "/Footer.php"); ?>