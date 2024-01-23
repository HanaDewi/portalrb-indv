<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LkeTestTp extends Model
{
    use HasFactory;
    protected $table = 'lke_test_tp';

    public function klpd_instansi(): BelongsTo
    {
        return $this->belongsTo(KlpdInstansi::class, "lke_instansi_id");
    }

    public function lke_test_tp_line()
    {
        return $this->hasMany(LkeTestTpLine::class, 'test_tp_id');
    }

    public function lke_kegiatan(): BelongsTo
    {
        return $this->belongsTo(LkeKegiatan::class, "kegiatan_id");
    }
}
