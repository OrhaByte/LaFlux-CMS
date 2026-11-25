<?php

namespace ExtensionsValley\Cms\Validators;

class PostValidation
{
    public function getRules(): array
    {
        return [
            'title' => 'required|max:255',
            'language_code' => 'nullable|alpha|size:2',
            'slug' => 'nullable|max:255',
            'category_id' => 'nullable|integer|exists:cms_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:cms_tags,id',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'meta_title' => 'nullable|max:255',
            'meta_keywords' => 'nullable|max:500',
            'status' => 'required|in:0,1',
            'published_at' => 'nullable|date',
        ];
    }

    public function getUpdateRules($post): array
    {
        return [
            'title' => 'required|max:255',
            'language_code' => 'nullable|alpha|size:2',
            'slug' => 'nullable|max:255',
            'category_id' => 'nullable|integer|exists:cms_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:cms_tags,id',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'meta_title' => 'nullable|max:255',
            'meta_keywords' => 'nullable|max:500',
            'status' => 'required|in:0,1',
            'published_at' => 'nullable|date',
        ];
    }
}

