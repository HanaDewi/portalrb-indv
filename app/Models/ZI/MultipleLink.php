<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MultipleLink extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'multiple_link';
    protected $guarded = [
        'id'
    ];
}
