<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Bảng xếp hạng | ' . $Database->site('TenWeb') . '';
$locationPage = 'home_page';
require_once(__DIR__ . "/../../public/client/header.php");

checkLogin();
if (!empty($_GET["type"])) {
    $type = check_string($_GET["type"]);
} else {
    $type = 'capdo';
}
?>
<style>
    <?= include_once(__DIR__ . "/../../assets/css/bxh.css");
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
            <div class="table-rating">
                <div class="page__title">Bảng xếp hạng</div>
                <div class="table-rating__header">
                    <img src="<?= BASE_URL("/") ?>/assets/img/bxh.svg" alt="" class="table-rating__header-img">
                </div>
                <div class="table-rating__content">
                    <input type="hidden" id="currentPage" value="1" />
                    <input type="hidden" id="typeBXH" value="tabCapDo" />
                    <div class="tabcontrol-nav">
                        <div class="tablinks tabcontrol-item <?= $type == 'capdo' ? "tab-active" : "" ?> " onclick="openTab(event, 'tabCapDo')">Cấp độ</div>
                        <div class="tablinks tabcontrol-item <?= $type == 'tuvung' ? "tab-active" : "" ?>" onclick="openTab(event, 'tabTuVung')">Từ vựng</div>
                    </div>
                    <div id="tabCapDo" class="tabcontent" style="display: <?= $type == 'capdo' ? "block" : "none" ?>;">
                        <div class="table-rating__overview">

                            <div class="table-rating__overview-list-ratings" id="table-bxh-list-capdo">
                            </div>
                        </div>
                    </div>
                    <div id="tabTuVung" class="tabcontent" style="display: <?= $type == 'tuvung' ? "block" : "none" ?>;">
                        <div class="table-rating__overview">
                            <div class="table-rating__overview-list-ratings" id="table-bxh-list-tuvung">
                            </div>
                        </div>
                    </div>
                    <div class="table-rating__overview-btn-download-more btn btn--primary" id="btnLoadMoreBXHList" onclick="getListBXH('loadmore')">Tải thêm</div>

                </div>
            </div>
        </div>
        <script src="<?= BASE_URL("/") ?>/assets/javascript/tab_control.js?t=<?= rand(0, 99999) ?>"></script>

        <script>
            const BXH = {
                itemsPerPage: 10,
                currentPage: 1,
            };

            function openTab(evt, tabID) {
                // Declare all variables
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                // Get all elements with class="tablinks" and remove the class "active"
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" tab-active", "");
                }
                // Show the current tab, and add an "active" class to the button that opened the tab
                document.getElementById(tabID).style.display = "block";
                evt.currentTarget.className += " tab-active";
                $('#typeBXH').val(tabID);
                $('#currentPage').val(1);
                $("#table-bxh-list-capdo").empty();

                $("#table-bxh-list-tuvung").empty();

                getListBXH();
            }

            function updateSTTBXH() {
                const nodeList = document.querySelectorAll(".table-rating__overview-item-rating-index");

                for (let i = 0; i < nodeList.length; i++) {
                    (nodeList[i].innerHTML = i + 1);


                }


            }

            function getListBXH(type = "get") {
                $.ajax({
                    url: "<?= BASE_URL("assets/ajaxs/User.php"); ?>",
                    method: "POST",
                    data: {
                        type: 'getListBXH',
                        typeBXH: $("#typeBXH").val(),
                        itemsPerPage: BXH.itemsPerPage,
                        currentPage: $("#currentPage").val(),
                    },
                    beforeSend: function() {
                        $('#loading_modal').addClass("loading--open");
                    },
                    success: function(response) {
                        $('#loading_modal').removeClass("loading--open");
                        let json = $.parseJSON(response);
                        if (json.status === "error") {
                            $('#thongbao').append(json.message);

                        } else if (json.status === "success") {

                            let str = "";
                            if (json.data && json.data.map((item, index) => {
                                    str += ` <div class="table-rating__overview-item-rating">
                                        <div class="table-rating__overview-item-rating-index"></div>
                                        <img src="${item.AnhDaiDien}" alt="" class="table-rating__overview-item-rating-avata">
                                        <div class="table-rating__overview-item-rating-name"><a href="<?= BASE_URL("Page/TrangCaNhan/") ?>${item.TaiKhoan}/">${item.TenHienThi}</a></div>
                                        <div class="table-rating__overview-item-rating-points">${item.SoLuong}</div>
                                    </div>`;
                                }));
                            if (type == "get") {
                                if ($("#typeBXH").val() === 'tabCapDo') {

                                    $("#table-bxh-list-capdo").empty().append(str);
                                } else {
                                    $("#table-bxh-list-tuvung").empty().append(str);
                                }
                                $("#currentPage").val(1);
                            } else if (type == "loadmore") {
                                if ($("#typeBXH").val() === 'tabCapDo') {

                                    $("#table-bxh-list-capdo").append(str);
                                } else {
                                    $("#table-bxh-list-tuvung").append(str);
                                }

                            }

                            if (json.results < BXH.itemsPerPage) {
                                $('#btnLoadMoreBXHList').hide();
                            } else {
                                const currentPage = $("#currentPage").val();
                                $("#currentPage").val(parseInt(currentPage) + 1);
                                $('#btnLoadMoreBXHList').show();


                            }
                            updateSTTBXH();
                        }
                    }
                });

            }
            $(document).ready(function() {

                getListBXH();



            });
        </script>
        <?php
        include_once(__DIR__ . "/../../public/client/menu_right_bxh.php");
        include_once(__DIR__ . "/../../public/client/navigation_mobile.php");

        ?>
        <?php
        require_once(__DIR__ . "/../../public/client/footer.php"); ?>