<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Models\Category;
use ExtensionsValley\Cms\Models\ContentVersion;
use ExtensionsValley\Cms\Models\Post;
use ExtensionsValley\Cms\Models\Tag;
use ExtensionsValley\Cms\Validators\PostValidation;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function addPost()
    {
        $title = 'Add New Post';
        $categories = Category::getCategories();
        $tags = Tag::getTags();
        $selectedTags = [];

        return view('Cms::admin.post.form', compact('title', 'categories', 'tags', 'selectedTags'));
    }

    public function savePost(Request $request)
    {
        $validation = Validator::make($request->all(), (new PostValidation)->getRules());
        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.addpost', [
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $lang = strtolower($request->input('language_code') ?: CmsHelper::defaultLanguageCode());
        $slug = CmsHelper::uniqueSlug(
            'cms_posts',
            $request->filled('slug') ? $request->input('slug') : $request->input('title'),
            null,
            $lang
        );

        $featuredImage = CmsHelper::sanitizeMediaPath($request->input('selected_media_path'));
        if ($request->hasFile('featured_image')) {
            $featuredImage = CmsHelper::uploadFeaturedImage($request->file('featured_image'), 'posts');
        }

        $post = Post::create([
            'language_code' => $lang,
            'title' => $request->input('title'),
            'slug' => $slug,
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'category_id' => $request->input('category_id') ?: null,
            'featured_image' => $featuredImage,
            'meta_title' => $request->input('meta_title'),
            'meta_keywords' => $request->input('meta_keywords'),
            'meta_description' => $request->input('meta_description'),
            'status' => $request->input('status', 1),
            'published_at' => $request->filled('published_at') ? $request->input('published_at') : now(),
            'author_id' => Auth::guard('admin')->id(),
        ]);

        $post->tags()->sync($request->input('tags', []));

        if ((int) $post->status === 1) {
            ContentVersion::recordSnapshot('post', $post);
        }

        return redirect('admin/ExtensionsValley/cms/list/cms_posts')
            ->with(['message' => 'Post added successfully!']);
    }

    public function editPost($id)
    {
        $title = 'Edit Post';
        $post = Post::with(['tags', 'versions.author'])->findOrFail($id);
        $categories = Category::getCategories();
        $tags = Tag::getTags();
        $selectedTags = $post->tags->pluck('id')->toArray();

        return view('Cms::admin.post.form', compact('title', 'post', 'categories', 'tags', 'selectedTags'));
    }

    public function viewPost($id)
    {
        $title = 'View Post';
        $post = Post::with(['tags', 'versions.author'])->findOrFail($id);
        $categories = Category::getCategories();
        $tags = Tag::getTags();
        $selectedTags = $post->tags->pluck('id')->toArray();
        $viewmode = 'view';

        return view('Cms::admin.post.form', compact('title', 'post', 'categories', 'tags', 'selectedTags', 'viewmode'));
    }

    public function updatePost(Request $request)
    {
        $post = Post::findOrFail($request->input('post_id'));
        $validation = Validator::make($request->all(), (new PostValidation)->getUpdateRules($post));
        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editpost', [
                'id' => $post->id,
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $lang = strtolower($request->input('language_code') ?: $post->language_code ?: CmsHelper::defaultLanguageCode());
        $slug = CmsHelper::uniqueSlug(
            'cms_posts',
            $request->filled('slug') ? $request->input('slug') : $request->input('title'),
            $post->id,
            $lang
        );

        $featuredImage = $post->featured_image;
        if ($request->boolean('remove_featured_image')) {
            $featuredImage = null;
        }
        if ($request->filled('selected_media_path')) {
            $sanitized = CmsHelper::sanitizeMediaPath($request->input('selected_media_path'));
            if ($sanitized !== null) {
                $featuredImage = $sanitized;
            }
        }
        if ($request->hasFile('featured_image')) {
            $featuredImage = CmsHelper::uploadFeaturedImage($request->file('featured_image'), 'posts');
        }

        $post->update([
            'language_code' => $lang,
            'title' => $request->input('title'),
            'slug' => $slug,
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'category_id' => $request->input('category_id') ?: null,
            'featured_image' => $featuredImage,
            'meta_title' => $request->input('meta_title'),
            'meta_keywords' => $request->input('meta_keywords'),
            'meta_description' => $request->input('meta_description'),
            'status' => $request->input('status', 1),
            'published_at' => $request->filled('published_at') ? $request->input('published_at') : $post->published_at,
        ]);

        $post->tags()->sync($request->input('tags', []));

        if ((int) $post->status === 1) {
            ContentVersion::recordSnapshot('post', $post);
        }

        return redirect('admin/ExtensionsValley/cms/list/cms_posts')
            ->with(['message' => 'Post updated successfully!']);
    }
}

