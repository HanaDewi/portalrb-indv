<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TematikIndikatorRoadmap extends Model
{
    use LogsActivity, HasFactory;
    protected $table = 'tematik_indikator_roadmap';

    public function permasalahan($ids = [])
    {
        $select = $this->hasMany(TematikPermasalahan::class, 'tematik_indikator_roadmap_id')->orderBy('tematik_indikator_roadmap_id');
        if (count($ids) > 0) {
            $select->whereIn('id', $ids);
        }
        return $select;
    }

    public function sasaran_roadmap()
    {
        return $this->belongsTo(TematikSasaranRoadmap::class, 'tematik_sasaran_roadmap_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'tematik_sasaran_roadmap_id',
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
