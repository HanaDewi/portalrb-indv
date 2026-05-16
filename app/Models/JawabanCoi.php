<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanCoi extends Model
{
    use HasFactory;

    protected $table = 'jawaban_coi';
    
    protected $fillable = [
        'instansi_id',
        'user_id',
        'master_pertanyaan_id',
        'nilai_jawaban'
    ];

    public function instansi()
    {
        return $this->belongsTo(KlpdInstansi::class, 'instansi_id');
    }
}