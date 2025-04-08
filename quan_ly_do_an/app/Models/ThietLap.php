<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThietLap extends Model
{
    use HasFactory;

    protected $table = 'thiet_lap';
    protected $primaryKey = 'ma_thiet_lap';
    public $timestamps = false;
}