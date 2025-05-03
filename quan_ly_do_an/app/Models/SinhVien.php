<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    use HasFactory;

    protected $table = 'sinh_vien';
    protected $primaryKey = 'ma_sv';
    public $timestamps = false;

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoanSV::class, 'ma_tk', 'ma_tk');
    }
}
