<?php

namespace ExtensionsValley\Cms\Validators;

class CategoryValidation
{
    public function getRules(): array
    {
        return [
            'name' => 'required|max:200',
            'language_code' => 'nullable|alpha|size:2',
            'slug' => 'nullable|max:220',
            'parent_id' => 'nullable|integer|exists:cms_categories,id',
            'meta_title' => 'nullable|max:255',
            'meta_keywords' => 'nullable|max:500',
            'status' => 'required|in:0,1',
        ];
    }

    public function getUpdateRules($category): array
    {
        return [
            'name' => 'required|max:200',
            'language_code' => 'nullable|alpha|size:2',
            'slug' => 'nullable|max:220',
            'parent_id' => 'nullable|integer|exists:cms_categories,id|not_in:' . $category->id,
            'meta_title' => 'nullable|max:255',
            'meta_keywords' => 'nullable|max:500',
            'status' => 'required|in:0,1',
        ];
    }
}

