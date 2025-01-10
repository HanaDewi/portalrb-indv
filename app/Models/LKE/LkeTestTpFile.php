<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkeTestTpFile extends Model
{
    use HasFactory;
    protected $table = 'lke_test_tp_files';

    public function lke_test_tp()
    {
        return $this->belongsTo(LkeTestTp::class, "test_tp_id");
    }
}
