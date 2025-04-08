<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaiKhoanSV extends Model
{
    use HasFactory;

    protected $table = 'tai_khoan_sv';
    protected $primaryKey = 'ma_lai_khoan';
    public $timestamps = false;
}