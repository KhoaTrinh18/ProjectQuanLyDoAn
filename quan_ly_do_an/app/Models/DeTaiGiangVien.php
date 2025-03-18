<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeTaiGiangVien extends Model
{
    use HasFactory;

    protected $table = 'de_tai_gv';

    public $timestamps = false;

    public function linhVuc()
    {
        return $this->belongsTo(LinhVuc::class, 'ma_linh_vuc', 'ma_linh_vuc');
    }
}
