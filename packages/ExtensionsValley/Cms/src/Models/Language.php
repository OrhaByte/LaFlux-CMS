<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Language extends Model
{
    use SoftDeletes;

    protected $table = 'cms_languages';

    protected $casts = [
        'is_default' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'code',
        'direction',
        'is_default',
        'status',
    ];

    public static function getLanguages()
    {
        return self::whereNull('deleted_at')
            ->where('status', 1)
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->pluck('name', 'code');
    }

    public static function getRlationstatus($cid)
    {
        // Prevent disabling/trashing the default language
        $hasDefault = DB::table('cms_languages')
            ->whereIn('id', $cid)
            ->where('is_default', 1)
            ->exists();

        return $hasDefault ? 1 : 0;
    }
}

