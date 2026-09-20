<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\PublicSetting;
use Illuminate\Http\Request;

class PublicSettingController extends Controller
{
    private array $settingsConfig = [
        'hero_title'       => ['label' => 'Hero Title',       'type' => 'text'],
        'hero_subtitle'    => ['label' => 'Hero Subtitle',    'type' => 'textarea'],
        'hero_cta_text'    => ['label' => 'Hero CTA Text',    'type' => 'text'],
        'hero_cta_url'     => ['label' => 'Hero CTA URL',     'type' => 'text'],
        'about_text'       => ['label' => 'About Text',       'type' => 'textarea'],
        'mission_text'     => ['label' => 'Mission Text',     'type' => 'textarea'],
        'vision_text'      => ['label' => 'Vision Text',      'type' => 'textarea'],
        'contact_email'    => ['label' => 'Contact Email',    'type' => 'email'],
        'contact_phone'    => ['label' => 'Contact Phone',    'type' => 'text'],
        'contact_address'  => ['label' => 'Contact Address',  'type' => 'textarea'],
        'contact_map_embed'=> ['label' => 'Map Embed Code',   'type' => 'textarea'],
        'facebook_url'     => ['label' => 'Facebook URL',     'type' => 'url'],
        'twitter_url'      => ['label' => 'Twitter URL',      'type' => 'url'],
        'instagram_url'    => ['label' => 'Instagram URL',    'type' => 'url'],
        'linkedin_url'     => ['label' => 'LinkedIn URL',     'type' => 'url'],
        'youtube_url'      => ['label' => 'YouTube URL',      'type' => 'url'],
        'footer_text'      => ['label' => 'Footer Text',      'type' => 'textarea'],
        'logo_path'        => ['label' => 'Site Logo',        'type' => 'image'],
        'favicon_path'     => ['label' => 'Favicon',          'type' => 'image'],
    ];

    public function index()
    {
        $settings = PublicSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.cms.settings.index', [
            'settings'      => $settings,
            'settingsConfig' => $this->settingsConfig,
        ]);
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach ($this->settingsConfig as $key => $cfg) {
            $rules[$key] = 'nullable|string';
        }
        $validated = $request->validate($rules);

        foreach ($this->settingsConfig as $key => $cfg) {
            $value = $validated[$key] ?? null;

            if ($cfg['type'] === 'image' && $request->hasFile($key)) {
                if ($existing = PublicSetting::where('key', $key)->first()) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($existing->value);
                }
                $value = $request->file($key)->store('settings', 'public');
            }

            if ($value !== null) {
                PublicSetting::setVal($key, $value, $cfg['type']);
            }
        }

        return redirect()->route('admin.cms.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
