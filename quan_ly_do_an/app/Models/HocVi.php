<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HocVi extends Model
{
    use HasFactory;

    protected $table = 'hoc_vi';
    protected $primaryKey = 'ma_hoc_vi';
    public $timestamps = false;

    public function giangViens() {
        return $this->hasMany(GiangVien::class, 'ma_hoc_vi', 'ma_hoc_vi');
    }
}
