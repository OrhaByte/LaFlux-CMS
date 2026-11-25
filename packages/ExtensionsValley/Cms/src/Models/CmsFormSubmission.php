<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class CmsFormSubmission extends Model
{
    protected $table = 'cms_form_submissions';

    protected $casts = [
        'payload' => 'array',
    ];

    protected $fillable = [
        'form_id',
        'payload',
        'ip_address',
        'user_agent',
    ];

    public function form()
    {
        return $this->belongsTo(CmsForm::class, 'form_id');
    }
}

