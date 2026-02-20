<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TematikPermasalahan extends Model
{
    use LogsActivity, HasFactory;
    protected $table = 'tematik_permasalahan';

    public function indikator_permasalahan()
    {
        return $this->hasMany(TematikIndikatorPermasalahan::class, 'tematik_permasalahan_id')->orderBy('tematik_permasalahan_id');
    }

    public function indikator_roadmap()
    {
        return $this->belongsTo(TematikIndikatorRoadmap::class, 'tematik_indikator_roadmap_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'tematik_indikator_roadmap_id',
                'nama',
                'sasaran_permasalahan'
            ]);
    }
}
