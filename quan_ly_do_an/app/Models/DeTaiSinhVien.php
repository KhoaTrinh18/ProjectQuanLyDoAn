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
        return $this->belongsToMany(SinhVien::class, 'sinh_vien_de_tai_sv', 'ma_de_tai', 'ma_sv');
    }

    public function sinhVienPhanBiens(){
        return $this->belongsToMany(SinhVien::class, 'bang_diem_gvpb_cho_svdx', 'ma_de_tai', 'ma_sv')->distinct();
    }

    public function sinhVienHoiDongs(){
        return $this->belongsToMany(SinhVien::class, 'bang_diem_gvthd_cho_svdx', 'ma_de_tai', 'ma_sv')->distinct();
    }

    public function giangViens(){
        return $this->belongsToMany(GiangVien::class, 'bang_phan_cong_svdx', 'ma_de_tai', 'ma_gvhd')->distinct();
    }

    public function giangVienPhanBiens(){
        return $this->belongsToMany(GiangVien::class, 'bang_diem_gvpb_cho_svdx', 'ma_de_tai', 'ma_gvpb')->distinct();
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
