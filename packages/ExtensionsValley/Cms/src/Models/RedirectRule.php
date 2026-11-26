<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RedirectRule extends Model
{
    use SoftDeletes;

    protected $table = 'cms_redirects';

    protected $casts = [
        'status_code' => 'integer',
        'hits' => 'integer',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'source_url',
        'target_url',
        'status_code',
        'hits',
        'status',
    ];

    public static function normalizeSourcePath(string $url): string
    {
        $parsed = parse_url(trim($url), PHP_URL_PATH);
        $path = '/' . ltrim((string) ($parsed ?: trim($url)), '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public static function getRlationstatus($cid)
    {
        return 0;
    }
}

