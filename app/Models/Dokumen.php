<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;
    protected $table = 'dokumen';

    public function kategori()
    {
        return $this->belongsTo(DokumenKategori::class, 'kategori_id');
    }

    public function files()
    {
        return $this->hasMany(DokumenFile::class, 'dokumen_id');
    }

    public function instansi()
    {
        return $this->belongsTo(KlpdInstansi::class, 'instansi_id');
    }
}
