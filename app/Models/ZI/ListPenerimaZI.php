<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ListPenerimaZI extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'list_penerima_zi';
    protected $guarded = [
        'id'
    ];
}
