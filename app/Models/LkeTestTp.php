<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LkeTestTp extends Model
{
    use LogsActivity,
        HasFactory;
    protected $table = 'lke_test_tp';
    public $timestamps = false;

    public function klpd_instansi(): BelongsTo
    {
        return $this->belongsTo(KlpdInstansi::class, "lke_instansi_id");
    }

    public function lke_test_tp_line()
    {
        return $this->hasMany(LkeTestTpLine::class, 'test_tp_id');
    }

    public function lke_kegiatan(): BelongsTo
    {
        return $this->belongsTo(LkeKegiatan::class, "kegiatan_id");
    }

    public function files()
    {
        return $this->hasMany(LkeTestTpFile::class, "test_tp_id");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'kegiatan_id', 'lke_instansi_id', 'rb_general', 'rb_tematik', 'bobot_rb_general_penyesuaian', 'rb_general_penyesuaian', 'index_rb_penyesuaian']);
        // Chain fluent methods for configuration options
    }
}
