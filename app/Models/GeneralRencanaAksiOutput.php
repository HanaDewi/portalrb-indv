<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralRencanaAksiOutput extends Model
{
    use HasFactory;
    protected $table = 'general_rencana_aksi_output';

    public function rencana_aksi()
    {
        return $this->belongsTo(GeneralRencanaAksi::class, 'general_rencana_aksi_id');
    }
}
