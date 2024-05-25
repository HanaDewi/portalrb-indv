<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GeneralPerencanaanTarget extends Model
{
    use LogsActivity, HasFactory;
    protected $table = 'general_perencanaan_target';

    public function perencanaan()
    {
        return $this->belongsTo(GeneralPerencanaan::class, 'general_perencanaan_id');
    }

    public function rencana_aksi()
    {
        return $this->hasMany(GeneralRencanaAksi::class, 'general_perencanaan_target_id');
    }

    public function dokumens()
    {
        return $this->hasMany(GeneralPerencanaanTargetDokumen::class, 'general_perencanaan_target_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'general_perencanaan_id', 'tahun', 'target', 'realisasi_indikator', 'capaian_indikator', 'catatan', 'catatan_evaluator']);
    }
}
