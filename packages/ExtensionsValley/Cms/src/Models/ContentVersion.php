<?php

namespace ExtensionsValley\Cms\Models;

use ExtensionsValley\Dashboard\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ContentVersion extends Model
{
    protected $table = 'cms_content_versions';

    protected $fillable = [
        'content_type',
        'content_id',
        'version_number',
        'title',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'created_by',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function recordSnapshot(string $type, Model $model, ?int $userId = null): self
    {
        $nextVersion = ((int) self::where('content_type', $type)
            ->where('content_id', $model->id)
            ->max('version_number')) + 1;

        return self::create([
            'content_type' => $type,
            'content_id' => $model->id,
            'version_number' => $nextVersion,
            'title' => $model->title ?? '',
            'excerpt' => $model->excerpt ?? null,
            'content' => $model->content ?? null,
            'featured_image' => $model->featured_image ?? null,
            'meta_title' => $model->meta_title ?? null,
            'meta_keywords' => $model->meta_keywords ?? null,
            'meta_description' => $model->meta_description ?? null,
            'created_by' => $userId ?? Auth::guard('admin')->id(),
        ]);
    }
}

