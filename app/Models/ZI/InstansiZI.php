<?php

namespace App\Models\ZI;

use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstansiZI extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
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

    public function administrasi_instansi() {
        return $this->hasOne(SeleksiAdministrasiInstansi::class, 'instansi_zi_id');
    }

    public function sanggah_instansi() {
        return $this->hasOne(SanggahInstansi::class, 'instansi_zi_id');
    }


}
