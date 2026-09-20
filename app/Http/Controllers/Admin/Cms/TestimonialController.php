<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::ordered()->get();
        return view('admin.cms.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.cms.testimonials.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'position'    => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'content'     => 'required|string',
            'rating'      => 'nullable|integer|min:1|max:5',
            'image'       => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('testimonials', 'public');
        }

        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['is_active']   = $data['is_active'] ?? true;
        $data['sort_order']  = $data['sort_order'] ?? 0;

        Testimonial::create($data);

        return redirect()->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.cms.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'position'    => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'content'     => 'required|string',
            'rating'      => 'nullable|integer|min:1|max:5',
            'image'       => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($testimonial->image_path) {
                Storage::disk('public')->delete($testimonial->image_path);
            }
            $data['image_path'] = $request->file('image')->store('testimonials', 'public');
        }

        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['is_active']   = $data['is_active'] ?? true;
        $data['sort_order']  = $data['sort_order'] ?? 0;

        $testimonial->update($data);

        return redirect()->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image_path) {
            Storage::disk('public')->delete($testimonial->image_path);
        }

        $testimonial->delete();

        return redirect()->route('admin.cms.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }
}
