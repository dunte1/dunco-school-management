<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\PricingPlan;
use App\Models\PricingFeature;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $plans = PricingPlan::with('features')->ordered()->get();
        return view('admin.cms.pricing.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.cms.pricing.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:pricing_plans,slug',
            'price'          => 'required|numeric|min:0',
            'currency'       => 'nullable|string|max:10',
            'billing_period' => 'nullable|string|max:50',
            'description'    => 'nullable|string',
            'is_featured'    => 'boolean',
            'is_active'      => 'boolean',
            'sort_order'     => 'nullable|integer|min:0',
            'features'       => 'nullable|array',
            'features.*'     => 'nullable|string|max:500',
        ]);

        $slug = $data['slug'] ?: \Illuminate\Support\Str::slug($data['name']);
        $data['slug'] = $slug;
        $data['currency'] = $data['currency'] ?? 'USD';
        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['is_active'] = $data['is_active'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $features = $data['features'] ?? [];
        unset($data['features']);

        $plan = PricingPlan::create($data);

        foreach (array_filter($features) as $i => $featureText) {
            $plan->features()->create([
                'feature_text' => $featureText,
                'is_included'  => true,
                'sort_order'   => $i,
            ]);
        }

        return redirect()->route('admin.cms.pricing.index')
            ->with('success', 'Pricing plan created successfully.');
    }

    public function edit(PricingPlan $pricingPlan)
    {
        $pricingPlan->load('features');
        return view('admin.cms.pricing.edit', ['plan' => $pricingPlan]);
    }

    public function update(Request $request, PricingPlan $pricingPlan)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'slug'           => 'nullable|string|max:255|unique:pricing_plans,slug,' . $pricingPlan->id,
            'price'          => 'required|numeric|min:0',
            'currency'       => 'nullable|string|max:10',
            'billing_period' => 'nullable|string|max:50',
            'description'    => 'nullable|string',
            'is_featured'    => 'boolean',
            'is_active'      => 'boolean',
            'sort_order'     => 'nullable|integer|min:0',
            'features'       => 'nullable|array',
            'features.*'     => 'nullable|string|max:500',
            'feature_ids'    => 'nullable|array',
            'feature_ids.*'  => 'integer',
            'feature_included' => 'nullable|array',
        ]);

        $data['slug'] = $data['slug'] ?: \Illuminate\Support\Str::slug($data['name']);
        $data['currency'] = $data['currency'] ?? 'USD';
        $data['is_featured'] = $data['is_featured'] ?? false;
        $data['is_active'] = $data['is_active'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $features = $data['features'] ?? [];
        $featureIds = $data['feature_ids'] ?? [];
        $featureIncluded = $data['feature_included'] ?? [];
        unset($data['features'], $data['feature_ids'], $data['feature_included']);

        $pricingPlan->update($data);

        $pricingPlan->features()->delete();

        foreach (array_filter($features) as $i => $featureText) {
            $pricingPlan->features()->create([
                'feature_text' => $featureText,
                'is_included'  => true,
                'sort_order'   => $i,
            ]);
        }

        return redirect()->route('admin.cms.pricing.index')
            ->with('success', 'Pricing plan updated successfully.');
    }

    public function destroy(PricingPlan $pricingPlan)
    {
        $pricingPlan->features()->delete();
        $pricingPlan->delete();

        return redirect()->route('admin.cms.pricing.index')
            ->with('success', 'Pricing plan deleted successfully.');
    }
}
