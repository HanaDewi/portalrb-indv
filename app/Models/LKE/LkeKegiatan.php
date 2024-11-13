<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkeKegiatan extends Model
{
    use HasFactory;
    protected $table = 'lke_kegiatan';

    public function getNamaTahunAttribute()
    {
        return '['.$this->tahun.'] '.$this->nama;
    }

    public function parameters()
    {
        return $this->hasMany(LkeParameter::class, 'lke_kegiatan_id');
    }
}
