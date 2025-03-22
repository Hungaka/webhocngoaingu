<?php
class HoatDong extends Database {
    public function insertHoatDong($arg) {
        $this->connect();
        $table = "hoatdong";
        $data = array(
            'TenHoatDong' => $arg["TenHoatDong"],
            'NoiDung' => $arg["NoiDung"],
            'TaiKhoan' => $arg["TaiKhoan"],
            'MaLoaiHoatDong' => $arg["MaLoaiHoatDong"]
        );
        $field_list = '';
        $value_list = '';
        foreach ($data as $key => $value) {
            $field_list .= ",$key";
            $value_list .= ",'" . mysqli_real_escape_string($this->ketnoi, $value) . "'";
        }
        $sql = 'INSERT INTO ' . $table . '(' . trim($field_list, ',') . ') VALUES (' . trim($value_list, ',') . ')';

        return mysqli_query($this->ketnoi, $sql);
    }

}
?>