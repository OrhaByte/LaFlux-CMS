<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;

    protected $table = 'cms_banners';

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    public function slides()
    {
        return $this->hasMany(BannerSlide::class, 'banner_id')->orderBy('ordering')->orderBy('id');
    }

    public function activeSlides()
    {
        return $this->hasMany(BannerSlide::class, 'banner_id')
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
            BannerSlide::whereIn('banner_id', $cid)->delete();
        }
    }
}

