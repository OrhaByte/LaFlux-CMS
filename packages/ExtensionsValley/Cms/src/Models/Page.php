<?php

namespace ExtensionsValley\Cms\Models;

use ExtensionsValley\Dashboard\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $table = 'cms_pages';

    protected $casts = [
        'published_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'language_code',
        'title',
        'slug',
        'content',
        'featured_image',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'ordering',
        'status',
        'published_at',
        'author_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function versions()
    {
        return $this->hasMany(ContentVersion::class, 'content_id')
            ->where('content_type', 'page')
            ->orderByDesc('version_number');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 1)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public static function getPages()
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

