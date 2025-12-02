<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelaporanCoi extends Model
{
    use HasFactory;

    protected $table = 'pelaporan_coi';

    protected $guarded = ['id'];

    protected $casts = [
        'q1_peraturan_internal' => 'boolean',
        'q2_selaras_permepan' => 'boolean',
        'q21_susun_revisi' => 'boolean',
        'q3_pedoman_teknis' => 'boolean',
        'q4_penunjukan_pejabat' => 'boolean',
        'q5_sistem_aplikasi' => 'boolean',
        'q6_pencatatan_register' => 'boolean',
        'q7_deklarasi_aktual' => 'boolean',
        'q8_lini_aduan' => 'boolean',
        'q9_monev' => 'boolean',
        'q10_laporan' => 'boolean',
        'finalized_at' => 'datetime',
    ];

    public function instansi()
    {
        return $this->belongsTo(KlpdInstansi::class, 'instansi_id');
    }
}
