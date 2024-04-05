<?php

namespace App\Models\RuangBelajar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'bljr_tags';

    public function news()
    {
        return $this->belongsToMany(Article::class, 'news_tags');
    }
}
