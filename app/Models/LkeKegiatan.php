<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LkeKegiatan extends Model
{
    use HasFactory;
    protected $table = 'old_lke_kegiatan';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];

    public function lke_test_tp()
    {
        return $this->hasMany(LkeTestTp::class, 'kegiatan_id');
    }
}
