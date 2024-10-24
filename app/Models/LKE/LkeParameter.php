<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkeParameter extends Model
{
    use HasFactory;
    protected $table = 'lke_parameter';

    public function parent()
    {
        return $this->belongsTo(LkeParameter::class, 'parent_id');
    }

    public function bobots()
    {
        return $this->hasMany(LkeBobot::class, 'lke_parameter_id');
    }
}
