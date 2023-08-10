<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralPerencanaan extends Model
{
    use HasFactory;
    protected $table = 'general_perencanaan';

    public function target()
    {
        return $this->hasMany(GeneralPerencanaanTarget::class, 'general_perencanaan_id')->orderBy('tahun');
    }
}
