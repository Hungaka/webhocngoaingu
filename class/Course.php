<?php
class Course extends Database
{

    public function xoaTatCaTuVungDaHoc($agr)
    {
        $result =  $this->query("delete from hoctuvung where TaiKhoan = '" . $agr["TaiKhoan"] . "' and MaKhoaHoc = '" . $agr["MaKhoaHoc"] . "' ");
        return  $result;
    }
    public function xoaTatCaTuVungDaBoQua($agr)
    {
        $result =  $this->query("delete from boquatuvung where TaiKhoan = '" . $agr["TaiKhoan"] . "' and MaKhoaHoc = '" . $agr["MaKhoaHoc"] . "' ");
        return  $result;
    }
    public function xoaDangKyKhoaHoc($agr)
    {
        $result =  $this->query("delete from dangkykhoahoc where TaiKhoan = '" . $agr["TaiKhoan"] . "' and MaKhoaHoc = '" . $agr["MaKhoaHoc"] . "' ");
        return  $result;
    }
    public function dangKyKhoaHoc($agr)
    {
        $result = $this->insert("dangkykhoahoc", [
            'TaiKhoan' => $agr["TaiKhoan"],
            'MaKhoaHoc' => $agr["MaKhoaHoc"],
        ]);
        return  $result;
    }
}
