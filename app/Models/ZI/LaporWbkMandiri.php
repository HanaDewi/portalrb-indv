<?php

namespace App\Models\ZI;

use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporWbkMandiri extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'lapor_wbk_mandiri';
    protected $guarded = [
        'id'
    ];
    public function tahun_evaluasi(): BelongsTo
    {
        return $this->belongsTo(TahunEvaluasi::class, 'tahun');
    }

    public function tahap_seleksi(): BelongsTo
    {
        return $this->belongsTo(TahapSeleksiZI::class, 'tahap_seleksi_id');
    }

    public function instansi_ZI(): BelongsTo
    {
        return $this->belongsTo(InstansiZI::class, 'instansi_zi_id');
    }
}
