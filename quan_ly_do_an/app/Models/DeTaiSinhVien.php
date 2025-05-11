<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeTaiSinhVien extends Model
{
    use HasFactory;

    protected $table = 'de_tai_sv';
    protected $primaryKey = 'ma_de_tai';
    public $timestamps = false;

    public function linhVuc()
    {
        return $this->belongsTo(LinhVuc::class, 'ma_linh_vuc', 'ma_linh_vuc');
    }

    public function sinhViens(){
        return $this->belongsToMany(SinhVien::class, 'sinh_vien_de_tai_sv', 'ma_de_tai', 'ma_sv')->where('sinh_vien.trang_thai', '!=', '3');
    }

    public function sinhVienPhanBiens(){
        return $this->belongsToMany(SinhVien::class, 'bang_diem_gvpb_cho_svdx', 'ma_de_tai', 'ma_sv')->where('sinh_vien.trang_thai', '!=', '3')->distinct();
    }

    public function sinhVienHoiDongs(){
        return $this->belongsToMany(SinhVien::class, 'bang_diem_gvthd_cho_svdx', 'ma_de_tai', 'ma_sv')->where('sinh_vien.trang_thai', '!=', '3')->distinct();
    }

    public function giangViens(){
        return $this->belongsToMany(GiangVien::class, 'bang_phan_cong_svdx', 'ma_de_tai', 'ma_gvhd')->distinct();
    }

    public function giangVienHuongDans(){
        return $this->belongsToMany(GiangVien::class, 'bang_phan_cong_svdx', 'ma_de_tai', 'ma_gvhd')->distinct()->withPivot(['diem_gvhd', 'nhan_xet', 'ma_sv']);
    }

    public function giangVienPhanBiens(){
        return $this->belongsToMany(GiangVien::class, 'bang_diem_gvpb_cho_svdx', 'ma_de_tai', 'ma_gvpb')->distinct()->withPivot(['diem_gvpb', 'nhan_xet', 'ma_sv']);
    }

    public function HoiDongs()
    {
        return $this->belongsToMany(HoiDong::class, 'bang_diem_gvthd_cho_svdx', 'ma_de_tai', 'ma_hoi_dong')->distinct();
    }

    public function ngayDeXuat()
    {
        return $this->hasOne(SinhVienDeTaiSV::class, 'ma_de_tai', 'ma_de_tai');
    }
}
