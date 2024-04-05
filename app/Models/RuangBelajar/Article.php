<?php

namespace App\Models\RuangBelajar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Lang;

class Article extends Model
{
    use HasFactory;
    protected $table = 'bljr_articles';

    /** scope for active items */
    public function scopeActiveEntries($query)
    {
        return $query->where([
            'status' => 1,
            'is_approved' => 1
        ]);
    }

    /** scope for check language */
    public function scopeWithLocalize($query)
    {
        return $query->where([
            'language' => Lang::locale()
        ]);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'bljr_news_tags');
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
