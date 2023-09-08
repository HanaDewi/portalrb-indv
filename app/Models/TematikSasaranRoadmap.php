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
        return $this->belongsTo(Tema::class, 'id');
    }

    public function indikator_roadmap()
    {
        return $this->hasMany(TematikIndikatorRoadmap::class, 'general_perencanaan_id')->orderBy('tahun');
    }

    public function permasalahan()
    {
        return $this->hasManyThrough(Cek_syarat_unit::class, Unit::class, "id_instansi", "id_unit", "id", "id");
    }
}
