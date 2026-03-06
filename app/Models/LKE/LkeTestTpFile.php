<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LkeTestTpFile extends Model
{
    use LogsActivity, HasFactory;
    protected $table = 'lke_test_tp_files';

    public function lke_test_tp()
    {
        return $this->belongsTo(LkeTestTp::class, "test_tp_id");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'test_tp_id', 'file', 'deskripsi']);
    }
}
