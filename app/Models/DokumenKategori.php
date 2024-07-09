<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DokumenKategori extends Model
{
    use LogsActivity, HasFactory;
    protected $table = 'dokumen_kategori';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'dokumen_id', 'file', 'deskripsi']);
    }
}
