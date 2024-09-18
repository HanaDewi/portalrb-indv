<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UnitZI extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'unit_zi';
    protected $guarded = [
        'id'
    ];

    public function instansiZI()
    {
        return $this->belongsTo(InstansiZI::class, 'instansi_zi_id');
    }

    public function unit_tim() {
        return $this->hasMany(UnitTimEvaluasi::class, 'unit_id');
    }

    public function seleksi_administrasi_unit() {
        return $this->hasOne(SeleksiAdministrasiUnit::class, 'unit_zi_id');
    }

    public function sanggah_unit() {
        return $this->hasOne(SanggahUnit::class, 'unit_zi_id');
    }

    public function analisis_dokumen() {
        return $this->hasOne(AnalisisDokumen::class, 'unit_zi_id');
    }

    public function wawancara() {
        return $this->hasOne(Wawancara::class, 'unit_zi_id');
    }

    public function verifikasi_lapangan() {
        return $this->hasOne(VerifikasiLapangan::class, 'unit_zi_id');
    }

    public function warlap() {
        return $this->hasOne(Warlap::class, 'unit_zi_id');
    }
}

