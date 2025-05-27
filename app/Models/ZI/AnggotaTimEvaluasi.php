<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AnggotaTimEvaluasi extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
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
        return $this->belongsTo(TimEvaluasi::class, "tim_id");
    }
}
