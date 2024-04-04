<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralPerencanaanTarget extends Model
{
    use HasFactory;
    protected $table = 'general_perencanaan_target';

    public function perencanaan()
    {
        return $this->belongsTo(GeneralPerencanaan::class, 'general_perencanaan_id');
    }

    public function rencana_aksi()
    {
        return $this->hasMany(GeneralRencanaAksi::class, 'general_perencanaan_target_id');
    }

    public function dokumens()
    {
        return $this->hasMany(GeneralPerencanaanTargetDokumen::class, 'general_perencanaan_target_id');
    }
}
