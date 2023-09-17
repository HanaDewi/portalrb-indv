<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TematikIndikatorRoadmap extends Model
{
    use HasFactory;
    protected $table = 'tematik_indikator_roadmap';

    public function permasalahan()
    {
        return $this->hasMany(TematikPermasalahan::class, 'tematik_indikator_roadmap_id')->orderBy('tematik_indikator_roadmap_id');
    }
}
