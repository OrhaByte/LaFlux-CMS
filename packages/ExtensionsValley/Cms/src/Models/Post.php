<?php

namespace ExtensionsValley\Cms\Models;

use ExtensionsValley\Dashboard\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'cms_posts';

    protected $casts = [
        'published_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'language_code',
        'title',
        'slug',
        'excerpt',
        'content',
        'category_id',
        'featured_image',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'status',
        'published_at',
        'author_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'cms_post_tag', 'post_id', 'tag_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 1)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public static function getPosts()
    {
        return self::whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('title')
            ->pluck('title', 'id');
    }

    public static function getRlationstatus($cid)
    {
        return 0;
    }
}

