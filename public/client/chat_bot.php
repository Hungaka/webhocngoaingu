<?php
require_once(__DIR__ . "/../../configs/config.php");
require_once(__DIR__ . "/../../configs/function.php");
$title = 'Trợ giúp từ ChatBot | ' . $Database->site('TenWeb') . '';
$locationPage = 'chatbot_page';
require_once(__DIR__ . "/../../public/client/header.php");

checkLogin();
$checkChatBotRoom = $Database->get_row("select * from chatbot_room where TaiKhoan = '" . $_SESSION["account"] . "' ");
$getTaiKhoan = $Database->get_row("select * from nguoidung where TaiKhoan = '" . $_SESSION["account"] . "'");
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
                <div class="page__title">Trợ giúp từ ChatBot</div>
                <div class="table-rating__header">
                    <img src="https://i.imgur.com/0J4kSSu.png" alt="" class="table-rating__header-img">
                </div>
                <div class="table-rating__content">
                    <?php
                    if ($checkChatBotRoom <= 0) {
                    ?>
                        <div class="chatbot-content-text">
                            Bạn chưa có phòng chat nào với ChatBot 5FsGroup
                        </div>
                        <div class="btn btn--primary" onclick="createNewRoom()">Tạo room mới</div>
                    <?php
                    } else {
                    ?>


                        <div class="modal" id="modal-confirm-delete-messages">
                            <div class="modal-background"></div>
                            <div class="modal-content">
                                <div class="modal-content-body">
                                    <div class="modal-header__text">
                                        Xác nhận xóa lịch sử đoạn chat </div>
                                    <div class="modal-close modal-close-btn" aria-label="close">
                                    </div>
                                    <div class="modal-content-body__text">
                                        Bạn muốn xóa lịch sử chat không? Các tin nhắn sẽ bị xóa vĩnh viễn!
                                    </div>

                                    <div class="btn btn--primary" onclick="deleteMessages()">
                                        Xác nhận
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="chatbot_content" id="chat_content">
                        </div>
                        <div class="chatbot_form">
                            <textarea id="contentInput" class="chatbot_form_input-write" placeholder="Hãy hỏi gì đó"></textarea>
                            <div class="btn btn--primary" id="btnSend" onclick="sendMessage()">Gửi</div>

                        </div>
                        <div style="
    display: flex;
    justify-content: center;
">
                            <div class="btn btn--primary js-modal-trigger" data-target="modal-confirm-delete-messages">Xóa đoạn chat</div>

                        </div>


                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <script>
            <?php
            if ($checkChatBotRoom > 0) {
            ?>

                function updateChatBotResponse(content, timeResponse) {
                    $.ajax({
                        url: "<?= BASE_URL("assets/ajaxs/ChatBot.php"); ?>",
                        method: "POST",
                        data: {
                            type: 'UpdateChatBotResponse',
                            content: content,
                            thoiGian: timeResponse,
                            room: "<?= $checkChatBotRoom > 0 ? $checkChatBotRoom["MaRoom"] : '' ?>",
                        },

                    });

                }

                function getTimeCurrent() {
                    return moment().format("YYYY-MM-DD HH:mm:ss");
                }

                function htmlEncode(str) {
                    return String(str).replace(/[^\w. ]/gi, function(c) {
                        return '&#' + c.charCodeAt(0) + ';';
                    });
                }

                function sendMessage() {
                    $.ajax({
                        url: "<?= BASE_URL("assets/ajaxs/ChatBot.php"); ?>",
                        method: "POST",
                        data: {
                            type: 'SendMessage',
                            content: $("#contentInput").val(),
                            room: "<?= $checkChatBotRoom > 0 ? $checkChatBotRoom["MaRoom"] : '' ?>",
                        },
                        beforeSend: function() {
                            $('#btnSend').html('Đang xử lý').addClass("disabled");
                            $('#contentInput').addClass("disabled");

                        },
                        success: function(response) {
                            let json = $.parseJSON(response);
                            if (json.status === "error") {
                                $('#btnSend').html('Gửi').removeClass("disabled");
                                $('#contentInput').removeClass("disabled");
                                $('#thongbao').append(json.message);

                            } else if (json.status === "success") {
                                $("#chat_content .chatbot-content-text").remove();
                                let noiDungUser = (json.data.content).replace(/(?:\r\n|\r|\n)/g, '<br>');
                                appendMessage("user", "<?= $getTaiKhoan["AnhDaiDien"] ?>", "<?= $getTaiKhoan["TenHienThi"] ?>", noiDungUser, json.data.createdAt);
                                $("#contentInput").val("");
                                let uuid = uuidv4()
                                const eventSource = new EventSource(`<?= BASE_URL("Page/ChatBotStream") ?>?chat_room_id=<?= $checkChatBotRoom > 0 ? $checkChatBotRoom["MaRoom"] : '' ?>`);

                                let timeResponse = getTimeCurrent();

                                appendMessage("assistant", "<?= BASE_URL("assets/img/logo.png") ?>", "5Fs Group", "", timeResponse, uuid);
                                const div = document.getElementById(uuid);
                                let txtDatabase = ``;

                                eventSource.onmessage = function(e) {
                                    if (e.data == "[DONE]") {
                                        eventSource.close();
                                        updateChatBotResponse(txtDatabase, timeResponse);
                                        txtDatabase = ``;
                                        $('#btnSend').html('Gửi').removeClass("disabled");
                                        $('#contentInput').removeClass("disabled");

                                    } else {

                                        let txt = JSON.parse(e.data).choices[0].delta.content;
                                        if (txt !== undefined) {
                                            txtDatabase += txt;
                                            div.innerHTML += txt.replace(/(?:\r\n|\r|\n)/g, '<br>');
                                        }

                                    }
                                };

                                eventSource.onerror = function(e) {
                                    console.log(e);
                                    toastr.error("Chatbot đang gặp vấn đề, vui lòng thử lại hoặc xóa đoạn chat đi", "Lỗi hệ thống!");
                                    eventSource.close();
                                    $('#btnSend').html('Gửi').removeClass("disabled");
                                    $('#contentInput').removeClass("disabled");
                                };
                            }
                        }
                    });

                }

                function text(str) {
                    return $('<div>', {
                        text: str
                    }).text();
                }

                function deleteMessages() {
                    $.ajax({
                        url: "<?= BASE_URL("assets/ajaxs/ChatBot.php"); ?>",
                        method: "POST",
                        data: {
                            type: 'DeleteMessages',
                            room: "<?= $checkChatBotRoom > 0 ? $checkChatBotRoom["MaRoom"] : '' ?>",
                        },
                        beforeSend: function() {
                            $('#loading_modal').addClass("loading--open");
                        },
                        success: function(response) {
                            $('#loading_modal').removeClass("loading--open");
                            let json = $.parseJSON(response);
                            $('#thongbao').append(json.message);
                            if (json.status === "success") {
                                $("#chat_content").empty();
                                window.location.reload();

                            }
                        }
                    });

                }

                function uuidv4() {
                    return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
                    );
                }

                function getHistoryMessages() {
                    $.ajax({
                        url: "<?= BASE_URL("assets/ajaxs/ChatBot.php"); ?>",
                        method: "POST",
                        data: {
                            type: 'GetHistoryMessages',
                            room: "<?= $checkChatBotRoom > 0 ? $checkChatBotRoom["MaRoom"] : '' ?>",
                        },
                        beforeSend: function() {
                            $('#loading_modal').addClass("loading--open");

                        },
                        success: function(response) {
                            $("#contentInput").val("");
                            $('#loading_modal').removeClass("loading--open");
                            let json = $.parseJSON(response);
                            if (json.status === "error") {
                                $('#thongbao').append(json.message);

                            } else if (json.status === "success") {
                                if (json.data.length === 0) {
                                    $("#chat_content").append(` <div class="chatbot-content-text">
                            Bạn chưa có tin nhắn nào với ChatBot 5FsGroup
                        </div><div class="chatbot-content-text">
                            Hãy thử hỏi ChatBot ở phía dưới
                        </div>`);

                                } else {

                                    let data = createHistoryMessages(json.data);
                                    $("#chat_content").append(data);
                                    scrollToBottom();
                                }



                            }
                        }
                    });

                }

                function createHistoryMessages(data) {
                    let str = ``;
                    data.forEach((item) => {
                        const noiDung = (item.NoiDung.replace(/(?:\r\n|\r|\n)/g, '<br>'))
                        str += `          <div class="chatbot ${item.Role === "user" ? "right" : "left"}">
                                <img src="${item.Role === "user" ? "<?= $getTaiKhoan["AnhDaiDien"]  ?>"  : "<?= BASE_URL("assets/img/logo.png") ?>"}" alt="" class="chatbot-avata ${item.Role === "user" ? "right" : "left"}">
                                <div class="chatbot-content card">
                                    <div class="chatbot-content-wrap">
                                        <div class="chatbot-name">${item.Role === "user" ? "<?= $getTaiKhoan["TenHienThi"]  ?>"  : "5Fs Group"}</div>
                                        <div class="chatbot-paragraph">${noiDung}</div>
                                        <div class="chatbot-time">${item.ThoiGian}</div>
                                    </div>
                                </div>
                            </div>`;

                    })
                    return str;

                }

                function appendMessage(role, anhDaiDien, tenHienThi, noiDung, thoiGian, id) {
                    let data = ``;
                    data = `<div class="chatbot ${role === "user" ? "right" : "left"}">
                                <img src="${role === "user" ? anhDaiDien  : "<?= BASE_URL("assets/img/logo.png") ?>"}" alt="" class="chatbot-avata ${role === "user" ? "right" : "left"}">
                                <div class="chatbot-content card">
                                    <div class="chatbot-content-wrap">
                                        <div class="chatbot-name">${role === "user" ? tenHienThi  : "5Fs Group"}</div>
                                        <div class="chatbot-paragraph" id=${id}>${noiDung}</div>
                                        <div class="chatbot-time">${thoiGian}</div>
                                    </div>
                                </div>
                            </div>`;

                    $("#chat_content").append(data);
                    scrollToBottom();


                }

                function scrollToBottom() {
                    var elem = document.getElementById('chat_content');
                    elem.scrollTop = elem.scrollHeight;

                };
            <?php
            } else {
            ?>

                function createNewRoom() {
                    $.ajax({
                        url: "<?= BASE_URL("assets/ajaxs/ChatBot.php"); ?>",
                        method: "POST",
                        data: {
                            type: 'TaoRoom',
                        },
                        beforeSend: function() {
                            $('#loading_modal').addClass("loading--open");

                        },
                        success: function(response) {
                            $('#loading_modal').removeClass("loading--open");
                            let json = $.parseJSON(response);
                            $('#thongbao').append(json.message);
                            if (json.status === "success") {
                                window.location.reload();
                            }
                        }
                    });

                }
            <?php

            }
            ?>



            $(document).ready(function() {
                <?php
                if ($checkChatBotRoom > 0) {
                ?>
                    getHistoryMessages();
                <?php
                }

                ?>




            });
        </script>

        <div class="menu_right-container">

            <div class="statistical">
                <div class="interact">

                    <div class="interact__question-congratulations">
                        <div class="interact__question-title">GIỚI THIỆU </div>
                        <div class="interact__question-text">
                            Dùng ChatBot 5Fs Group để giải đáp bất kỳ câu hỏi nào, đặc biệt là liên quan đến bài học
                        </div>
                        <img src="<?= BASE_URL("/") ?>/assets/img/congratulations.svg" alt="" class="interact__question-img">
                    </div>
                </div>

            </div>
        </div>
        <?php

        include_once(__DIR__ . "/../../public/client/navigation_mobile.php");

        ?>
        <?php
        require_once(__DIR__ . "/../../public/client/footer.php"); ?>