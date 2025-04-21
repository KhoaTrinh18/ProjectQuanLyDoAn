<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoMon extends Model
{
    use HasFactory;

    protected $table = 'bo_mon';
    protected $primaryKey = 'ma_bo_mon';
    public $timestamps = false;

    public function hoiDongs() {
        return $this->hasMany(HoiDong::class, 'ma_bo_mon', 'ma_bo_mon');
    }
}
