<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    protected $table = 'cms_theme_settings';

    protected $casts = [
        'settings' => 'array',
    ];

    protected $fillable = [
        'theme_slug',
        'language_code',
        'section_key',
        'settings',
    ];
}

