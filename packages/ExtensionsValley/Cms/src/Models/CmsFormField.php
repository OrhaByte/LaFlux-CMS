<?php

namespace ExtensionsValley\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class CmsFormField extends Model
{
    protected $table = 'cms_form_fields';

    protected $casts = [
        'is_required' => 'boolean',
    ];

    protected $fillable = [
        'form_id',
        'label',
        'field_name',
        'field_type',
        'placeholder',
        'options',
        'is_required',
        'ordering',
    ];

    public function form()
    {
        return $this->belongsTo(CmsForm::class, 'form_id');
    }

    public function getOptionsArrayAttribute(): array
    {
        if (empty($this->options)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->options))));
    }
}

