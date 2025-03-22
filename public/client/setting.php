<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Chỉnh sửa trang cá nhân | ' . $Database->site("TenWeb") . '';
$locationPage = 'setting_page';
require_once(__DIR__ . "/../../public/client/header.php");

checkLogin();
$checkTaiKhoan = $Database->get_row("SELECT * FROM nguoidung WHERE TaiKhoan = '" . ($_SESSION["account"]) . "' ");
if ($checkTaiKhoan <= 0) {
    return die('<script type="text/javascript">
    setTimeout(function(){ location.href = "' . BASE_URL('/') . '" }, 0);
    </script>
    ');
}
if (!empty($_GET["type"])) {
    $type = check_string($_GET["type"]);
} else {
    $type = 'thongtin';
}
?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/setting_page.css");
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
            <div class="setting">
                <div class="page__title">Cài đặt</div>
                <div class="setting__header">
                    <img src="<?= $checkTaiKhoan["AnhDaiDien"] ?>" alt="" class="setting__header-img">
                    <div class="setting__header-content">
                        <div class="setting__header-name"><?= $checkTaiKhoan["TenHienThi"] ?></div>
                        <a href="<?= BASE_URL("Page/TrangCaNhan" . "/" . $checkTaiKhoan["TaiKhoan"]) ?>/" class="setting__header-wach-profile">Xem hồ sơ</a>
                    </div>
                </div>
                <div class="setting-content">
                    <div class="tabcontrol-nav">
                        <div class="tablinks tabcontrol-item <?= $type == 'thongtin' ? "tab-active" : "" ?>" onclick="openTab(event, 'tabThongTin')">Thông tin</div>
                        <div class="tablinks tabcontrol-item <?= $type == 'matkhau' ? "tab-active" : "" ?>" onclick="openTab(event, 'tabMatKhau')">Mật khẩu</div>
                        <div class="tablinks tabcontrol-item <?= $type == 'hethong' ? "tab-active" : "" ?>" onclick="openTab(event, 'tabHeThong')">Hệ thống</div>
                        <div class="tablinks tabcontrol-item <?= $type == 'muctieu' ? "tab-active" : "" ?>" onclick="openTab(event, 'tabMucTieu')">Mục tiêu</div>
                    </div>
                    <div class="tabcontent setting-box" id="tabThongTin" style="display: <?= $type == 'thongtin' ? "flex" : "none" ?>;">
                        <div class="setting-container">

                            <div class="setting-box__item" style="align-items: flex-start;">
                                <div class="setting-box__item-title">Ảnh hồ sơ</div>
                                <div class="setting-box__item-content">
                                    <label for="avatarProfile" id="btnSelectAvatarProfile" class="avatar-profile__choose-file">
                                        <input type="file" name="avatarProfile" id="avatarProfile" class="input-avatar-file" />

                                        Chọn tập tin

                                    </label>
                                    <div class="setting-box__item-text" id="nameAvatarFile">chưa chọn file</div>


                                    <div class="setting-box__item-text">chỉ chấp nhận file .png .jpeq .jpg</div>

                                </div>
                            </div>

                            <div class="setting-box__item">
                                <div class="setting-box__item-title">Tên hiển thị</div>
                                <div class="setting-box__item-content">
                                    <input type="text" class="input-text-profile" id="tenHienThiInput" value="<?= $checkTaiKhoan["TenHienThi"] ?>">
                                </div>
                            </div>
                            <div class="setting-box__item">
                                <div class="setting-box__item-title">Tên đăng nhập</div>
                                <div class="setting-box__item-content">
                                    <input type="text" class="input-text-profile" value="<?= $checkTaiKhoan["TaiKhoan"] ?>" disabled>
                                </div>
                            </div>
                            <div class="setting-box__item" style="align-items: flex-start;">
                                <div class="setting-box__item-title">Email</div>
                                <div class="setting-box__item-content">
                                    <input type="text" class="input-text-profile" value="<?= $checkTaiKhoan["Email"] ?>" id="emailInput" <?= $checkTaiKhoan["KichHoatEmail"] == 0 ? '' : 'disabled' ?>>
                                    <div class="avatar-profile-text-container">
                                        <?php

                                        if ($checkTaiKhoan["KichHoatEmail"] == 0) {
                                        ?>
                                            <div class="setting-box__item-text" style="color: red">Email chưa được xác nhận </div>

                                            <div class="setting-box__item-text" style="cursor: pointer; color: red" id="btnSendActiveEmail"><u>Xác nhận</u></div>

                                        <?php
                                        } else {
                                        ?>
                                            <div class="setting-box__item-text" style="color: var(--green-color)">Email đã được xác nhận </div>

                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div id="btnLuuThongTin" class="save-btn btn btn--primary">Lưu thay đổi</div>

                        </div>
                    </div>
                    <div class="tabcontent setting-box" id="tabMatKhau" style="display: <?= $type == 'matkhau' ? "flex" : "none" ?>;">
                        <div class="setting-container">

                            <div class="setting-box__item">
                                <div class="setting-box__item-title">Mật khẩu hiện tại</div>
                                <div class="setting-box__item-content">
                                    <input type="password" id="oldMatKhauInput" class="input-text-profile">
                                </div>
                            </div>
                            <div class="setting-box__item">
                                <div class="setting-box__item-title">Mật khẩu mới</div>
                                <div class="setting-box__item-content">
                                    <input type="password" id="newMatKhauInput" class="input-text-profile">
                                </div>
                            </div>
                            <div class="setting-box__item" style="justify-content: center;">
                                <input type="checkbox" id="hienThiMatKhauCheckBox" />
                                <div class="setting-box__item-text">Hiển thị mật khẩu</div>
                            </div>
                            <div class="save-btn btn btn--primary" id="btnLuuMatKhau">Lưu thay đổi</div>

                        </div>
                    </div>
                    <?php
                    $getThongBaoEmail = $Database->get_row("select * from thongbaoemail where TaiKhoan = '" . $checkTaiKhoan["TaiKhoan"] . "' ");
                    ?>
                    <div class="tabcontent setting-box" id="tabHeThong" style="display: <?= $type == 'hethong' ? "flex" : "none" ?>;">
                        <div class="setting-container">

                            <div class="setting-box__item" style="align-items: flex-start;">
                                <div class="setting-box__item-title">Gửi mail cho tôi khi</div>
                                <?php
                                if ($getThongBaoEmail) {
                                ?>
                                    <div class="setting-box__item-content">
                                        <div class="send-mail__file">
                                            <input id="capNhatMoi" type="checkbox" <?= boolval($getThongBaoEmail["CapNhatMoi"]) ? "checked" : "" ?> />
                                            <div class="setting-box__item-text">Có cập nhật mới</div>
                                        </div>
                                        <div class="send-mail__file">
                                            <input id="baoCaoTienTrinhHocTap" type="checkbox" <?= boolval($getThongBaoEmail["BaoCaoTienTrinhHocTap"]) ? "checked" : "" ?> />
                                            <div class="setting-box__item-text">Báo cáo tiến trình hằng ngày</div>
                                        </div>
                                        <div class="send-mail__file">
                                            <input id="nhacNhoTienTrinhHocTap" type="checkbox" <?= boolval($getThongBaoEmail["NhacNhoTienTrinhHocTap"]) ? "checked" : "" ?> />
                                            <div class="setting-box__item-text">Nhắc nhở tiến trình học tập</div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="save-btn btn btn--primary" id="btnCapNhatThongBaoEmail">Lưu thay đổi</div>

                        </div>
                    </div>
                    <div class="tabcontent setting-box" id="tabMucTieu" style="display: <?= $type == 'muctieu' ? "flex" : "none" ?>;">
                        <div class="setting-container">

                            <div class="setting-box__item-content">
                                <div class="setting-box__item-title">Sửa mục tiêu học tập hằng ngày</div>
                                <div class="setting-box__item-text">Chọn một mục tiêu mỗi ngày sẽ giúp bạn luôn có động lực khi học tập một ngôn ngữ. Nhưng đừng lo, bạn có thể thay đổi mục tiêu luyện tập bất cứ lúc nào.</div>
                                <div class="edit-target__content">
                                    <img src="<?= BASE_URL('/') ?>/assets/img/setting-target.svg" alt="" class="edit-target__content-img">
                                    <ul class="edit-target__content-list">
                                        <?php
                                        foreach ($Database->get_list(" SELECT * FROM muctieuhoctap ") as $mucTieuHocTap) {

                                        ?>
                                            <li data-course-target-id="<?= $mucTieuHocTap["MaMucTieu"] ?>" class="edit-target__content-item <?= $mucTieuHocTap["MaMucTieu"] == $checkTaiKhoan["MaMucTieu"] ? "edit-target__content-item--choose" : "" ?>"><span class="edit-target__content-item-text"><?= $mucTieuHocTap["TenMucTieu"] ?></span><span class="edit-target__content-item-number"><?= $mucTieuHocTap["NoiDungMucTieu"] ?></span></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="save-btn btn btn--primary" id="btnCapNhatMucTieuHocTap">Lưu thay đổi</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?= BASE_URL("/") ?>/assets/javascript/tab_control.js?t=<?= rand(0, 99999) ?>"></script>

        <script>
            let imageProfile = null;
            let mucTieuHocTapID = null;

            function handleMucTieuHocTap() {
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/User.php"); ?>",
                    method: "POST",
                    data: {
                        type: "capNhatMucTieuHocTap",
                        maMucTieuHocTap: mucTieuHocTapID,
                    },
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");
                        $("#btnCapNhatMucTieuHocTap").text("Đang tải...");
                        $("#btnCapNhatMucTieuHocTap").addClass("disabled");
                    },
                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        let json = $.parseJSON(response);
                        if (json && json.message) {
                            $("#thongbao").empty().append(json.message);
                            $("#btnCapNhatMucTieuHocTap").text("Lưu thay đổi");
                            $("#btnCapNhatMucTieuHocTap").removeClass("disabled");
                            if (json.status == "success") {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000)
                            }
                        }

                    }
                });

            }


            function initMucTieuHocTap() {
                const nodeList = document.querySelectorAll(".edit-target__content-item");
                for (let i = 0; i < nodeList.length; i++) {
                    // lấy ID mục tiêu học tập lần đầu của người dùng
                    if (mucTieuHocTapID == null) {
                        if ($(nodeList[i]).hasClass("edit-target__content-item--choose")) {
                            const courseTargetID = nodeList[i].getAttribute("data-course-target-id");
                            mucTieuHocTapID = courseTargetID;
                        }
                    }
                    // Xử lý sự kiện click từng mục tiêu
                    nodeList[i].addEventListener("click", () => {
                        const courseTargetID = nodeList[i].getAttribute("data-course-target-id");
                        if (!$(nodeList[i]).hasClass("edit-target__content-item--choose")) {
                            nodeList.forEach(e => {
                                e.classList.remove("edit-target__content-item--choose")
                            })

                            nodeList[i].classList.add("edit-target__content-item--choose");
                        }
                        mucTieuHocTapID = courseTargetID;
                        console.log(courseTargetID);
                    })
                }
            }

            function handleShowPassword() {
                if ($("#hienThiMatKhauCheckBox").is(":checked")) {
                    $("#oldMatKhauInput").attr("type", "text");
                    $("#newMatKhauInput").attr("type", "text");
                } else {
                    $("#oldMatKhauInput").attr("type", "password");
                    $("#newMatKhauInput").attr("type", "password");
                }
            }

            function handleCapNhatMatKhau() {
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/User.php"); ?>",
                    method: "POST",
                    data: {
                        type: "thayDoiMatKhau",
                        oldPassword: $("#oldMatKhauInput").val().trim(),
                        newPassword: $("#newMatKhauInput").val().trim(),
                    },
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");
                        $("#btnLuuMatKhau").text("Loading...");
                        $("#oldMatKhauInput").attr("disabled", true);
                        $("#newMatKhauInput").attr("disabled", true);
                        $("#btnLuuMatKhau").addClass("disabled");


                    },

                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        let json = $.parseJSON(response);
                        if (json && json.message) {
                            $("#thongbao").empty().append(json.message);
                            $("#btnLuuMatKhau").text("Lưu thay đổi");
                            $("#oldMatKhauInput").attr("disabled", false);
                            $("#newMatKhauInput").attr("disabled", false);
                            $("#btnLuuMatKhau").removeClass("disabled");
                            if (json.status == "success") {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000)
                            }
                        }

                    }
                });

            }

            function handleCapNhatThongBaoEmail() {
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/User.php"); ?>",
                    method: "POST",
                    data: {
                        type: "thongBaoEmail",
                        capNhatMoi: $('#capNhatMoi').is(":checked") ? 1 : 0,
                        baoCaoTienTrinhHocTap: $('#baoCaoTienTrinhHocTap').is(":checked") ? 1 : 0,
                        nhacNhoTienTrinhHocTap: $('#nhacNhoTienTrinhHocTap').is(":checked") ? 1 : 0,
                    },
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");
                    },

                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        let json = $.parseJSON(response);
                        if (json && json.message) {
                            $("#thongbao").empty().append(json.message);

                            if (json.status == "success") {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000)
                            }
                        }

                    }
                });

            }

            function handleLuuThongTin() {
                console.log($("#tenHienThiInput").val());
                let data = new FormData();
                if ($('#avatarProfile')[0].files[0]) {
                    data.append('avatarProfile', $('#avatarProfile')[0].files[0]);
                }
                data.append('type', 'updateThongTin');
                data.append('tenHienThi', $("#tenHienThiInput").val().trim());
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/User.php"); ?>",
                    method: "POST",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");
                        $("#btnLuuThongTin").text("Loading...");
                        $("#tenHienThiInput").attr("disabled", true);
                        $("#btnLuuThongTin").addClass("disabled");
                        $("#btnSelectAvatarProfile").addClass("disabled");
                    },
                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        let json = $.parseJSON(response);
                        if (json && json.message) {
                            $("#thongbao").empty().append(json.message);
                            $("#btnLuuThongTin").text("Lưu thay đổi");
                            $("#tenHienThiInput").attr("disabled", false);
                            $("#btnLuuThongTin").removeClass("disabled");
                            $("#btnSelectAvatarProfile").removeClass("disabled");
                            if (json.status == "success") {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000)
                            }
                        }

                    }
                });

            }

            function handleSendMailActive() {
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/User.php"); ?>",
                    method: "POST",
                    data: {
                        type: 'sendEmailActive',
                        email: $("#emailInput").val().trim(),
                    },
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");
                        $("#emailInput").attr("disabled", true);
                        $("#btnSendActiveEmail").text("Đang gửi");
                    },
                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        let json = $.parseJSON(response);
                        if (json && json.message) {
                            $("#emailInput").attr("disabled", false);
                            $("#btnSendActiveEmail").empty().append("<u>Xác nhận</u>");
                            $("#thongbao").empty().append(json.message);
                        }

                    }
                });

            }

            function addEventSelectAvatarProfile() {
                document.querySelector('#avatarProfile').addEventListener('change', function() {
                    if (this.files && this.files.length === 0) {
                        $("#nameAvatarFile").text("chưa chọn file");
                        imageProfileSrc = null;
                    } else
                    if (this.files && this.files[0]) {
                        $("#nameAvatarFile").text(this.files[0].name);
                        imageProfileSrc = this.files[0];
                    }
                });
            }
            $(document).ready(function() {

                initMucTieuHocTap();

                addEventSelectAvatarProfile();
                $("#btnCapNhatMucTieuHocTap").click(function() {
                    handleMucTieuHocTap();
                })
                $("#btnLuuThongTin").click(function() {
                    handleLuuThongTin();
                })
                $("#btnLuuMatKhau").click(function() {
                    handleCapNhatMatKhau();
                })
                $("#btnCapNhatThongBaoEmail").click(function() {
                    handleCapNhatThongBaoEmail();
                })
                $("#btnSendActiveEmail").click(function() {
                    handleSendMailActive();
                })
                $("#hienThiMatKhauCheckBox").click(function() {
                    handleShowPassword();
                })

            });
        </script>
        <?php
        include_once(__DIR__ . "/../../public/client/navigation_mobile.php");
        require_once(__DIR__ . "/../../public/client/footer.php"); ?>