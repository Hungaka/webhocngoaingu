<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'DASHBOARD ADMIN';
require_once(__DIR__ . "/Header.php");
require_once(__DIR__ . "/Sidebar.php");

?>



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>

            </div>
        </div>
    </section>
    <section class="content">

        <div id="thongbao"></div>
        <div class="row">
            <div class="col-lg-3 col-12">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="total_users"><?= $Database->num_rows("SELECT * FROM `nguoidung` "); ?></h3>
                        <p>Tổng thành viên </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $Database->num_rows("SELECT * FROM `khoahoc` "); ?></h3>
                        <p>Tổng khóa học </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-language"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $Database->num_rows("SELECT * FROM `baihoc` "); ?></h3>
                        <p>Tổng bài học </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-language"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $Database->num_rows("SELECT * FROM `tuvung` "); ?></h3>
                        <p>Tổng từ vựng </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-language"></i>
                    </div>
                </div>
            </div>
        </div>



    </section>
</div>


</section>
<!-- /.content -->
</div>
<script>
    $(function() {
        $("#datatable").DataTable({
            "responsive": false,
            "autoWidth": false,
        });
        $("#datatable1").DataTable({
            "responsive": false,
            "autoWidth": false,
        });
    });
</script>

<?php
require_once(__DIR__ . "/Footer.php");
?>