<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TimEvaluasiRB extends Model
{
    use HasFactory;
    protected $table = 'tim_evaluasi';
    protected $guarded = [
        'id'
    ];

    public function instansi_tim()
    {
        return $this->hasMany(InstansiTimEvaluasi::class, 'tim_id')
            ->whereHas('instansi', function ($query) {
                $query->whereNull('deleted_at');
            });
    }
}
