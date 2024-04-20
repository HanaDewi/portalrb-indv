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
        return $this->belongsTo(Tema::class, 'tema_id');
    }

    public function indikator_roadmap($ids=[])
    {
        $select = $this->hasMany(TematikIndikatorRoadmap::class, 'tematik_sasaran_roadmap_id');
        if (count($ids)>0) {
            $select->whereIn('id', $ids)->orderBy('tematik_sasaran_roadmap_id');
        }
        return $select;
    }

    public function permasalahan()
    {
        return $this->hasManyThrough(
            TematikPermasalahan::class,
            TematikIndikatorRoadmap::class,
            "tematik_sasaran_roadmap_id", //foreign key TematikIndikatorRoadmap untuk sasaran roadmap
            "tematik_indikator_roadmap_id", //foreign key TematikPermasalah untuk TematikIndikatorRoadmap
            "id", //primary key  sasaranroadmap
            "id" //primary key TematikIndikatorRoadmap
        );
    }

    public function indikatorForPermasalahanOnIndikatorRoadmap()
    {
        return TematikIndikatorPermasalahan::whereIn('id', function ($query) {
            $query->select('tematik_indikator_permasalahan.id')
                ->from('tematik_indikator_permasalahan')
                ->join('tematik_permasalahan', 'tematik_indikator_permasalahan.tematik_permasalahan_id', '=', 'tematik_permasalahan.id')
                ->join('tematik_indikator_roadmap', 'tematik_permasalahan.tematik_indikator_roadmap_id', '=', 'tematik_indikator_roadmap.id')
                ->where('tematik_indikator_roadmap.tematik_sasaran_roadmap_id', $this->id);
        })->get();
    }
}
