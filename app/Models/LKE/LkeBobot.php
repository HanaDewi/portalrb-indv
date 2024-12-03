<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkeBobot extends Model
{
    use HasFactory;
    protected $table = 'lke_bobot';

    public function lke_test_tp_line()
    {
        return $this->hasOne(LkeTestTpLine::class, 'lke_bobot_id');
    }

    public function lke_parameter()
    {
        return $this->belongsTo(LkeParameter::class, 'lke_parameter_id');
    }
}
