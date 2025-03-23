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
        return $this->belongsToMany(GiangVien::class, 'giang_vien_de_tai', 'ma_de_tai', 'ma_gv');
    }
}
