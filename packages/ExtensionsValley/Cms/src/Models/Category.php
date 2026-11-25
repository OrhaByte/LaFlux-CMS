<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'cms_categories';

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'language_code',
        'name',
        'slug',
        'parent_id',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'status',
        'created_by',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public static function getCategories($excludeId = null)
    {
        $query = self::whereNull('deleted_at')->where('status', 1);
        if ($excludeId) {
            $query->where('id', '<>', $excludeId);
        }

        return $query->orderBy('name')->pluck('name', 'id');
    }

    public static function getRlationstatus($cid)
    {
        $postCount = DB::table('cms_posts')
            ->whereNull('deleted_at')
            ->whereIn('category_id', $cid)
            ->count();

        $childCount = DB::table('cms_categories')
            ->whereNull('deleted_at')
            ->whereIn('parent_id', $cid)
            ->count();

        return ($postCount > 0 || $childCount > 0) ? 1 : 0;
    }
}

