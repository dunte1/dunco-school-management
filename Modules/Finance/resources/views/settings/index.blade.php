@extends('finance::layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-8 mb-8 max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold text-blue-700 mb-6">Finance Settings</h2>

    @if (session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300 animate-fade-in">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300 animate-fade-in">
            <ul class="mb-0 ps-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('finance.settings.update') }}">
        @csrf
        <div class="mb-8">
            <h4 class="font-semibold text-lg mb-2 text-blue-600">General Settings</h4>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Currency</div>
                <input type="text" name="currency" class="form-control" value="{{ old('currency', $settings['currency'] ?? 'KES') }}">
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Financial Year Start</div>
                <input type="date" name="financial_year_start" class="form-control" value="{{ old('financial_year_start', $settings['financial_year_start'] ?? date('Y-01-01')) }}">
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Academic Calendar Type</div>
                <select name="academic_calendar_type" class="form-select">
                    <option value="trimester" @if(($settings['academic_calendar_type'] ?? '') == 'trimester') selected @endif>Trimester</option>
                    <option value="semester" @if(($settings['academic_calendar_type'] ?? '') == 'semester') selected @endif>Semester</option>
                    <option value="term" @if(($settings['academic_calendar_type'] ?? '') == 'term') selected @endif>Term</option>
                </select>
            </div>
        </div>
        <div class="mb-8">
            <h4 class="font-semibold text-lg mb-2 text-blue-600">Per-Campus Configuration</h4>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Campus/Branch Name</div>
                <input type="text" name="campus_name" class="form-control" value="{{ old('campus_name', $settings['campus_name'] ?? '') }}">
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Fee Structure (per campus)</div>
                <input type="text" name="campus_fee_structure" class="form-control" value="{{ old('campus_fee_structure', $settings['campus_fee_structure'] ?? '') }}">
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Bank Account (per campus)</div>
                <input type="text" name="campus_bank_account" class="form-control" value="{{ old('campus_bank_account', $settings['campus_bank_account'] ?? '') }}">
            </div>
        </div>
        <div class="mb-8">
            <h4 class="font-semibold text-lg mb-2 text-blue-600">Payment & Notification Settings</h4>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Default Payment Method</div>
                <select name="default_payment_method" class="form-select">
                    <option value="bank" @if(($settings['default_payment_method'] ?? '') == 'bank') selected @endif>Bank Transfer</option>
                    <option value="mpesa" @if(($settings['default_payment_method'] ?? '') == 'mpesa') selected @endif>Mpesa</option>
                    <option value="card" @if(($settings['default_payment_method'] ?? '') == 'card') selected @endif>Card</option>
                    <option value="cash" @if(($settings['default_payment_method'] ?? '') == 'cash') selected @endif>Cash</option>
                </select>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Enable Online Payments</div>
                <input type="checkbox" name="enable_online_payments" value="1" @if(($settings['enable_online_payments'] ?? false)) checked @endif> <span class="ms-2">Yes</span>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">MPESA API Key</div>
                <input type="text" name="mpesa_api_key" class="form-control" value="{{ old('mpesa_api_key', $settings['mpesa_api_key'] ?? '') }}">
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Card Payment API Key</div>
                <input type="text" name="card_api_key" class="form-control mt-2" placeholder="Card Processor API Key (if enabled)" value="{{ old('card_api_key', $settings['card_api_key'] ?? '') }}">
            </div>
        </div>

        <div class="mb-8">
            <h4 class="font-semibold text-lg mb-2 text-blue-600">PayPal Settings</h4>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Enable PayPal Payments</div>
                <input type="checkbox" name="paypal_enabled" value="1" @if(($settings['paypal_enabled'] ?? false)) checked @endif> <span class="ms-2">Enable</span>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">PayPal Mode</div>
                <select name="paypal_mode" class="form-select">
                    <option value="sandbox" @if(($settings['paypal_mode'] ?? 'sandbox') === 'sandbox') selected @endif>Sandbox</option>
                    <option value="live" @if(($settings['paypal_mode'] ?? '') === 'live') selected @endif>Live</option>
                </select>
            </div>
             <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Sandbox Client ID</div>
                <input type="text" name="paypal_sandbox_client_id" class="form-control" placeholder="Sandbox Client ID" value="{{ old('paypal_sandbox_client_id', $settings['paypal_sandbox_client_id'] ?? '') }}">
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Sandbox Client Secret</div>
                <input type="password" name="paypal_sandbox_client_secret" class="form-control" placeholder="Sandbox Client Secret" value="{{ old('paypal_sandbox_client_secret', $settings['paypal_sandbox_client_secret'] ?? '') }}">
            </div>
             <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Live Client ID</div>
                <input type="text" name="paypal_live_client_id" class="form-control" placeholder="Live Client ID" value="{{ old('paypal_live_client_id', $settings['paypal_live_client_id'] ?? '') }}">
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Live Client Secret</div>
                <input type="password" name="paypal_live_client_secret" class="form-control" placeholder="Live Client Secret" value="{{ old('paypal_live_client_secret', $settings['paypal_live_client_secret'] ?? '') }}">
            </div>
        </div>
        
        <div class="mb-8">
            <h4 class="font-semibold text-lg mb-2 text-blue-600">M-Pesa Settings</h4>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Enable M-Pesa Payments</div>
                <input type="checkbox" name="mpesa_enabled" value="1" @if(($settings['mpesa_enabled'] ?? false)) checked @endif> <span class="ms-2">Enable</span>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">M-Pesa Environment</div>
                <select name="mpesa_env" class="form-select">
                    <option value="sandbox" @if(($settings['mpesa_env'] ?? 'sandbox') === 'sandbox') selected @endif>Sandbox</option>
                    <option value="live" @if(($settings['mpesa_env'] ?? '') === 'live') selected @endif>Live</option>
                </select>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Consumer Key</div>
                <input type="text" name="mpesa_consumer_key" class="form-control" placeholder="Consumer Key" value="{{ old('mpesa_consumer_key', $settings['mpesa_consumer_key'] ?? '') }}">
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Consumer Secret</div>
                <input type="password" name="mpesa_consumer_secret" class="form-control" placeholder="Consumer Secret" value="{{ old('mpesa_consumer_secret', $settings['mpesa_consumer_secret'] ?? '') }}">
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Business Shortcode</div>
                <input type="text" name="mpesa_shortcode" class="form-control" placeholder="Business Shortcode" value="{{ old('mpesa_shortcode', $settings['mpesa_shortcode'] ?? '') }}">
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Passkey</div>
                <input type="password" name="mpesa_passkey" class="form-control" placeholder="Passkey" value="{{ old('mpesa_passkey', $settings['mpesa_passkey'] ?? '') }}">
            </div>
        </div>

        <div class="mb-8">
            <h4 class="font-semibold text-lg mb-2 text-blue-600">Reminders & Alerts</h4>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Send SMS Reminders</div>
                <input type="checkbox" name="send_sms_reminders" value="1" @if(($settings['send_sms_reminders'] ?? false)) checked @endif> <span class="ms-2">Yes</span>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Send Email Reminders</div>
                <input type="checkbox" name="send_email_reminders" value="1" @if(($settings['send_email_reminders'] ?? false)) checked @endif> <span class="ms-2">Yes</span>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">In-App Alerts</div>
                <input type="checkbox" name="in_app_alerts" value="1" @if(($settings['in_app_alerts'] ?? false)) checked @endif> <span class="ms-2">Yes</span>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">WhatsApp Integration (optional)</div>
                <input type="checkbox" name="whatsapp_integration" value="1" @if(($settings['whatsapp_integration'] ?? false)) checked @endif> <span class="ms-2">Enable</span>
                <input type="text" name="whatsapp_api_key" class="form-control mt-2" placeholder="WhatsApp API Key (if enabled)" value="{{ old('whatsapp_api_key', $settings['whatsapp_api_key'] ?? '') }}">
            </div>
        </div>
        <div class="text-end">
            <button class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection 