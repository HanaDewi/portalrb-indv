<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkeTestTp extends Model
{
    use HasFactory;
    protected $table = 'lke_test_tp';

    public function files()
    {
        return $this->hasMany(LkeTestTpFile::class, "test_tp_id");
    }
}
