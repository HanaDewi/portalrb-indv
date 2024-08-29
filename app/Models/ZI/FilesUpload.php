<?php

namespace App\Models\ZI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FilesUpload extends Model
{
    use HasFactory;
    protected $connection = 'zi_db';
    protected $table = 'file_upload';
    protected $guarded = [
        'id'
    ];
}
