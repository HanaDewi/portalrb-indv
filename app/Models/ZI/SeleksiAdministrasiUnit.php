<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SeleksiAdministrasiUnit extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'seleksi_administrasi_unit';
    protected $guarded = [
        'id'
    ];

    public function unitZI(): BelongsTo
    {
        return $this->belongsTo(UnitZI::class, 'unit_zi_id');
    }
}
