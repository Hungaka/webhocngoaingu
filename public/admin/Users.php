<?php

require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Quản lý thành viên';
require_once(__DIR__ . "/Header.php");
require_once(__DIR__ . "/Sidebar.php");

?>



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quản lý thành viên</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">DANH SÁCH THÀNH VIÊN</h3>
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
                                        <th>Tên hiển thị</th>
                                        <th>Email</th>
                                        <th>Ảnh đại diện</th>
                                        <th>Cấp độ</th>
                                        <th>Kích hoạt email</th>
                                        <th>Loại đăng nhập</th>
                                        <th>Quyền hạn</th>

                                        <th>Ngày đăng ký</th>
                                        <th>Trạng thái</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ($Database->get_list(" SELECT * FROM nguoidung A inner join quyenhan B on A.MaQuyenHan = B.MaQuyenHan ORDER BY A.NgayDangKy DESC ") as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $row['TaiKhoan']; ?></td>
                                            <td><?= $row['TenHienThi']; ?></td>
                                            <td><?= $row['Email']; ?></td>
                                            <td><img style="border: 1px solid;
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;" src="<?= $row['AnhDaiDien']; ?>" /></td>
                                            <td><?= $row['CapDo']; ?></td>
                                            <td><?= displayStatusActiveEmailAccount($row['KichHoatEmail']); ?></td>
                                            <td><?php if (!empty($row['FacebookID'])) {
                                                ?>
                                                    <span class="badge badge-success">Facebook</span>
                                                <?php

                                                } else {
                                                ?>
                                                    <span class="badge badge-dark">Thường</span>

                                                <?php

                                                }   ?>
                                            </td>
                                            <td><?= $row['TenQuyenHan']; ?></td>
                                            <td><span class="badge badge-dark"><?= $row['NgayDangKy']; ?></span></td>
                                            <td><?= displayStatusAccount($row['TrangThai']); ?></td>

                                            <td>
                                                <a type="button" href="<?= BASE_URL('admin/users/edit/'); ?><?= $row['TaiKhoan']; ?>" class="btn btn-primary"><i class="fas fa-edit"></i>
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