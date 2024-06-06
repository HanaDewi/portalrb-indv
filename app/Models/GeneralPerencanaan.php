<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GeneralPerencanaan extends Model
{
    use LogsActivity, HasFactory;
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
        return $this->belongsTo(Indikator::class, 'indikator_id');
    }

    public function instansi()
    {
        return $this->belongsTo(KlpdInstansi::class, 'instansi_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'instansi_id', 'kegiatan_utama_id', 'indikator_id', 'baseline_tahun', 'baseline_target', 'baseline_realisasi']);
    }
}
