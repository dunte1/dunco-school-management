<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Entities\FinanceSetting;

class SettingsController extends Controller
{
    public function index()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('finance_settings')) {
                $settings = FinanceSetting::pluck('value', 'key')->toArray();
            } else {
                $settings = [];
            }
        } catch (\Exception $e) {
            $settings = [];
        }
        
        return view('finance::settings.index', compact('settings'));
    }

    public function create()
    {
        return view('finance::settings.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.settings.index');
    }

    public function show($id)
    {
        return view('finance::settings.show');
    }

    public function edit($id)
    {
        return view('finance::settings.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|max:10',
            'financial_year_start' => 'required|date',
            'academic_calendar_type' => 'required|string|in:trimester,semester,term',
            'campus_name' => 'nullable|string|max:255',
            'campus_fee_structure' => 'nullable|string',
            'campus_bank_account' => 'nullable|string|max:255',
            'default_payment_method' => 'required|string|in:bank,mpesa,card,cash',
            'enable_online_payments' => 'boolean',
            'mpesa_api_key' => 'nullable|string',
            'card_api_key' => 'nullable|string',
            'paypal_enabled' => 'boolean',
            'paypal_mode' => 'required_if:paypal_enabled,1|string|in:sandbox,live',
            'paypal_sandbox_client_id' => 'nullable|string',
        ]);

        foreach ($request->except('_token', '_method') as $key => $value) {
            FinanceSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('finance.settings.index')->with('success', 'Settings updated successfully.');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.settings.index');
    }
}
