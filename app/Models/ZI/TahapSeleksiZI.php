<?php

namespace App\Models\ZI;

use App\Models\ZI\UnitZI;
use App\Models\KlpdInstansi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahapSeleksiZI extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'tahap_seleksi';
    protected $guarded = [
        'id'
    ];
    public function tahun_evaluasi(): BelongsTo
    {
        return $this->belongsTo(TahunEvaluasi::class, 'tahun');
    }
}
