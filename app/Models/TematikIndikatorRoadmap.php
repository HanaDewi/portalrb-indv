<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TematikIndikatorRoadmap extends Model
{
    use HasFactory;
    protected $table = 'tematik_indikator_roadmap';

    public function permasalahan($ids=[])
    {
        $select = $this->hasMany(TematikPermasalahan::class, 'tematik_indikator_roadmap_id')->orderBy('tematik_indikator_roadmap_id');
        if (count($ids)>0) {
            $select->whereIn('id', $ids);
        }
        return $select;
    }

    public function sasaran_roadmap()
    {
        return $this->belongsTo(TematikSasaranRoadmap::class, 'tematik_sasaran_roadmap_id');
    }
}
