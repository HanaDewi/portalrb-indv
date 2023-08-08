<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanUtama extends Model
{
    use HasFactory;
    protected $table = 'kegiatan_utama';

    public function indikators()
    {
        return $this->hasMany(Indikator::class, 'kegiatan_utama_id');
    }
}
