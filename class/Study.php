<?php
class Study extends Database {
    
    public function insertTuVungDaHoc($agr) {
        $this->connect();
        $table = "hoctuvung";
        $data = array(
            'MaTuVung' => $agr["MaTuVung"],
            'MaKhoaHoc' => $agr["MaKhoaHoc"],
            'MaBaiHoc' => $agr["MaBaiHoc"],
            'TaiKhoan' => $agr["TaiKhoan"]
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
    public function updateKinhNghiem($kinhNghiem, $taiKhoan) {
        $this->connect();
        $this->query("update nguoidung set KinhNghiem = KinhNghiem + '" . $kinhNghiem . "', TongKinhNghiem = TongKinhNghiem + '" . $kinhNghiem . "' where TaiKhoan =  '" . $taiKhoan . "' "); 
    }

    public function danhDauTuKho($agr) {
        $this->connect();
        $table = 'hoctuvung';
        $data = array(
            'MaTuVung' => $agr["MaTuVung"],
            'MaKhoaHoc' => $agr["MaKhoaHoc"],
            'MaBaiHoc' => $agr["MaBaiHoc"],
            'TaiKhoan' => $agr["TaiKhoan"]
        );

        $sql = "UPDATE $table SET TuKho = 1 WHERE MaTuVung = '".$data["MaTuVung"] ."' and MaKhoaHoc = '".$data["MaKhoaHoc"] ."' and MaBaiHoc = '".$data["MaBaiHoc"] ."'  and  TaiKhoan = '".$data["TaiKhoan"] ."' " ;
        return mysqli_query($this->ketnoi, $sql);
    }
    public function huyDanhDauTuKho($agr) {
        $this->connect();
        $table = 'hoctuvung';
        $data = array(
            'MaTuVung' => $agr["MaTuVung"],
            'MaKhoaHoc' => $agr["MaKhoaHoc"],
            'MaBaiHoc' => $agr["MaBaiHoc"],
            'TaiKhoan' => $agr["TaiKhoan"]
        );

        $sql = "UPDATE $table SET TuKho = 0 WHERE MaTuVung = '".$data["MaTuVung"] ."' and MaKhoaHoc = '".$data["MaKhoaHoc"] ."' and MaBaiHoc = '".$data["MaBaiHoc"] ."'  and  TaiKhoan = '".$data["TaiKhoan"] ."' " ;
        return mysqli_query($this->ketnoi, $sql);
    }
}
