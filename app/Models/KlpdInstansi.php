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

    /**
     * ACCESSOR: Mengubah tampilan 'group' secara dinamis tanpa ubah database.
     * Ini akan memecah:
     * - 'kabupaten' menjadi 'kota' atau 'kabupaten'
     * - 'kl' menjadi 'kementerian' atau 'lembaga'
     */
    public function getGroupAttribute($value)
    {
        // Jika aslinya 'kabupaten', cek apakah ada kata 'Kota' di namanya
        if ($value == 'kabupaten') {
            return str_contains($this->name, 'Kota') ? 'kota' : 'kabupaten';
        }
        
        // Jika aslinya 'kl', cek apakah ada kata 'Kementerian' di namanya
        if ($value == 'kl') {
            return str_contains($this->name, 'Kementerian') ? 'kementerian' : 'lembaga';
        }

        // Untuk group 'provinsi', 'lain', dll biarkan apa adanya
        return $value;
    }

    public function lke_test_tp_old(): HasOne
    {
        if ($this->id_before) {
            return $this->hasOne(LkeTestTpOld::class, "lke_instansi_id", "id_before");
        } else {
            return $this->hasOne(LkeTestTpOld::class, "lke_instansi_id");
        }
    }

    public function mapping_kode_instansi(): HasOne
    {
        return $this->hasOne(MappingKodeInstansi::class, "rb_klpd_code");
    }

    public function instansi_zi()
    {
        return $this->hasMany(InstansiZI::class, "instansi_id");
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
        return $this->name_before ? $this->name . ' [<span class="italic text-red-500">' . $this->name_before . '</span>]' : $this->name;
    }

    public function idBeforeUsed()
    {
        return $this->hasMany(KlpdInstansi::class, 'id_before', 'id');
    }

    public function instansi_tim()
    {
        return $this->hasMany(\App\Models\InstansiTimEvaluasi::class, 'instansi_id');
    }

    public function penghargaan()
    {
        return $this->hasMany(\App\Models\Akip\Penghargaan::class, 'instansi_id');
    }
}