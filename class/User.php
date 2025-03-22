<?php
class User extends Database
{
    public function checkValidAccount($account)
    {
        $result = array(
            'status' => false,
            'message' => ''
        );
        if (empty($account)) {
            $result = array(
                'status' => false,
                'message' => 'Vui lòng nhập tên tài khoản!'
            );
        } else 
        if (checkContainSpecialCharacter($account)) {
            $result = array(
                'status' => false,
                'message' => 'Tên đăng nhập có chứa kí tự đặc biệt'
            );
        } else
        if (strlen($account) < 5 || strlen($account) > 100) {
            $result = array(
                'status' => false,
                'message' => 'Tên đăng nhập phải từ 5 kí tự trở lên và từ 100 kí tự trở xuống'
            );
        } else {
            $result = array(
                'status' => true,
            );
        }
        return $result;
    }
    public function checkValidPassword($password)
    {
        $result = array(
            'status' => false,
            'message' => ''
        );
        if (empty($password)) {
            $result = array(
                'status' => false,
                'message' => 'Vui lòng nhập mật khẩu!'
            );
        } else 
        if (strlen($password) < 5) {
            $result = array(
                'status' => false,
                'message' => 'Mật khẩu phải từ 5 kí tự trở lên'
            );
        } else {
            $result = array(
                'status' => true,
            );
        }
        return $result;
    }
    public function checkValidTenHienThi($tenHienThi)
    {
        $result = array(
            'status' => false,
            'message' => ''
        );
        if (empty($tenHienThi)) {
            $result = array(
                'status' => false,
                'message' => 'Vui lòng nhập tên hiển thị!'
            );
        } else 
        if (checkContainSpecialCharacter($tenHienThi)) {
            $result = array(
                'status' => false,
                'message' => 'Tên hiển thị có chứa kí tự đặc biệt'
            );
        } else
        if (strlen($tenHienThi) < 2 || strlen($tenHienThi) > 50) {
            $result = array(
                'status' => false,
                'message' => 'Tên hiển thị phải từ 2 kí tự trở lên và từ 50 kí tự trở xuống'
            );
        } else {
            $result = array(
                'status' => true,
            );
        }
        return $result;
    }
}
