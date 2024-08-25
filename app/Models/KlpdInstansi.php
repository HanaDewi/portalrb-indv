<?php

namespace App\Models;

use App\Models\ZI\InstansiZI;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KlpdInstansi extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'klpd_instansi';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];

    public function lke_test_tp(): HasOne
    {
        return $this->hasOne(LkeTestTp::class,  "lke_instansi_id");
    }

    public function instansi_zi()
    {
        return $this->hasMany(InstansiZI::class,  "instansi_id");
    }


}
