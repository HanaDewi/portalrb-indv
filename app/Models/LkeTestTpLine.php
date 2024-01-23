<?php

namespace App\Models;

use App\Models\LkeTestTp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LkeTestTpLine extends Model
{
    use HasFactory;
    protected $table = 'lke_test_tp_line';

    public function lke_test_tp(): BelongsTo
    {
        return $this->belongsTo(LkeTestTp::class, "test_tp_id");
    }

    public function lke_kegiatan(): BelongsTo
    {
        return $this->belongsTo(LkeKegiatan::class, "kegiatan_id");
    }

    public function paramL1(): BelongsTo
    {
        return $this->belongsTo(LkeParameter::class, "lke_param_l1");
    }

    public function paramL4(): BelongsTo
    {
        return $this->belongsTo(LkeParameter::class, "lke_param_l4");
    }

    public function paramL0(): BelongsTo
    {
        return $this->belongsTo(LkeParameter::class, "lke_param_l0");
    }

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(LkeTestQuestion::class, "test_line");
    }

    public function tim_penilai(): BelongsTo
    {
        return $this->belongsTo(LkeTP::class, "penilai_id");
    }
}
