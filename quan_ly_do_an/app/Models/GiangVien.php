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
}
