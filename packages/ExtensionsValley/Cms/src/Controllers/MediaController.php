<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Models\Media;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function index(Request $request)
    {
        $title = 'Media Library';
        $search = trim((string) $request->get('q', ''));
        $type = (string) $request->get('type', 'all');

        $query = Media::query()->orderByDesc('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('file_name', 'like', '%' . $search . '%')
                    ->orWhere('alt_text', 'like', '%' . $search . '%');
            });
        }

        if ($type === 'images') {
            $query->where('mime_type', 'like', 'image/%');
        } elseif ($type === 'documents') {
            $query->where('mime_type', 'not like', 'image/%');
        }

        $mediaItems = $query->paginate(24)->withQueryString();

        return view('Cms::admin.media.index', compact('title', 'mediaItems', 'search', 'type'));
    }

    public function upload(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|mimes:jpeg,jpg,png,gif,webp,svg,pdf,doc,docx,xls,xlsx,zip,mp4,mp3|max:10240',
        ]);

        if ($validation->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validation->errors()], 422);
            }

            return redirect()->route('extensionsvalley.cms.media')
                ->withErrors($validation);
        }

        $uploaded = [];
        foreach ($request->file('files') as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $media = CmsHelper::storeMediaFile($file, Auth::guard('admin')->id());

            $uploaded[] = [
                'id' => $media->id,
                'title' => $media->title,
                'alt_text' => $media->alt_text,
                'file_path' => $media->file_path,
                'url' => asset($media->file_path),
                'is_image' => $media->is_image,
                'human_size' => $media->human_size,
            ];
        }

        if ($request->expectsJson()) {
            return response()->json(['uploaded' => $uploaded], 201);
        }

        return redirect()->route('extensionsvalley.cms.media')
            ->with(['message' => count($uploaded) . ' file(s) uploaded to Media Library!']);
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $media->update([
            'title' => $request->input('title', $media->title),
            'alt_text' => $request->input('alt_text'),
        ]);

        return redirect()->route('extensionsvalley.cms.media')
            ->with(['message' => 'Media details updated!']);
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $fullPath = public_path($media->file_path);
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
        $media->forceDelete();

        return redirect()->route('extensionsvalley.cms.media')
            ->with(['message' => 'Media file deleted permanently!']);
    }

    public function picker(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $onlyImages = $request->boolean('images_only', true);

        $query = Media::query()->orderByDesc('id');
        if ($onlyImages) {
            $query->where('mime_type', 'like', 'image/%');
        }
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('file_name', 'like', '%' . $search . '%');
            });
        }

        $items = $query->limit(48)->get()->map(fn (Media $m) => [
            'id' => $m->id,
            'title' => $m->title,
            'alt_text' => $m->alt_text,
            'file_name' => $m->file_name,
            'file_path' => $m->file_path,
            'url' => asset($m->file_path),
            'is_image' => $m->is_image,
            'human_size' => $m->human_size,
        ]);

        return response()->json(['data' => $items]);
    }
}

