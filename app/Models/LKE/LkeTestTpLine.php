<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LkeTestTpLine extends Model
{
    use LogsActivity, HasFactory;
    protected $table = 'lke_test_tp_line';

    public function lke_bobot()
    {
        return $this->belongsTo(LkeBobot::class, 'lke_bobot_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'instansi_id', 'lke_bobot_id', 'penilai_user_id', 'score', 'score_index', 'capaian_index', 'catatan', 'rekomendasi', 'status_sanggah', 'keterangan_sanggah', 'file_sanggah', 'pengaju_sanggah', 'keterangan_tanggapan_sanggah']);
    }
}
