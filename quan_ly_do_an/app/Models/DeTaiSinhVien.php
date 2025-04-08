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
}
