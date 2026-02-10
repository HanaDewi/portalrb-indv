d<?php

namespace App\Models\Akip;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\KlpdInstansi;

class Penghargaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penghargaan';

    protected $fillable = [
        'instansi_id',
        'tahun',
        'file_sertifikat',
    ];

    /**
     * Get the instansi that owns the penghargaan.
     */
    public function instansi()
    {
        return $this->belongsTo(KlpdInstansi::class, 'instansi_id', 'id');
    }
}