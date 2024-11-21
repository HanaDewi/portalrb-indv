<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UnggahFile extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'upload_file';
    protected $guarded = [
        'id'
    ];
}
