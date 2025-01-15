<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TematikRekapCapaianOutput extends Model
{
    use HasFactory;
    protected $table = 'tematik_rekap_capaian_output';
    protected $guarded = [
        'id'
    ];
}
