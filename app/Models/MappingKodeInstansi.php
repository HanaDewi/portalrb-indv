<?php

namespace App\Models;

use App\Models\LKE\LkeTestTp;
use App\Models\LKE\LkeTestTpLine;
use App\Models\LkeTestTp as LkeTestTpOld;
use App\Models\ZI\InstansiZI;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MappingKodeInstansi extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'mapping_kode_instansi';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];

    
    public function klpd_instansi(): BelongsTo
    {
        return $this->belongsTo(KlpdInstansi::class,'id', 'rb_klpd_code');
    }

}
