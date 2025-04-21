<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoiDongGiangVien extends Model
{
    use HasFactory;

    protected $table = 'hoi_dong_giang_vien';
    protected $primaryKey = null; 
    public $incrementing = false;
    public $timestamps = false;
}