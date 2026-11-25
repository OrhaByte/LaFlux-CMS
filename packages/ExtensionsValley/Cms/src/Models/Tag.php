<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $table = 'cms_tags';

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'language_code',
        'name',
        'slug',
        'status',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'cms_post_tag', 'tag_id', 'post_id');
    }

    public static function getTags()
    {
        return self::whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id');
    }

    public static function getRlationstatus($cid)
    {
        return 0;
    }
}

