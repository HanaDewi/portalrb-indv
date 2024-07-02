<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstansiZI extends Model
{
    use HasFactory;
    public function klpd_instansi(): BelongsTo
    {
        return $this->belongsTo(KlpdInstansi::class, "lke_instansi_id");
    }
}
