<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $table = 'cms_menus';

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'language_code',
        'name',
        'slug',
        'location',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->orderBy('ordering')->orderBy('id');
    }

    public function rootItems()
    {
        return $this->hasMany(MenuItem::class, 'menu_id')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('ordering')
            ->orderBy('id');
    }

    public static function getRlationstatus($cid)
    {
        return 0;
    }

    public static function getCommonActionHandler($actionType, $cid)
    {
        if ($actionType === 'forcedelete') {
            MenuItem::whereIn('menu_id', $cid)->delete();
        }
    }
}

