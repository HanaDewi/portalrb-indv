<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SeleksiAdministrasiInstansi extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'seleksi_administrasi_instansi';
    protected $guarded = [
        'id'
    ];
}
