<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SanggahUnit extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'sanggah_unit';
    protected $guarded = [
        'id'
    ];
}
