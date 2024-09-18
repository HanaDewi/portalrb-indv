<?php

namespace App\Models\ZI;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SanggahInstansi extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $connection = 'zi_db';
    protected $table = 'sanggah_instansi';
    protected $guarded = [
        'id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded('*');
    }
    
    public function instansi_ZI(): BelongsTo
    {
        return $this->belongsTo(InstansiZI::class, 'instansi_zi_id');
    }
}
