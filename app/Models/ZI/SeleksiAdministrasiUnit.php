<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SeleksiAdministrasiUnit extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'seleksi_administrasi_unit';
    protected $guarded = [
        'id'
    ];
}
