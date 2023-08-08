<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;
    protected $table = 'indikator';

    public function kegiatan_utama()
    {
        return $this->belongsTo(KegiatanUtama::class, 'kegiatan_utama_id');
    }
}
