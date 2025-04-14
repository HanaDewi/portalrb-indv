<?php

namespace App\Models;

use App\Models\LKE\LkeTestTp;
use App\Models\ZI\InstansiZI;
use App\Models\LKE\LkeTestTpLine;
use App\Models\MappingKodeInstansi;
use Illuminate\Database\Eloquent\Model;
use App\Models\LkeTestTp as LkeTestTpOld;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class KlpdInstansi extends Model
{
    use HasFactory, SoftDeletes;
    protected $connection = 'mysql';
    protected $table = 'klpd_instansi_new';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];

    public function lke_test_tp_old(): HasOne
    {
        return $this->hasOne(LkeTestTpOld::class,  "lke_instansi_id");
    }

    public function mapping_kode_instansi(): HasOne
    {
        return $this->hasOne(MappingKodeInstansi::class,  "rb_klpd_code");
    }

    public function instansi_zi()
    {
        return $this->hasMany(InstansiZI::class,  "instansi_id");
    }

    public function lke_test_tps()
    {
        return $this->hasMany(LkeTestTp::class, 'instansi_id');
    }

    public function lke_test_tp_lines()
    {
        return $this->hasMany(LkeTestTpLine::class, 'instansi_id');
    }

    public function jawaban_renaksi()
    {
        return $this->hasMany(JawabanRenaksi::class, 'instansi_id');
    }

    public function tematik_sasaran_roadmap()
    {
        return $this->hasMany(TematikSasaranRoadmap::class, 'instansi_id');
    }

    public function getNamaInstansiAttribute()
    {
        return $this->name_before ? $this->name . ' [<span class="font-italic text-danger">' . $this->name_before . '</span>]' : $this->name;
    }
}
