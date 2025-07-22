<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Finance\Entities\FinanceSetting;
use App\Models\AuditLog;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || !$user->hasAnyRole(['admin', 'finance_manager'])) {
                abort(403, 'Unauthorized: You do not have access to Finance Settings.');
            }
            return $next($request);
        });
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'currency' => 'required|string|max:10',
            'financial_year_start' => 'required|date',
            'academic_calendar_type' => 'required|in:trimester,semester,term',
            'campus_name' => 'nullable|string|max:255',
            'campus_fee_structure' => 'nullable|string|max:255',
            'campus_bank_account' => 'nullable|string|max:255',
            'default_payment_method' => 'required|in:bank,mpesa,card,cash',
            'mpesa_api_key' => 'nullable|string|max:255',
            'card_api_key' => 'nullable|string|max:255',
            'whatsapp_api_key' => 'nullable|string|max:255',
            'paypal_mode' => 'nullable|in:sandbox,live',
            'paypal_sandbox_client_id' => 'nullable|string|max:255',
            'paypal_sandbox_client_secret' => 'nullable|string|max:255',
            'paypal_live_client_id' => 'nullable|string|max:255',
            'paypal_live_client_secret' => 'nullable|string|max:255',
            'mpesa_env' => 'nullable|in:sandbox,live',
            'mpesa_consumer_key' => 'nullable|string|max:255',
            'mpesa_consumer_secret' => 'nullable|string|max:255',
            'mpesa_shortcode' => 'nullable|string|max:255',
            'mpesa_passkey' => 'nullable|string|max:255',
        ], [
            'currency.required' => 'Currency is required.',
            'financial_year_start.required' => 'Financial year start date is required.',
            'academic_calendar_type.required' => 'Academic calendar type is required.',
            'default_payment_method.required' => 'Default payment method is required.',
        ]);

        $oldSettings = FinanceSetting::find(1)?->settings ?? null;
        $settings = array_merge($oldSettings ?? [], [
            'currency' => $validated['currency'],
            'financial_year_start' => $validated['financial_year_start'],
            'academic_calendar_type' => $validated['academic_calendar_type'],
            'campus_name' => $validated['campus_name'] ?? '',
            'campus_fee_structure' => $validated['campus_fee_structure'] ?? '',
            'campus_bank_account' => $validated['campus_bank_account'] ?? '',
            'default_payment_method' => $validated['default_payment_method'],
            'enable_online_payments' => $request->has('enable_online_payments'),
            'mpesa_api_key' => $validated['mpesa_api_key'] ?? '',
            'card_api_key' => $validated['card_api_key'] ?? '',
            'send_sms_reminders' => $request->has('send_sms_reminders'),
            'send_email_reminders' => $request->has('send_email_reminders'),
            'in_app_alerts' => $request->has('in_app_alerts'),
            'whatsapp_integration' => $request->has('whatsapp_integration'),
            'whatsapp_api_key' => $validated['whatsapp_api_key'] ?? '',
            'paypal_enabled' => $request->has('paypal_enabled'),
            'paypal_mode' => $validated['paypal_mode'] ?? 'sandbox',
            'paypal_sandbox_client_id' => $validated['paypal_sandbox_client_id'] ?? '',
            'paypal_sandbox_client_secret' => $validated['paypal_sandbox_client_secret'] ?? '',
            'paypal_live_client_id' => $validated['paypal_live_client_id'] ?? '',
            'paypal_live_client_secret' => $validated['paypal_live_client_secret'] ?? '',
            'mpesa_enabled' => $request->has('mpesa_enabled'),
            'mpesa_env' => $validated['mpesa_env'] ?? 'sandbox',
            'mpesa_consumer_key' => $validated['mpesa_consumer_key'] ?? '',
            'mpesa_consumer_secret' => $validated['mpesa_consumer_secret'] ?? '',
            'mpesa_shortcode' => $validated['mpesa_shortcode'] ?? '',
            'mpesa_passkey' => $validated['mpesa_passkey'] ?? '',
        ]);
        FinanceSetting::updateOrCreate(['id' => 1], ['settings' => $settings]);

        AuditLog::log(
            'finance.settings.updated',
            'Finance settings updated',
            $oldSettings,
            $settings
        );

        return redirect()->route('finance.settings.index')->with(['settings' => $settings, 'success' => 'Settings updated successfully!']);
    }

    public function index()
    {
        $defaultSettings = [
            'currency' => 'KES',
            'financial_year_start' => date('Y-01-01'),
            'academic_calendar_type' => 'trimester',
            'campus_name' => '',
            'campus_fee_structure' => '',
            'campus_bank_account' => '',
            'default_payment_method' => 'bank',
            'enable_online_payments' => false,
            'mpesa_api_key' => '',
            'card_api_key' => '',
            'send_sms_reminders' => false,
            'send_email_reminders' => false,
            'in_app_alerts' => false,
            'whatsapp_integration' => false,
            'whatsapp_api_key' => '',
            'paypal_enabled' => false,
            'paypal_mode' => 'sandbox',
            'paypal_sandbox_client_id' => '',
            'paypal_sandbox_client_secret' => '',
            'paypal_live_client_id' => '',
            'paypal_live_client_secret' => '',
            'mpesa_enabled' => false,
            'mpesa_env' => 'sandbox',
            'mpesa_consumer_key' => '',
            'mpesa_consumer_secret' => '',
            'mpesa_shortcode' => '',
            'mpesa_passkey' => '',
        ];
        
        $currentSettings = FinanceSetting::find(1)?->settings ?? [];
        $settings = array_merge($defaultSettings, $currentSettings);

        return view('finance::settings.index', compact('settings'));
    }
} 