<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GeneralRencanaAksiOutput extends Model
{
    use LogsActivity, HasFactory;
    protected $table = 'general_rencana_aksi_output';

    public function rencana_aksi()
    {
        return $this->belongsTo(GeneralRencanaAksi::class, 'general_rencana_aksi_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'general_rencana_aksi_id', 'satuan_output', 'indikator_output', 'target_tw1', 'target_tw2', 'target_tw3', 'target_tw4', 'target_total', 'anggaran_tw1', 'anggaran_tw2', 'anggaran_tw3', 'anggaran_tw4', 'anggaran_total', 'pelaksana', 'koordinator', 'realisasi_output_tw1', 'realisasi_output_tw2', 'realisasi_output_tw3', 'realisasi_output_tw4', 'realisasi_output_total', 'realisasi_anggaran_tw1', 'realisasi_anggaran_tw2', 'realisasi_anggaran_tw3', 'realisasi_anggaran_tw4', 'realisasi_anggaran_total', 'capaian_output_tw1', 'capaian_output_tw2', 'capaian_output_tw3', 'capaian_output_tw4', 'capaian_output_total', 'capaian_anggaran_tw1', 'capaian_anggaran_tw2', 'capaian_anggaran_tw3', 'capaian_anggaran_tw4', 'capaian_anggaran_total']);
    }
}
