<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TimEvaluasi extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'tim_evaluasi';
    protected $guarded = [
        'id'
    ];
}
