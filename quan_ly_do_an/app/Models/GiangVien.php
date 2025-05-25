<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiangVien extends Model
{
    use HasFactory;

    protected $table = 'giang_vien';
    protected $primaryKey = 'ma_gv';
    public $timestamps = false;

    public function hocVi()
    {
        return $this->belongsTo(HocVi::class, 'ma_hoc_vi', 'ma_hoc_vi');
    }

    public function boMon()
    {
        return $this->belongsTo(BoMon::class, 'ma_bo_mon', 'ma_bo_mon');
    }

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoanGV::class, 'ma_tk', 'ma_tk');
    }

    public function deTais()
    {
        return $this->belongsToMany(DeTaiGiangVien::class, 'giang_vien_de_tai_gv', 'ma_gv', 'ma_de_tai');
    }

    public function sinhVienDangKys()
    {
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        return $this->belongsToMany(SinhVien::class, 'bang_phan_cong_svdk', 'ma_gvhd', 'ma_sv')
            ->join('de_tai_gv', 'bang_phan_cong_svdk.ma_de_tai', '=', 'de_tai_gv.ma_de_tai')
            ->where(['de_tai_gv.da_xac_nhan_huong_dan' => 1, 'de_tai_gv.nam_hoc' => $thietLap->nam_hoc])
            ->select('sinh_vien.*')
            ->distinct();
    }

    public function sinhVienDeXuats()
    {
        $thietLap = ThietLap::where('trang_thai', 1)->first();
        return $this->belongsToMany(SinhVien::class, 'bang_phan_cong_svdx', 'ma_gvhd', 'ma_sv')
            ->join('de_tai_sv', 'bang_phan_cong_svdx.ma_de_tai', '=', 'de_tai_sv.ma_de_tai')
            ->where('de_tai_sv.nam_hoc', $thietLap->nam_hoc)
            ->select('sinh_vien.*')
            ->distinct();
    }

    public function deTaiDangKys()
    {
        return $this->belongsToMany(DeTaiGiangVien::class, 'bang_phan_cong_svdk', 'ma_gvhd', 'ma_de_tai')->distinct();
    }

    public function hoiDongs()
    {
        return $this->belongsToMany(HoiDong::class, 'hoi_dong_giang_vien', 'ma_gv', 'ma_hoi_dong');
    }
}
