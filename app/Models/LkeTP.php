<?php

namespace App\Models;

use App\Models\LkeTestTp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LkeTP extends Model
{
    use HasFactory;
    protected $table = 'lke_tp';
}
