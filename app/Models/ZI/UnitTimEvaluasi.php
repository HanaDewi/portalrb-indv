<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UnitTimEvaluasi extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'unit_tim_evaluasi';
    protected $guarded = [
        'id'
    ];


    public function unit()
    {
        return $this->belongsTo(UnitZI::class, "unit_id");
    }

    public function tim()
    {
        return $this->belongsTo(TimEvaluasi::class, "tim_id");
    }
    

}
