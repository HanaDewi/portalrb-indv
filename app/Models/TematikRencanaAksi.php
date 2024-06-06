<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TematikRencanaAksi extends Model
{
    use HasFactory;
    protected $table = 'tematik_rencana_aksi';
    public function output($fokus_intervensi_ids=[])
    {
        $select = $this->hasMany(TematikRencanaAksiOutput::class, 'tematik_rencana_aksi_id');
        if (count($fokus_intervensi_ids)>0) {
            $select->whereIn('fokus_intervensi', $fokus_intervensi_ids);
        }
        return $select;
    }

    public function indikator()
    {
        return $this->belongsTo(TematikIndikatorPermasalahan::class, 'tematik_indikator_permasalahan_id');
    }
}
