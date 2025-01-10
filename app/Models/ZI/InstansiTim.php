<?php

namespace App\Models\ZI;

use App\Models\KlpdInstansi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InstansiTim extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
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
        return $this->belongsTo(TimEvaluasi::class, "tim_id");
    }
    

}
