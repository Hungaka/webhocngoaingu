<?php

require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Quản lý Chat Bot';
require_once(__DIR__ . "/Header.php");
require_once(__DIR__ . "/Sidebar.php");

if (isset($_GET["delete_chat_room"])) {
    $getRoom = $_GET["room"];
    $Database->query("delete from message_chatbot_room where MaRoom = '" . $getRoom . "' ");
    $Database->query("delete from chatbot_room where MaRoom = '" . $getRoom . "' ");
    admin_msg_success("Xóa thành công", "chatgpt", 1000);
}
if (isset($_GET["delete_message_chat_bot"])) {
    $getTinNhan = $_GET["tinNhan"];
    $Database->query("delete from message_chatbot_room where MaTinNhan = '" . $getTinNhan . "' ");
    admin_msg_success("Xóa thành công", "chatgpt", 1000);
}

?>



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quản lý chat bot</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">DANH SÁCH CHAT BOT ROOM</h3>
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
                                        <th>Mã room</th>
                                        <th>Tài khoản</th>
                                        <th>Thời gian</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ($Database->get_list(" SELECT * FROM chatbot_room ORDER BY MaRoom DESC ") as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $row['MaRoom']; ?></td>
                                            <td><a href="<?= BASE_URL('admin/users/edit/'); ?><?= $row['TaiKhoan']; ?>"><?= $row['TaiKhoan']; ?></a></td>
                                            <td><span class="badge badge-dark"><?= $row['ThoiGian']; ?></span></td>

                                            <td>
                                                <a type="button" href="?delete_chat_room&room=<?= $row['MaRoom']; ?>" class="btn btn-primary"><i class="fas fa-trash"></i>
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

            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách chat bot message</h3>
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
                                        <th>Mã tin nhắn</th>
                                        <th>Mã room</th>
                                        <th>Nội dung</th>
                                        <th>Thời gian</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ($Database->get_list(" SELECT * FROM message_chatbot_room A inner join chatbot_room B on A.MaRoom = B.MaRoom and A.Role = 'user'  ORDER BY A.ThoiGian DESC ") as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><a href="<?= BASE_URL('admin/users/edit/'); ?><?= $row['TaiKhoan']; ?>"><?= $row['TaiKhoan']; ?></a></td>
                                            <td><?= $row['MaTinNhan']; ?></td>
                                            <td><?= $row['MaRoom']; ?></td>
                                            <td><?= $row['NoiDung']; ?></td>

                                            <td><span class="badge badge-dark px-3"><?= $row['ThoiGian']; ?></span></td>
                                            <td>
                                                <a type="button" href="?delete_message_chat_bot&tinNhan=<?= $row['MaTinNhan']; ?>" class="btn btn-primary"><i class="fas fa-trash"></i>
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