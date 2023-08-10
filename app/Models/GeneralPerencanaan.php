<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralPerencanaan extends Model
{
    use HasFactory;
    protected $table = 'general_perencanaan';

    public function target()
    {
        return $this->hasMany(GeneralPerencanaanTarget::class, 'general_perencanaan_id')->orderBy('tahun');
    }

    public function kegiatan_utama()
    {
        return $this->belongsTo(KegiatanUtama::class, 'kegiatan_utama_id');
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'kegiatan_utama_id');
    }
}
