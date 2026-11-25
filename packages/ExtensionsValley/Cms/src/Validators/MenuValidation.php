<?php

namespace ExtensionsValley\Cms\Validators;

class MenuValidation
{
    public function getRules(): array
    {
        return [
            'name' => 'required|max:200',
            'language_code' => 'nullable|alpha|size:2',
            'slug' => 'nullable|max:200',
            'location' => 'required|max:100',
            'status' => 'required|in:0,1',
        ];
    }

    public function getUpdateRules($menu): array
    {
        return [
            'name' => 'required|max:200',
            'language_code' => 'nullable|alpha|size:2',
            'slug' => 'nullable|max:200',
            'location' => 'required|max:100',
            'status' => 'required|in:0,1',
        ];
    }
}

