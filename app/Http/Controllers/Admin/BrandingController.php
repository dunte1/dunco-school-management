<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandingController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $schoolId = auth()->user()->school_id ?? null;
        $branding = Branding::query()->when($schoolId, fn($q)=>$q->where('school_id',$schoolId))->orderByDesc('id')->first();
        return view('admin.branding.edit', compact('branding'));
    }

    public function update(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'name' => ['nullable','string','max:255'],
            'primary_color' => ['nullable','string','max:32'],
            'secondary_color' => ['nullable','string','max:32'],
            'text_color' => ['nullable','string','max:32'],
            'logo' => ['nullable','image','max:2048'],
            'auth_bg' => ['nullable','image','max:4096'],
            'hero_bg' => ['nullable','image','max:4096'],
        ]);

        $schoolId = auth()->user()->school_id ?? null;
        $branding = Branding::firstOrNew(['school_id' => $schoolId]);
        $branding->name = $data['name'] ?? $branding->name;
        $branding->primary_color = $data['primary_color'] ?? $branding->primary_color;
        $branding->secondary_color = $data['secondary_color'] ?? $branding->secondary_color;
        $branding->text_color = $data['text_color'] ?? $branding->text_color;

        if ($request->hasFile('logo')) {
            $branding->logo_path = $request->file('logo')->store('branding/'.($schoolId ?? 'global'), 'public');
        }
        if ($request->hasFile('auth_bg')) {
            $branding->auth_bg_path = $request->file('auth_bg')->store('branding/'.($schoolId ?? 'global'), 'public');
        }
        if ($request->hasFile('hero_bg')) {
            $branding->hero_bg_path = $request->file('hero_bg')->store('branding/'.($schoolId ?? 'global'), 'public');
        }

        $branding->save();
        Cache::forget('branding:'.($schoolId ?? 'global'));

        return redirect()->back()->with('success', 'Branding updated.');
    }
}


