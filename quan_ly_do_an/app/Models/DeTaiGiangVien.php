<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeTaiGiangVien extends Model
{
    use HasFactory;

    protected $table = 'de_tai_gv';
    protected $primaryKey = 'ma_de_tai';
    public $timestamps = false;

    public function linhVuc()
    {
        return $this->belongsTo(LinhVuc::class, 'ma_linh_vuc', 'ma_linh_vuc');
    }

    public function giangViens()
    {
        return $this->belongsToMany(GiangVien::class, 'giang_vien_de_tai_gv', 'ma_de_tai', 'ma_gv');
    }

    public function giangVienHuongDans()
    {
        return $this->belongsToMany(GiangVien::class, 'bang_phan_cong_svdk', 'ma_de_tai', 'ma_gvhd')->withPivot(['diem_gvhd', 'nhan_xet']);
    }

    public function giangVienPhanBiens()
    {
        return $this->belongsToMany(GiangVien::class, 'bang_diem_gvpb_cho_svdk', 'ma_de_tai', 'ma_gvpb')->distinct()->withPivot(['diem_gvpb', 'nhan_xet']);
    }

    public function HoiDongs()
    {
        return $this->belongsToMany(HoiDong::class, 'bang_diem_gvthd_cho_svdk', 'ma_de_tai', 'ma_hoi_dong')->distinct();
    }

    public function sinhViens(){
        return $this->belongsToMany(SinhVien::class, 'bang_phan_cong_svdk', 'ma_de_tai', 'ma_sv')->distinct();
    }

    public function sinhVienPhanBiens()
    {
        return $this->belongsToMany(SinhVien::class, 'bang_diem_gvpb_cho_svdk', 'ma_de_tai', 'ma_sv')->distinct();
    }

    public function sinhVienHoiDongs()
    {
        return $this->belongsToMany(SinhVien::class, 'bang_diem_gvthd_cho_svdk', 'ma_de_tai', 'ma_sv')->distinct();
    }

    public function ngayDuaRa()
    {
        return $this->hasOne(GiangVienDeTaiGV::class, 'ma_de_tai', 'ma_de_tai');
    }
}
