<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'cms_menu_items';

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'type',
        'reference_id',
        'url',
        'target',
        'ordering',
        'status',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('status', 1)
            ->orderBy('ordering')
            ->orderBy('id');
    }

    public function getResolvedUrlAttribute(): string
    {
        if ($this->type === 'page' && $this->reference_id) {
            $page = Page::find($this->reference_id);
            return $page ? \ExtensionsValley\Cms\Helpers\CmsHelper::url('page/' . $page->slug, $page->language_code) : '#';
        }

        if ($this->type === 'post' && $this->reference_id) {
            $post = Post::find($this->reference_id);
            return $post ? \ExtensionsValley\Cms\Helpers\CmsHelper::url('post/' . $post->slug, $post->language_code) : '#';
        }

        if ($this->type === 'category' && $this->reference_id) {
            $category = Category::find($this->reference_id);
            return $category ? \ExtensionsValley\Cms\Helpers\CmsHelper::url('category/' . $category->slug, $category->language_code) : '#';
        }

        if ($this->url && str_starts_with($this->url, '/cms')) {
            return \ExtensionsValley\Cms\Helpers\CmsHelper::url(ltrim($this->url, '/'));
        }

        if ($this->url && str_starts_with($this->url, '/') && !str_starts_with($this->url, '//')) {
            return url($this->url);
        }

        return \ExtensionsValley\Cms\Helpers\CmsHelper::sanitizeUrl($this->url);
    }
}

