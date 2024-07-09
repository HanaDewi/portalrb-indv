<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class InstansiZI extends Model
{
    use HasFactory;
    protected $table = 'instansi_zi';
    protected $guarded = [
        'id'
    ];
    public function klpd_instansi(): BelongsTo
    {
        return $this->belongsTo(KlpdInstansi::class, 'instansi_id');
    }

    public function unit_zi() {
        return $this->hasMany(UnitZI::class, 'instansi_zi_id')->orderBy('nama');
    }
}
