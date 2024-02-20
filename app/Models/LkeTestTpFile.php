<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LkeTestTpFile extends Model
{
    use LogsActivity,
        HasFactory;
    protected $table = 'lke_test_tp_files';

    public function test_tp()
    {
        return $this->belongsTo(LkeTestTp::class, "test_tp_id");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['test_tp_id', 'file', 'deskripsi']);
        // Chain fluent methods for configuration options
    }
}
