<?php

namespace ExtensionsValley\Cms\Validators;

class TagValidation
{
    public function getRules(): array
    {
        return [
            'name' => 'required|max:150',
            'language_code' => 'nullable|alpha|size:2',
            'slug' => 'nullable|max:180',
            'status' => 'required|in:0,1',
        ];
    }

    public function getUpdateRules($tag): array
    {
        return [
            'name' => 'required|max:150',
            'language_code' => 'nullable|alpha|size:2',
            'slug' => 'nullable|max:180',
            'status' => 'required|in:0,1',
        ];
    }
}

