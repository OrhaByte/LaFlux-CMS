<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsForm extends Model
{
    use SoftDeletes;

    protected $table = 'cms_forms';

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'slug',
        'submit_label',
        'success_message',
        'notify_email',
        'status',
    ];

    public function fields()
    {
        return $this->hasMany(CmsFormField::class, 'form_id')->orderBy('ordering')->orderBy('id');
    }

    public function submissions()
    {
        return $this->hasMany(CmsFormSubmission::class, 'form_id')->orderByDesc('id');
    }

    public static function getRlationstatus($cid)
    {
        return 0;
    }

    public static function getCommonActionHandler($actionType, $cid)
    {
        if ($actionType === 'forcedelete') {
            CmsFormField::whereIn('form_id', $cid)->delete();
            CmsFormSubmission::whereIn('form_id', $cid)->delete();
        }
    }
}

