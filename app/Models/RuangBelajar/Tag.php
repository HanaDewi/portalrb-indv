<?php

namespace App\Models\RuangBelajar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'bljr_tags';

    public function article()
    {
        return $this->belongsToMany(Article::class, 'bljr_article_tags');
    }
}
