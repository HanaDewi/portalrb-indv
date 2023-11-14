<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;
    protected $table = 'tema';

    public function sasarans()
    {
        return $this->hasMany(TematikSasaranRoadmap::class, 'tema_id');
    }
}
