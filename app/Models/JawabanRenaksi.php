<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanRenaksi extends Model
{
    use HasFactory;
    protected $table = 'jawaban_renaksi';

    public function lke()
    {
        return $this->belongsTo(LKERenaksi::class, "lke_renaksi_id");
    }

    public function konversi_jawaban_renaksi()
    {
        return $this->belongsTo(KonversiJawabanRenaksi::class, "jawaban", "jawaban");
    }
}
