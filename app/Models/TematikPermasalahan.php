<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TematikPermasalahan extends Model
{
    use HasFactory;
    protected $table = 'tematik_permasalahan';

    public function indikator_permasalahan()
    {
        return $this->hasMany(TematikIndikatorPermasalahan::class, 'tematik_permasalahan_id')->orderBy('tematik_permasalahan_id');
    }
}
