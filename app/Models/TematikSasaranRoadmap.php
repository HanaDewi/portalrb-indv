<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TematikSasaranRoadmap extends Model
{
    use HasFactory;
    protected $table = 'tematik_sasaran_roadmap';

    public function tema()
    {
        return $this->belongsTo(Tema::class, 'kegiatan_utama_id');
    }
}
