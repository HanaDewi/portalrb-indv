<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PelaporanCoi extends Model
{
    use LogsActivity, HasFactory;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'instansi_id',
                'q1_peraturan_internal',
                'q11_nomor_peraturan',
                'q2_selaras_permepan',
                'q21_susun_revisi',
                'q211_rencana_penyesuaian',
                'q3_pedoman_teknis',
                'q31_nomor_pedoman',
                'q4_penunjukan_pejabat',
                'q5_sistem_aplikasi',
                'q51_url_sistem',
                'q6_pencatatan_register',
                'q61_total_wajib',
                'q62_total_lapor',
                'q7_deklarasi_aktual',
                'q71_jumlah_deklarasi',
                'q8_lini_aduan',
                'q81_nama_lini',
                'q9_monev',
                'q10_laporan',
                'finalized_at'
            ]);
    }
}
