<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TematikRencanaAksiOutput extends Model
{
    use HasFactory;
    protected $table = 'tematik_rencana_aksi_output';

    public function rencana_aksi()
    {
        return $this->belongsTo(TematikRencanaAksi::class, 'tematik_rencana_aksi_id');
    }

    public function get_intervensi()
    {
        return $this->belongsTo(FokusIntervensi::class, 'fokus_intervensi');
    }
}
