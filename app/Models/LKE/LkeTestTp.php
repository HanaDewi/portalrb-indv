<?php

namespace App\Models\LKE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LkeTestTp extends Model
{
    use LogsActivity, HasFactory;
    protected $table = 'lke_test_tp';

    public function files()
    {
        return $this->hasMany(LkeTestTpFile::class, "test_tp_id");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'lke_kegiatan_id', 'instansi_id', 'rb_general', 'rb_tematik', 'index_rb', 'bobot_rb_general_penyesuaian', 'rb_general_penyesuaian', 'koefisien']);
    }
}
