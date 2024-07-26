<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitZI extends Model
{
    use HasFactory;
    protected $table = 'unit_zi';
    protected $guarded = [
        'id'
    ];

    public function instansiZI()
    {
        return $this->belongsTo(InstansiZI::class, 'instansi_zi_id');
    }
}
