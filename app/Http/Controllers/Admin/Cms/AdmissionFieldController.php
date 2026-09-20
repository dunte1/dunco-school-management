<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\AdmissionSection;
use App\Models\AdmissionField;
use App\Models\AdmissionDocumentType;
use Illuminate\Http\Request;

class AdmissionFieldController extends Controller
{
    public function index()
    {
        $sections = AdmissionSection::with('fields')->ordered()->get();
        $documentTypes = AdmissionDocumentType::ordered()->get();
        return view('admin.cms.admissions.fields.index', compact('sections', 'documentTypes'));
    }

    public function createSection()
    {
        return view('admin.cms.admissions.fields.create-section');
    }

    public function storeSection(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:admission_sections,slug',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: \Illuminate\Support\Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $data['is_active'] ?? true;

        AdmissionSection::create($data);

        return redirect()->route('admin.cms.admissions.fields.index')
            ->with('success', 'Section created successfully.');
    }

    public function editSection(AdmissionSection $section)
    {
        $section->load('fields');
        return view('admin.cms.admissions.fields.edit-section', compact('section'));
    }

    public function updateSection(Request $request, AdmissionSection $section)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:admission_sections,slug,' . $section->id,
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: \Illuminate\Support\Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $data['is_active'] ?? true;

        $section->update($data);

        return redirect()->route('admin.cms.admissions.fields.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroySection(AdmissionSection $section)
    {
        $section->fields()->delete();
        $section->delete();

        return redirect()->route('admin.cms.admissions.fields.index')
            ->with('success', 'Section and its fields deleted.');
    }

    public function createField()
    {
        $sections = AdmissionSection::active()->ordered()->get();
        return view('admin.cms.admissions.fields.create-field', compact('sections'));
    }

    public function storeField(Request $request)
    {
        $data = $request->validate([
            'admission_section_id' => 'required|exists:admission_sections,id',
            'name'                 => 'required|string|max:255',
            'slug'                 => 'nullable|string|max:255',
            'type'                 => 'required|string|in:text,textarea,number,email,phone,date,select,checkbox,radio,file',
            'options'              => 'nullable|array',
            'is_required'          => 'boolean',
            'help_text'            => 'nullable|string|max:500',
            'placeholder'          => 'nullable|string|max:255',
            'validation_rules'     => 'nullable|string|max:255',
            'sort_order'           => 'nullable|integer|min:0',
            'is_active'            => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: \Illuminate\Support\Str::slug($data['name']);
        $data['is_required'] = $data['is_required'] ?? false;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $data['is_active'] ?? true;

        AdmissionField::create($data);

        return redirect()->route('admin.cms.admissions.fields.index')
            ->with('success', 'Field created successfully.');
    }

    public function editField(AdmissionField $field)
    {
        $sections = AdmissionSection::active()->ordered()->get();
        return view('admin.cms.admissions.fields.edit-field', compact('field', 'sections'));
    }

    public function updateField(Request $request, AdmissionField $field)
    {
        $data = $request->validate([
            'admission_section_id' => 'required|exists:admission_sections,id',
            'name'                 => 'required|string|max:255',
            'slug'                 => 'nullable|string|max:255',
            'type'                 => 'required|string|in:text,textarea,number,email,phone,date,select,checkbox,radio,file',
            'options'              => 'nullable|array',
            'is_required'          => 'boolean',
            'help_text'            => 'nullable|string|max:500',
            'placeholder'          => 'nullable|string|max:255',
            'validation_rules'     => 'nullable|string|max:255',
            'sort_order'           => 'nullable|integer|min:0',
            'is_active'            => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: \Illuminate\Support\Str::slug($data['name']);
        $data['is_required'] = $data['is_required'] ?? false;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $data['is_active'] ?? true;

        $field->update($data);

        return redirect()->route('admin.cms.admissions.fields.index')
            ->with('success', 'Field updated successfully.');
    }

    public function destroyField(AdmissionField $field)
    {
        $field->delete();

        return redirect()->route('admin.cms.admissions.fields.index')
            ->with('success', 'Field deleted successfully.');
    }

    public function documents()
    {
        return redirect()->route('admin.cms.admissions.fields.index');
    }

    public function storeDocument(Request $request)
    {
        return $this->storeDocumentType($request);
    }

    public function destroyDocument(AdmissionDocumentType $documentType)
    {
        return $this->destroyDocumentType($documentType);
    }

    public function createDocumentType()
    {
        return view('admin.cms.admissions.fields.create-document-type');
    }

    public function storeDocumentType(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'is_required'   => 'boolean',
            'allowed_types' => 'nullable|string|max:255',
            'max_size_kb'   => 'nullable|integer|min:1',
            'is_active'     => 'boolean',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        $data['is_required'] = $data['is_required'] ?? true;
        $data['is_active'] = $data['is_active'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        AdmissionDocumentType::create($data);

        return redirect()->route('admin.cms.admissions.fields.index')
            ->with('success', 'Document type created successfully.');
    }

    public function editDocumentType(AdmissionDocumentType $documentType)
    {
        return view('admin.cms.admissions.fields.edit-document-type', ['documentType' => $documentType]);
    }

    public function updateDocumentType(Request $request, AdmissionDocumentType $documentType)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'is_required'   => 'boolean',
            'allowed_types' => 'nullable|string|max:255',
            'max_size_kb'   => 'nullable|integer|min:1',
            'is_active'     => 'boolean',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        $data['is_required'] = $data['is_required'] ?? true;
        $data['is_active'] = $data['is_active'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $documentType->update($data);

        return redirect()->route('admin.cms.admissions.fields.index')
            ->with('success', 'Document type updated successfully.');
    }

    public function destroyDocumentType(AdmissionDocumentType $documentType)
    {
        $documentType->delete();

        return redirect()->route('admin.cms.admissions.fields.index')
            ->with('success', 'Document type deleted.');
    }
}
