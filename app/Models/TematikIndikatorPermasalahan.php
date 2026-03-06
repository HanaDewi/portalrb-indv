<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TematikIndikatorPermasalahan extends Model
{
    use LogsActivity, HasFactory;
    protected $table = 'tematik_indikator_permasalahan';

    public function permasalahan()
    {
        return $this->belongsTo(TematikPermasalahan::class, 'tematik_permasalahan_id');
    }

    public function rencana_aksi()
    {
        return $this->hasMany(TematikRencanaAksi::class, 'tematik_indikator_permasalahan_id')->orderBy('tematik_indikator_permasalahan_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'tematik_permasalahan_id',
                'nama',
                'target',
                'satuan',
                'realisasi_indikator',
                'capaian_indikator',
                'catatan',
                'catatan_evaluator'
            ]);
    }
}
