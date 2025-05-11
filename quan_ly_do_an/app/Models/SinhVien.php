<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    use HasFactory;

    protected $table = 'sinh_vien';
    protected $primaryKey = 'ma_sv';
    public $timestamps = false;

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoanSV::class, 'ma_tk', 'ma_tk');
    }

    public function deTaiDeXuat()
    {
        return $this->belongsToMany(
            DeTaiSinhVien::class,
            'sinh_vien_de_tai_sv',
            'ma_sv',
            'ma_de_tai'
        )->wherePivot('trang_thai', '!=', 0)
         ->where('de_tai_sv.da_huy', 0);
    }
    
    public function deTaiDangKy()
    {
        return $this->belongsToMany(
            DeTaiGiangVien::class,
            'bang_phan_cong_svdk',
            'ma_sv',
            'ma_de_tai'
        )->where('de_tai_gv.da_huy', 0);
    }
}
