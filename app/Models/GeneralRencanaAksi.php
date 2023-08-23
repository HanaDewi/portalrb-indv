<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralRencanaAksi extends Model
{
    use HasFactory;
    protected $table = 'general_rencana_aksi';

    public function target()
    {
        return $this->belongsTo(GeneralPerencanaanTarget::class, 'general_perencanaan_target_id');
    }

    public function output()
    {
        return $this->hasMany(GeneralRencanaAksiOutput::class, 'general_rencana_aksi_id');
    }
}
