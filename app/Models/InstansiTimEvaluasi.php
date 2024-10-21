<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InstansiTimEvaluasi extends Model
{
    use HasFactory;
    protected $table = 'instansi_tim';
    protected $guarded = [
        'id'
    ];


    public function instansi()
    {
        return $this->belongsTo(KlpdInstansi::class, "instansi_id");
    }

    public function tim()
    {
        return $this->belongsTo(TimEvaluasiRB::class, "tim_id");
    }
    

}
