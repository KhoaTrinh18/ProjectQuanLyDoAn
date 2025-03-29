<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiangVienDeTai extends Model
{
    use HasFactory;

    protected $table = 'giang_vien_de_tai';
    protected $primaryKey = null; 
    public $incrementing = false;
    public $timestamps = false;

    public function giangVien()
    {
        return $this->belongsTo(GiangVien::class, 'ma_gv', 'ma_gv');
    }

    public function deTaiGiangVien()
    {
        return $this->belongsTo(DeTaiGiangVien::class, 'ma_de_tai', 'ma_de_tai');
    }
}
