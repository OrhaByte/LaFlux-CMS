<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Models\Banner;
use ExtensionsValley\Cms\Models\BannerSlide;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function addBanner()
    {
        $title = 'Add New Banner / Slider';

        return view('Cms::admin.banner.form', compact('title'));
    }

    public function saveBanner(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'slug' => 'nullable|max:200|unique:cms_banners,slug',
            'status' => 'required|in:0,1',
        ]);

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.addbanner', [
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $slug = CmsHelper::uniqueSlug(
            'cms_banners',
            $request->filled('slug') ? $request->input('slug') : $request->input('name')
        );

        $banner = Banner::create([
            'name' => $request->input('name'),
            'slug' => $slug,
            'status' => $request->input('status', 1),
        ]);

        return redirect()->route('extensionsvalley.cms.editbanner', [
            'id' => $banner->id,
            'accesstoken' => $request->input('accesstoken') ?: base64_encode('extensionsvalley.cms.banners'),
        ])->with(['message' => 'Banner created! Now add slides below.']);
    }

    public function editBanner($id)
    {
        $title = 'Edit Banner & Slides';
        $banner = Banner::with('slides')->findOrFail($id);

        return view('Cms::admin.banner.form', compact('title', 'banner'));
    }

    public function viewBanner($id)
    {
        $title = 'View Banner';
        $banner = Banner::with('slides')->findOrFail($id);
        $viewmode = 'view';

        return view('Cms::admin.banner.form', compact('title', 'banner', 'viewmode'));
    }

    public function updateBanner(Request $request)
    {
        $banner = Banner::findOrFail($request->input('banner_id'));
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'slug' => 'nullable|max:200|unique:cms_banners,slug,' . $banner->id,
            'status' => 'required|in:0,1',
        ]);

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editbanner', [
                'id' => $banner->id,
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $slug = CmsHelper::uniqueSlug(
            'cms_banners',
            $request->filled('slug') ? $request->input('slug') : $request->input('name'),
            $banner->id
        );

        $banner->update([
            'name' => $request->input('name'),
            'slug' => $slug,
            'status' => $request->input('status', 1),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_banners')
            ->with(['message' => 'Banner updated successfully!']);
    }

    public function saveSlide(Request $request)
    {
        $bannerId = $request->input('banner_id');
        $token = $request->input('accesstoken') ?: base64_encode('extensionsvalley.cms.banners');

        $validation = Validator::make($request->all(), [
            'banner_id' => 'required|integer|exists:cms_banners,id',
            'title' => 'nullable|max:255',
            'description' => 'nullable|max:1000',
            'slide_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'selected_media_path' => 'nullable|string|max:500',
            'button_text' => 'nullable|max:100',
            'button_link' => 'nullable|max:500',
            'ordering' => 'nullable|integer',
        ]);

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editbanner', [
                'id' => $bannerId,
                'accesstoken' => $token,
            ])->withErrors($validation)->withInput();
        }

        $imagePath = $request->input('selected_media_path');
        if ($request->hasFile('slide_image')) {
            $imagePath = CmsHelper::storeMediaFile($request->file('slide_image'))->file_path;
        }

        if (empty($imagePath)) {
            return redirect()->route('extensionsvalley.cms.editbanner', [
                'id' => $bannerId,
                'accesstoken' => $token,
            ])->with(['error' => 'Please select a slide image from Media Library or upload one.'])->withInput();
        }

        BannerSlide::create([
            'banner_id' => $bannerId,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
            'button_text' => $request->input('button_text'),
            'button_link' => $request->input('button_link'),
            'ordering' => (int) $request->input('ordering', 0),
            'status' => 1,
        ]);

        return redirect()->route('extensionsvalley.cms.editbanner', [
            'id' => $bannerId,
            'accesstoken' => $token,
        ])->with(['message' => 'Slide added to banner!']);
    }

    public function deleteSlide(Request $request, $id)
    {
        $slide = BannerSlide::findOrFail($id);
        $bannerId = $slide->banner_id;
        $slide->delete();

        $token = $request->input('accesstoken') ?: base64_encode('extensionsvalley.cms.banners');

        return redirect()->route('extensionsvalley.cms.editbanner', [
            'id' => $bannerId,
            'accesstoken' => $token,
        ])->with(['message' => 'Slide removed!']);
    }

    public function reorderSlides(Request $request)
    {
        $order = $request->input('order', []);
        if (!is_array($order) || empty($order)) {
            return response()->json(['status' => 'error'], 422);
        }

        foreach ($order as $idx => $slideId) {
            BannerSlide::where('id', (int) $slideId)->update(['ordering' => $idx + 1]);
        }

        return response()->json(['status' => 'ok']);
    }
}

