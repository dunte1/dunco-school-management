<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\PublicModule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = PublicModule::ordered()->get();

        return view('admin.cms.modules.index', compact('modules'));
    }

    public function create()
    {
        return view('admin.cms.modules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:public_modules,slug',
            'icon'             => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description'      => 'nullable|string',
            'features'         => 'nullable|string',
            'benefits'         => 'nullable|string',
            'hero_title'       => 'nullable|string|max:255',
            'hero_subtitle'    => 'nullable|string|max:500',
            'screenshot_path'  => 'nullable|string|max:500',
            'is_active'        => 'boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'seo_title'        => 'nullable|string|max:255',
            'seo_description'  => 'nullable|string|max:1000',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['features'] = $data['features'] ? array_map('trim', explode("\n", $data['features'])) : null;
        $data['benefits'] = $data['benefits'] ? array_map('trim', explode("\n", $data['benefits'])) : null;
        $data['is_active'] = $data['is_active'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        PublicModule::create($data);

        return redirect()->route('admin.cms.modules.index')
            ->with('success', 'Module created successfully.');
    }

    public function show(PublicModule $module)
    {
        return view('public.module-detail', compact('module'));
    }

    public function edit(PublicModule $module)
    {
        return view('admin.cms.modules.edit', compact('module'));
    }

    public function update(Request $request, PublicModule $module)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:public_modules,slug,' . $module->id,
            'icon'             => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description'      => 'nullable|string',
            'features'         => 'nullable|string',
            'benefits'         => 'nullable|string',
            'hero_title'       => 'nullable|string|max:255',
            'hero_subtitle'    => 'nullable|string|max:500',
            'screenshot_path'  => 'nullable|string|max:500',
            'is_active'        => 'boolean',
            'sort_order'       => 'nullable|integer|min:0',
            'seo_title'        => 'nullable|string|max:255',
            'seo_description'  => 'nullable|string|max:1000',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['features'] = $data['features'] ? array_map('trim', explode("\n", $data['features'])) : null;
        $data['benefits'] = $data['benefits'] ? array_map('trim', explode("\n", $data['benefits'])) : null;
        $data['is_active'] = $data['is_active'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $module->update($data);

        return redirect()->route('admin.cms.modules.index')
            ->with('success', 'Module updated successfully.');
    }

    public function destroy(PublicModule $module)
    {
        $module->delete();

        return redirect()->route('admin.cms.modules.index')
            ->with('success', 'Module deleted successfully.');
    }
}
