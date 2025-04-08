<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaiKhoanGV extends Model
{
    use HasFactory;

    protected $table = 'tai_khoan_gv';
    protected $primaryKey = 'ma_lai_khoan';
    public $timestamps = false;
}