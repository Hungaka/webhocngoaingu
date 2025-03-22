<?php
require_once("../../configs/config.php");
require_once("../../configs/function.php");



if (empty($_POST['type'])) {
    $result = array(
        'status' => 'error',
        'message' => 'Dữ liệu không tồn tại'
    );
    return die(json_encode($result));
}
if (!isset($_SESSION["account"])) {
    $result = array(
        'status' => 'error',
        'message' => getMessageError2('Vui lòng đăng nhập vào hệ thống')
    );
    return die(json_encode($result));
}
checkAccountExist();

if ($_POST['type'] == 'TaoRoom') {
    try {
        $taiKhoan = $_SESSION["account"];
        $checkRoom = $Database->get_row("SELECT * FROM chatbot_room WHERE TaiKhoan = '" . $taiKhoan . "'");
        if ($checkRoom > 0) {
            throw new Exception(getMessageError2('Bạn đã mở room chat bot rồi'));
        }
        $Database->insert("chatbot_room", [
            'TaiKhoan' => $taiKhoan
        ]);
        // thêm vào hoạt động 
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 1,
            'TenHoatDong' => 'Tạo phòng chat bot',
            'NoiDung' => 'Tạo phòng chat bot mới',
            'TaiKhoan' => $taiKhoan
        ]);
        $result = array(
            'status' => 'success',
            'message' => getMessageSuccess2('Thành công'),
        );
        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage()
        );
        return die(json_encode($result));
    }
}
if ($_POST['type'] == 'UpdateChatBotResponse') {
    try {
        $taiKhoan = $_SESSION["account"];
        $content = ($_POST["content"]);
        $thoiGian = $_POST["thoiGian"];
        $room = $_POST["room"];

        $checkRoom = $Database->get_row("SELECT * FROM chatbot_room WHERE TaiKhoan = '" . $taiKhoan . "' and MaRoom = '" . $room . "' ");
        if ($checkRoom <= 0) {
            throw new Exception(getMessageError2('Bạn chưa mở room chat bot'));
        }
        // Thêm câu trả lời của chat bot vào database
        $Database->insert("message_chatbot_room", [
            'MaRoom' => $room,
            'NoiDung' => $content,
            'ThoiGian' => $thoiGian,
            'Role' => 'assistant'
        ]);


        $result = array(
            'status' => 'success',
            'message' => getMessageSuccess2('Thành công'),
        );
        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage()
        );
        return die(json_encode($result));
    }
}
if ($_POST['type'] == 'SendMessage') {
    try {
        $taiKhoan = $_SESSION["account"];
        $content = check_string($_POST["content"]);
        $room = $_POST["room"];
        if (empty($content) || empty($room)) {
            throw new ErrorException(getMessageError2('Vui lòng nhập đầy đủ dữ liệu'));
        }
        $checkChatBotRoom = $Database->get_row("select * from chatbot_room where TaiKhoan = '" . $_SESSION["account"] . "' and MaRoom = '" . $room . "' ");
        if ($checkChatBotRoom <= 0) {
            throw new ErrorException(getMessageError2('Phòng chat bot không tồn tại'));
        }
        $getTime =  getTime();
        // insert vào lịch sử chat
        $insertResult =  $Database->insert("message_chatbot_room", [
            'MaRoom' => $room,
            'NoiDung' => ($content),
            'ThoiGian' =>  $getTime,
            'Role' => 'user'
        ]);
        // thêm vào hoạt động 
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 1,
            'TenHoatDong' => 'Hỏi chatbot 5Fs Group',
            'NoiDung' => 'Hỏi chat bot về câu hỏi: "' . ($content) . '"',
            'TaiKhoan' => $taiKhoan
        ]);
        $result = array(
            'status' => 'success',
            'message' => getMessageSuccess2('Thành công'),
            'data' => array(
                'content' => $content,
                'createdAt' =>
                $getTime
            )

        );
        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage()
        );
        return die(json_encode($result));
    }
}

if ($_POST['type'] == 'DeleteMessages') {
    try {
        $taiKhoan = $_SESSION["account"];
        $room = $_POST["room"];
        if (empty($room)) {
            throw new ErrorException(getMessageError2('Vui lòng nhập phòng chat'));
        }
        $checkChatBotRoom = $Database->get_row("select * from chatbot_room where TaiKhoan = '" . $_SESSION["account"] . "' and MaRoom = '" . $room . "' ");
        if ($checkChatBotRoom <= 0) {
            throw new ErrorException(getMessageError2('Phòng chat bot không tồn tại'));
        }
        // Xóa tin nhắn
        $Database->query("delete from message_chatbot_room where MaRoom = '" . $room . "' ");
        // thêm vào hoạt động 
        $HoatDong->insertHoatDong([
            'MaLoaiHoatDong' => 1,
            'TenHoatDong' => 'Xóa tin nhắn Chatbot 5Fs Group',
            'NoiDung' => 'Xóa tin nhắn Chatbot',
            'TaiKhoan' => $taiKhoan
        ]);
        $result = array(
            'status' => 'success',
            'message' => getMessageSuccess2('Thành công'),
        );
        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage()
        );
        return die(json_encode($result));
    }
}

if ($_POST['type'] == 'GetHistoryMessages') {
    try {
        $room = $_POST["room"];
        $checkChatBotRoom = $Database->get_row("select * from chatbot_room where TaiKhoan = '" . $_SESSION["account"] . "' and MaRoom = '" . $room . "' ");
        if ($checkChatBotRoom <= 0) {
            throw new ErrorException(getMessageError2('Phòng chat bot không tồn tại'));
        }
        $data = $Database->get_list("select * from message_chatbot_room where MaRoom = '" . $room . "' order by ThoiGian asc ");
        $result = array(
            'status' => 'success',
            'message' => getMessageSuccess2('Thành công'),
            'data' => $data
        );
        return die(json_encode($result));
    } catch (Exception $err) {
        $result = array(
            'status' => 'error',
            'message' => $err->getMessage()
        );
        return die(json_encode($result));
    }
}
