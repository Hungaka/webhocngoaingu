<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Chỉnh sửa hệ thống';
require_once(__DIR__ . "/Header.php");
require_once(__DIR__ . "/Sidebar.php");
?>
<?php
function getData($name)
{
    global $Database;
    return $Database->site($name);
}

if (isset($_POST['btnSave'])) {
    if (empty($_POST['tenWeb']) || empty($_POST['email']) || empty($_POST['passEmail'])  || empty($_POST['defaultAvatar']) || empty($_POST['moTa']) || empty($_POST['tuKhoa']) || empty($_POST['thumbnail']) || empty($_POST['author']) || empty($_POST['baoTri']) || empty($_POST['noiDungBaoTri'])) {
        admin_msg_error("Vui lòng nhập đầy đủ thông tin", "", 500);
    }
    $Database->update("hethong", array(
        'BASE_URL' => $_POST['baseUrl'],
        'LinkIcon' => $_POST['linkIcon'],

        'GOOGLE_APP_ID' => $_POST['googleAppId'],
        'GOOGLE_APP_SECRET' => $_POST['googleAppSecret'],
        'GOOGLE_APP_CALLBACK_URL' => $_POST['googleAppCallbackUrl'],

        'FACEBOOK_APP_ID' => $_POST['facebookAppId'],
        'FACEBOOK_APP_SECRET' => $_POST['facebookAppSecret'],
        'FACEBOOK_APP_CALLBACK_URL' => $_POST['facebookAppCallbackUrl'],
        'OPENAI_API_KEY' => $_POST['openAiApiKey'],

        'TenWeb' => $_POST['tenWeb'],
        'Email'           => $_POST['email'],
        'PassEmail'  => $_POST['passEmail'],
        'DefaultAvatar'         => $_POST['defaultAvatar'],
        'Mota'         => $_POST['moTa'],
        'TuKhoa'         => $_POST['tuKhoa'],
        'Thumbnail'         => $_POST['thumbnail'],
        'Author'         => $_POST['author'],
        'NoiDungBaoTri'         => $_POST['noiDungBaoTri'],
        'BaoTri'         => $_POST['baoTri'],

    ), " `ID` = '1' ");
    admin_msg_success("Thay đổi thành công", "", 1000);
}


?>



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Chỉnh sửa hệ thống</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">CHỈNH SỬA HỆ THỐNG</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tên website</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="tenWeb" value="<?= getData('TenWeb'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Link website</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="baseUrl" value="<?= getData('BASE_URL'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Email thông báo</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="email" value="<?= getData('Email'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Mật khẩu email thông báo</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="passEmail" value="<?= getData('PassEmail'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Ảnh đại diện mặc định</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="defaultAvatar" value="<?= getData('DefaultAvatar'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Mô tả website</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="moTa" value="<?= getData('MoTa'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Từ khóa website</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="tuKhoa" value="<?= getData('TuKhoa'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Thumbnail website</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="thumbnail" value="<?= getData('Thumbnail'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Author website</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="author" value="<?= getData('Author'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">GOOGLE_APP_ID</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="googleAppId" value="<?= getData('GOOGLE_APP_ID'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">GOOGLE_APP_SECRET</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="googleAppSecret" value="<?= getData('GOOGLE_APP_SECRET'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">GOOGLE_APP_CALLBACK_URL</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="googleAppCallbackUrl" value="<?= getData('GOOGLE_APP_CALLBACK_URL'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">FACEBOOK_APP_ID</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="facebookAppId" value="<?= getData('FACEBOOK_APP_ID'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">FACEBOOK_APP_SECRET</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="facebookAppSecret" value="<?= getData('FACEBOOK_APP_SECRET'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">FACEBOOK_APP_CALLBACK_URL</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="facebookAppCallbackUrl" value="<?= getData('FACEBOOK_APP_CALLBACK_URL'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">OPENAI_API_KEY</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="openAiApiKey" value="<?= getData('OPENAI_API_KEY'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Link icon</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="linkIcon" value="<?= getData('LinkIcon'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nội dung bảo trì</label>
                                <div class="col-sm-10">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="noiDungBaoTri" value="<?= getData('NoiDungBaoTri'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label">Bảo trì</label>
                                <div class="col-sm-10">
                                    <select class="custom-select" name="baoTri">
                                        <option value="<?= getData('BaoTri'); ?>">
                                            <?php
                                            if (getData('BaoTri') == "ON") {
                                                echo 'Có';
                                            }
                                            if (getData('BaoTri') == "OFF") {
                                                echo 'Không';
                                            }
                                            ?>
                                        </option>
                                        <option value="ON">Có</option>
                                        <option value="OFF">Không</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" name="btnSave" class="btn btn-primary btn-block waves-effect">
                                <span>LƯU</span>
                            </button>
                            <a type="button" href="<?= BASE_URL('admin/home'); ?>" class="btn btn-danger btn-block waves-effect">
                                <span>TRỞ LẠI</span>
                            </a>
                        </form>
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