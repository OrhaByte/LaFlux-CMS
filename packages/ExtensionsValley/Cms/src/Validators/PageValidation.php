<?php

namespace ExtensionsValley\Cms\Validators;

class PageValidation
{
    public function getRules(): array
    {
        return [
            'title' => 'required|max:255',
            'language_code' => 'nullable|alpha|size:2',
            'slug' => 'nullable|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'meta_title' => 'nullable|max:255',
            'meta_keywords' => 'nullable|max:500',
            'ordering' => 'nullable|integer',
            'status' => 'required|in:0,1',
            'published_at' => 'nullable|date',
        ];
    }

    public function getUpdateRules($page): array
    {
        return [
            'title' => 'required|max:255',
            'language_code' => 'nullable|alpha|size:2',
            'slug' => 'nullable|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'meta_title' => 'nullable|max:255',
            'meta_keywords' => 'nullable|max:500',
            'ordering' => 'nullable|integer',
            'status' => 'required|in:0,1',
            'published_at' => 'nullable|date',
        ];
    }
}

