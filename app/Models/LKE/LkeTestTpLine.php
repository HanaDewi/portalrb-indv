<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkeTestTpLine extends Model
{
    use HasFactory;
    protected $table = 'lke_test_tp_line';

    public function lke_bobot()
    {
        return $this->belongsTo(LkeBobot::class, 'lke_bobot_id');
    }
}
