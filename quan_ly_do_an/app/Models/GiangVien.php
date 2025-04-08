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

    public function deTais()
    {
        return $this->belongsToMany(DeTaiGiangVien::class, 'giang_vien_de_tai_gv', 'ma_gv', 'ma_de_tai');
    }
}
