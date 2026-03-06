<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class TematikRekapCapaianOutput extends Model
{
    use LogsActivity, HasFactory;
    protected $table = 'tematik_rekap_capaian_output';
    protected $guarded = [
        'id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'instansi_id',
                'tema_id',
                'capaian_output_tw1',
                'capaian_output_tw2',
                'capaian_output_tw3',
                'capaian_output_tw4',
                'capaian_output_total',
                'pengisian_tw1',
                'pengisian_tw2',
                'pengisian_tw3',
                'pengisian_tw4'
            ]);
    }
}
