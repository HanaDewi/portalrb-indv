<?php

namespace App\Models;

use App\Models\User;
use App\Models\TimEvaluasiRB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnggotaTimEvaluasiRB extends Model
{
    use HasFactory;
    protected $table = 'anggota_tim_evaluasi';
    protected $guarded = [
        'id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function tim()
    {
        return $this->belongsTo(TimEvaluasiRB::class, "tim_id");
    }
    

}
