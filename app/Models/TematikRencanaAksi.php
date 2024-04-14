<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TematikRencanaAksi extends Model
{
    use HasFactory;
    protected $table = 'tematik_rencana_aksi';
    public function output()
    {
        return $this->hasMany(TematikRencanaAksiOutput::class, 'tematik_rencana_aksi_id');
    }
}
