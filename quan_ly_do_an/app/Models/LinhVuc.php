<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinhVuc extends Model
{
    use HasFactory;

    protected $table = 'linh_vuc';
    protected $primaryKey = 'ma_linh_vuc';
    public $timestamps = false;
}