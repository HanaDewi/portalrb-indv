<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPertanyaanCoi extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'master_pertanyaan_coi';

    // Kolom yang bisa diisi
    protected $fillable = [
        'teks_pertanyaan',
        'tipe_jawaban',
        'opsi',
        'is_wajib',
        'urutan'
    ];

    // Casting JSON opsi jadi Array otomatis
    protected $casts = [
        'opsi' => 'array',
        'is_wajib' => 'boolean',
    ];
}