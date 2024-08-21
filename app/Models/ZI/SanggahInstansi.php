<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SanggahInstansi extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'sanggah_instansi';
    protected $guarded = [
        'id'
    ];

    public function instansi_ZI(): BelongsTo
    {
        return $this->belongsTo(InstansiZI::class, 'instansi_zi_id');
    }
}
