<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoiDong extends Model
{
    use HasFactory;

    protected $table = 'hoi_dong';
    protected $primaryKey = 'ma_hoi_dong';
    public $timestamps = false;

    public function chuyenNganh()
    {
        return $this->belongsTo(BoMon::class, 'ma_bo_mon', 'ma_bo_mon');
    }

    public function giangViens()
    {
        return $this->belongsToMany(GiangVien::class, 'hoi_dong_giang_vien', 'ma_hoi_dong', 'ma_gv')->withPivot('chuc_vu');
    }
}