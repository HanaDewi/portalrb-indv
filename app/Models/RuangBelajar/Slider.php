<?php

namespace App\Models\RuangBelajar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $table = 'bljr_sliders';
    protected $fillable = [
        'id',
        'home_top_bar_slider',
        'home_top_bar_slider_status',
    ];
}
