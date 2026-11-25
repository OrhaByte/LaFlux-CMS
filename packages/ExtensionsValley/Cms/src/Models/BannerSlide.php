<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class BannerSlide extends Model
{
    protected $table = 'cms_banner_slides';

    protected $fillable = [
        'banner_id',
        'title',
        'description',
        'image',
        'button_text',
        'button_link',
        'ordering',
        'status',
    ];

    public function banner()
    {
        return $this->belongsTo(Banner::class, 'banner_id');
    }
}

