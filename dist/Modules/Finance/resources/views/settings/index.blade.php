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
            <h4 class="font-semibold text-lg mb-2 text-blue-600">M-Pesa Daraja API Settings</h4>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Enable M-Pesa Payments</div>
                <input type="checkbox" name="mpesa_enabled" value="1" @if(($settings['mpesa_enabled'] ?? false)) checked @endif> <span class="ms-2">Enable</span>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">M-Pesa Environment</div>
                <select name="mpesa_env" class="form-select">
                    <option value="sandbox" @if(($settings['mpesa_env'] ?? 'sandbox') === 'sandbox') selected @endif>Sandbox (Testing)</option>
                    <option value="live" @if(($settings['mpesa_env'] ?? '') === 'live') selected @endif>Live (Production)</option>
                </select>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Consumer Key</div>
                <input type="text" name="mpesa_consumer_key" class="form-control" placeholder="Daraja API Consumer Key" value="{{ old('mpesa_consumer_key', $settings['mpesa_consumer_key'] ?? '') }}">
                <small class="text-muted">Get this from your Safaricom Daraja developer account</small>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Consumer Secret</div>
                <input type="password" name="mpesa_consumer_secret" class="form-control" placeholder="Daraja API Consumer Secret" value="{{ old('mpesa_consumer_secret', $settings['mpesa_consumer_secret'] ?? '') }}">
                <small class="text-muted">Get this from your Safaricom Daraja developer account</small>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Business Shortcode</div>
                <input type="text" name="mpesa_shortcode" class="form-control" placeholder="Your M-Pesa Business Shortcode" value="{{ old('mpesa_shortcode', $settings['mpesa_shortcode'] ?? '') }}">
                <small class="text-muted">Your registered M-Pesa business shortcode (e.g., 174379 for sandbox)</small>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Passkey</div>
                <input type="password" name="mpesa_passkey" class="form-control" placeholder="Daraja API Passkey" value="{{ old('mpesa_passkey', $settings['mpesa_passkey'] ?? '') }}">
                <small class="text-muted">Get this from your Safaricom Daraja developer account</small>
            </div>
            
            <h5 class="font-semibold text-md mb-2 text-blue-500 mt-6">Advanced Daraja Features</h5>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Enable C2B (Customer to Business)</div>
                <input type="checkbox" name="mpesa_c2b_enabled" value="1" @if(($settings['mpesa_c2b_enabled'] ?? false)) checked @endif> <span class="ms-2">Enable</span>
                <small class="text-muted d-block mt-1">Allow customers to pay directly via M-Pesa</small>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Enable B2C (Business to Customer)</div>
                <input type="checkbox" name="mpesa_b2c_enabled" value="1" @if(($settings['mpesa_b2c_enabled'] ?? false)) checked @endif> <span class="ms-2">Enable</span>
                <small class="text-muted d-block mt-1">Send payments to customers (refunds, scholarships, etc.)</small>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Enable Transaction Reversal</div>
                <input type="checkbox" name="mpesa_reversal_enabled" value="1" @if(($settings['mpesa_reversal_enabled'] ?? false)) checked @endif> <span class="ms-2">Enable</span>
                <small class="text-muted d-block mt-1">Allow automatic transaction reversals for failed payments</small>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Enable Account Balance Check</div>
                <input type="checkbox" name="mpesa_balance_check_enabled" value="1" @if(($settings['mpesa_balance_check_enabled'] ?? false)) checked @endif> <span class="ms-2">Enable</span>
                <small class="text-muted d-block mt-1">Check M-Pesa account balance automatically</small>
            </div>
            
            <h5 class="font-semibold text-md mb-2 text-blue-500 mt-6">Callback URLs</h5>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">STK Push Callback URL</div>
                <input type="text" name="mpesa_stk_callback_url" class="form-control" placeholder="https://yourschool.com/finance/payment/mpesa-callback" value="{{ old('mpesa_stk_callback_url', $settings['mpesa_stk_callback_url'] ?? route('finance.payment.mpesa-callback')) }}">
                <small class="text-muted">URL where M-Pesa will send payment confirmations</small>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">C2B Confirmation URL</div>
                <input type="text" name="mpesa_c2b_confirmation_url" class="form-control" placeholder="https://yourschool.com/finance/payment/c2b-confirmation" value="{{ old('mpesa_c2b_confirmation_url', $settings['mpesa_c2b_confirmation_url'] ?? route('finance.payment.c2b-confirmation')) }}">
                <small class="text-muted">URL for C2B payment confirmations</small>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">C2B Validation URL</div>
                <input type="text" name="mpesa_c2b_validation_url" class="form-control" placeholder="https://yourschool.com/finance/payment/c2b-validation" value="{{ old('mpesa_c2b_validation_url', $settings['mpesa_c2b_validation_url'] ?? route('finance.payment.c2b-validation')) }}">
                <small class="text-muted">URL for C2B payment validations</small>
            </div>
            
            <h5 class="font-semibold text-md mb-2 text-blue-500 mt-6">Security Settings</h5>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Initiator Name</div>
                <input type="text" name="mpesa_initiator_name" class="form-control" placeholder="SchoolSystem" value="{{ old('mpesa_initiator_name', $settings['mpesa_initiator_name'] ?? 'SchoolSystem') }}">
                <small class="text-muted">Name used for B2C and reversal transactions</small>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Security Credential</div>
                <input type="password" name="mpesa_security_credential" class="form-control" placeholder="Base64 encoded security credential" value="{{ old('mpesa_security_credential', $settings['mpesa_security_credential'] ?? '') }}">
                <small class="text-muted">Required for B2C and reversal transactions (get from Safaricom)</small>
            </div>
            
            <h5 class="font-semibold text-md mb-2 text-blue-500 mt-6">Testing & Monitoring</h5>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Enable Detailed Logging</div>
                <input type="checkbox" name="mpesa_detailed_logging" value="1" @if(($settings['mpesa_detailed_logging'] ?? true)) checked @endif> <span class="ms-2">Enable</span>
                <small class="text-muted d-block mt-1">Log all M-Pesa API interactions for debugging</small>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 mb-4">
                <div class="mb-2 font-medium">Test Phone Numbers (Sandbox)</div>
                <input type="text" name="mpesa_test_phones" class="form-control" placeholder="254708374149,254733000000" value="{{ old('mpesa_test_phones', $settings['mpesa_test_phones'] ?? '254708374149,254733000000') }}">
                <small class="text-muted">Comma-separated list of test phone numbers for sandbox environment</small>
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