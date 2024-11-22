<?php

namespace App\Models\ZI;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class HasilFinal extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $connection = 'zi_db';
    protected $table = 'final';
    protected $guarded = [
        'id'
    ];

    

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logUnguarded('*');
    }
    public function unitZI()
    {
        return $this->belongsTo(UnitZI::class, 'unit_zi_id');
    }
}
