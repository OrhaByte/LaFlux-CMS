<?php

namespace ExtensionsValley\Cms\Controllers;

use ExtensionsValley\Cms\Helpers\CmsHelper;
use ExtensionsValley\Cms\Models\CmsForm;
use ExtensionsValley\Cms\Models\CmsFormField;
use ExtensionsValley\Cms\Models\CmsFormSubmission;
use ExtensionsValley\Dashboard\Models\traits\DashboardTraits;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FormController extends Controller
{
    use DashboardTraits;

    public function __construct()
    {
        $this->getNavigationBar();
        $this->getWidgets();
    }

    public function addForm()
    {
        $title = 'Add New Form';

        return view('Cms::admin.form.form', compact('title'));
    }

    public function saveForm(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'slug' => 'nullable|max:200|unique:cms_forms,slug',
            'submit_label' => 'nullable|max:100',
            'success_message' => 'nullable|max:500',
            'notify_email' => 'nullable|email|max:255',
            'status' => 'required|in:0,1',
        ]);

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.addform', [
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $slug = CmsHelper::uniqueSlug(
            'cms_forms',
            $request->filled('slug') ? $request->input('slug') : $request->input('name')
        );

        $form = CmsForm::create([
            'name' => $request->input('name'),
            'slug' => $slug,
            'submit_label' => $request->input('submit_label') ?: 'Submit',
            'success_message' => $request->input('success_message') ?: 'Thank you! Your submission has been received.',
            'notify_email' => $request->input('notify_email'),
            'status' => $request->input('status', 1),
        ]);

        return redirect()->route('extensionsvalley.cms.editform', [
            'id' => $form->id,
            'accesstoken' => $request->input('accesstoken') ?: base64_encode('extensionsvalley.cms.forms'),
        ])->with(['message' => 'Form created! Now configure its fields below.']);
    }

    public function editForm($id)
    {
        $title = 'Edit Form & Fields';
        $form = CmsForm::with('fields')->findOrFail($id);

        return view('Cms::admin.form.form', compact('title', 'form'));
    }

    public function viewForm($id)
    {
        $title = 'View Form';
        $form = CmsForm::with('fields')->findOrFail($id);
        $viewmode = 'view';

        return view('Cms::admin.form.form', compact('title', 'form', 'viewmode'));
    }

    public function updateForm(Request $request)
    {
        $form = CmsForm::findOrFail($request->input('form_id'));
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'slug' => 'nullable|max:200|unique:cms_forms,slug,' . $form->id,
            'submit_label' => 'nullable|max:100',
            'success_message' => 'nullable|max:500',
            'notify_email' => 'nullable|email|max:255',
            'status' => 'required|in:0,1',
        ]);

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editform', [
                'id' => $form->id,
                'accesstoken' => $request->input('accesstoken'),
            ])->withErrors($validation)->withInput();
        }

        $slug = CmsHelper::uniqueSlug(
            'cms_forms',
            $request->filled('slug') ? $request->input('slug') : $request->input('name'),
            $form->id
        );

        $form->update([
            'name' => $request->input('name'),
            'slug' => $slug,
            'submit_label' => $request->input('submit_label') ?: 'Submit',
            'success_message' => $request->input('success_message') ?: 'Thank you! Your submission has been received.',
            'notify_email' => $request->input('notify_email'),
            'status' => $request->input('status', 1),
        ]);

        return redirect('admin/ExtensionsValley/cms/list/cms_forms')
            ->with(['message' => 'Form updated successfully!']);
    }

    public function saveField(Request $request)
    {
        $formId = $request->input('form_id');
        $token = $request->input('accesstoken') ?: base64_encode('extensionsvalley.cms.forms');

        $validation = Validator::make($request->all(), [
            'form_id' => 'required|integer|exists:cms_forms,id',
            'label' => 'required|max:200',
            'field_name' => 'nullable|max:120',
            'field_type' => 'required|in:text,email,textarea,number,tel,date,select,radio,checkbox',
            'placeholder' => 'nullable|max:255',
            'options' => 'nullable|max:1000',
            'ordering' => 'nullable|integer',
        ]);

        if ($validation->fails()) {
            return redirect()->route('extensionsvalley.cms.editform', [
                'id' => $formId,
                'accesstoken' => $token,
            ])->withErrors($validation)->withInput();
        }

        $fieldName = Str::snake($request->filled('field_name') ? $request->input('field_name') : $request->input('label'));
        if ($fieldName === '') {
            $fieldName = 'field_' . time();
        }

        CmsFormField::create([
            'form_id' => $formId,
            'label' => $request->input('label'),
            'field_name' => $fieldName,
            'field_type' => $request->input('field_type'),
            'placeholder' => $request->input('placeholder'),
            'options' => $request->input('options'),
            'is_required' => $request->boolean('is_required'),
            'ordering' => (int) $request->input('ordering', 0),
        ]);

        return redirect()->route('extensionsvalley.cms.editform', [
            'id' => $formId,
            'accesstoken' => $token,
        ])->with(['message' => 'Form field added!']);
    }

    public function deleteField(Request $request, $id)
    {
        $field = CmsFormField::findOrFail($id);
        $formId = $field->form_id;
        $field->delete();

        $token = $request->input('accesstoken') ?: base64_encode('extensionsvalley.cms.forms');

        return redirect()->route('extensionsvalley.cms.editform', [
            'id' => $formId,
            'accesstoken' => $token,
        ])->with(['message' => 'Form field removed!']);
    }

    public function reorderFields(Request $request)
    {
        if (!CmsHelper::hasAcl('extensionsvalley.cms.forms', 'edit')) {
            return response()->json(['status' => 'forbidden'], 403);
        }

        $order = $request->input('order', []);
        if (!is_array($order) || empty($order)) {
            return response()->json(['status' => 'error'], 422);
        }

        foreach ($order as $idx => $fieldId) {
            CmsFormField::where('id', (int) $fieldId)->update(['ordering' => $idx + 1]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function submissions($id)
    {
        if (!CmsHelper::hasAcl('extensionsvalley.cms.forms', 'view')) {
            return redirect()->route('extensionsvalley.admin.dashboard')->with(['error' => 'Access Permission Denied!']);
        }

        $form = CmsForm::with('fields')->findOrFail($id);
        $submissions = $form->submissions()->paginate(20);
        $title = 'Form Submissions: ' . $form->name;

        return view('Cms::admin.form.submissions', compact('title', 'form', 'submissions'));
    }

    public function deleteSubmission($id)
    {
        if (!CmsHelper::hasAcl('extensionsvalley.cms.forms', 'trash')) {
            return redirect()->route('extensionsvalley.admin.dashboard')->with(['error' => 'Access Permission Denied!']);
        }

        $sub = CmsFormSubmission::findOrFail($id);
        $formId = $sub->form_id;
        $sub->delete();

        return redirect()->route('extensionsvalley.cms.formsubmissions', ['id' => $formId])
            ->with(['message' => 'Submission deleted!']);
    }
}

