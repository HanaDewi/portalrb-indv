<?php

namespace App\Models\Akip;

use App\Models\KlpdInstansi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluasiSakip extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'evaluasi_sakip';

    public function instansi()
    {
        return $this->belongsTo(KlpdInstansi::class, 'instansi_id');
    }
}
