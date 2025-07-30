@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Finance</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.finance') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    {{-- Balance Summary --}}
    @php
        $totalDue = $studentFees->sum('amount');
        $totalPaid = $studentFees->sum('total_paid');
        $totalOutstanding = $studentFees->sum('outstanding_amount');
    @endphp
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card bg-soft-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Due</h6>
                    <h4 class="fw-bold">Ksh {{ number_format($totalDue, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-soft-success">
                <div class="card-body">
                    <h6 class="text-muted">Total Paid</h6>
                    <h4 class="fw-bold">Ksh {{ number_format($totalPaid, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-soft-danger">
                <div class="card-body">
                    <h6 class="text-muted">Outstanding</h6>
                    <h4 class="fw-bold">Ksh {{ number_format($totalOutstanding, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Fee Statement --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Fee Statement</h5>
        </div>
        <div class="card-body">
            @if($studentFees->count())
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentFees as $fee)
                    <tr>
                        <td>{{ $fee->category }}</td>
                        <td>Ksh {{ number_format($fee->amount, 2) }}</td>
                        <td><span class="badge bg-{{ $fee->status == 'paid' ? 'success' : ($fee->status == 'partial' ? 'warning' : 'danger') }}">{{ ucfirst($fee->status) }}</span></td>
                        <td>{{ $fee->due_date->format('M d, Y') }}</td>
                        <td>
                            @if($fee->status !== 'paid')
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#payNowModal-{{ $fee->id }}">Pay Now</button>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Pay Now Modal -->
                    <div class="modal fade" id="payNowModal-{{ $fee->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Pay Fee: {{ $fee->category }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="payment-modal-content">
                                        {{-- Section A: Student + Fee Info --}}
                                        <div class="text-center mb-4">
                                            {{-- Placeholder for School Logo --}}
                                            <h5 class="mt-2">Dunco School</h5>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <p><strong>Student Name:</strong> {{ $students->first()?->name }}</p>
                                                <p><strong>Admission No.:</strong> {{ $students->first()?->admission_number }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Class / Program:</strong> {{ $students->first()?->currentClass?->name }}</p>
                                                {{-- Placeholder for Term/Year --}}
                                                <p><strong>Term / Year:</strong> Term 2, 2025</p> 
                                            </div>
                                        </div>
                                        <div class="row text-center bg-light p-3 rounded mb-4">
                                            <div class="col-4"><strong>Total Fee:</strong><br>KES {{ number_format($fee->amount, 2) }}</div>
                                            <div class="col-4"><strong>Amount Paid:</strong><br>KES {{ number_format($fee->payments->sum('amount'), 2) }}</div>
                                            <div class="col-4"><strong>Balance Due:</strong><br>KES {{ number_format($fee->amount - $fee->payments->sum('amount'), 2) }}</div>
                                        </div>
                                        <hr>
                                        {{-- Section B: Enter Payment --}}
                                        <div class="payment-section">
                                            <div class="mb-3">
                                                <label for="amount_to_pay-{{ $fee->id }}" class="form-label">💵 Amount to Pay</label>
                                                <input type="number" id="amount_to_pay-{{ $fee->id }}" class="form-control" name="amount" required max="{{ $fee->outstanding_amount }}" value="{{ $fee->outstanding_amount }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="payment_method-{{ $fee->id }}" class="form-label">📥 Select Payment Method</label>
                                                <select id="payment_method-{{ $fee->id }}" class="form-select" onchange="showPaymentFields('{{ $fee->id }}')">
                                                    <option value="">-- Select --</option>
                                                    @if(!empty($paymentSettings['mpesa_enabled']))<option value="mpesa">MPESA STK Push</option>@endif
                                                    @if(!empty($paymentSettings['paypal_enabled']))<option value="paypal">Visa/MasterCard (PayPal)</option>@endif
                                                    @if(!empty($paymentSettings['enable_online_payments']))
                                                    <option value="card">Visa/MasterCard (Stripe)</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div id="payment-fields-{{ $fee->id }}" class="mt-3"></div>
                                        </div>
                                    </div>
                                    {{-- Processing Spinner and Success/Error Messages --}}
                                    <div class="payment-processing-view" style="display: none;">
                                        <div class="text-center">
                                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <h5 class="mt-3">Processing payment...</h5>
                                            <p>Please do not close this window.</p>
                                        </div>
                                    </div>
                                    <div class="payment-result-view" style="display: none;">
                                        {{-- Success or Error content will be injected here --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <p>No fee records found. All clear!</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Invoices & Receipts --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Invoices & Receipts</h5>
        </div>
        <div class="card-body">
            @if($studentPayments->count())
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentPayments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>{{ $payment->fee->category ?? '-' }}</td>
                        <td>Ksh {{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->method ?? '-' }}</td>
                        <td>{{ $payment->reference ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-receipt fa-2x mb-2"></i>
                <p>No payment records found.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Online Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Online payment gateway integration is coming soon. Please contact the school office to make a payment.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection 

@push('scripts')
<script>
function showPaymentFields(feeId) {
    const method = document.getElementById(`payment_method-${feeId}`).value;
    const container = document.getElementById(`payment-fields-${feeId}`);
    const amountInput = document.getElementById(`amount_to_pay-${feeId}`);

    amountInput.addEventListener('change', () => showPaymentFields(feeId));
    
    const amount = amountInput.value;
    let html = '';

    if (method === 'mpesa') {
        html = `
            <form id="payment-form-${feeId}" onsubmit="handleFormSubmit(event, ${feeId}, '{{ url('finance/payment/mpesa-stk') }}/${feeId}')">
                @csrf
                <input type="hidden" name="amount" value="${amount}">
                <div class="mb-3">
                    <label for="phone-${feeId}" class="form-label">M-Pesa Phone Number</label>
                    <input type="text" id="phone-${feeId}" name="phone" class="form-control" placeholder="2547..." required>
                </div>
                <div class="mb-3">
                    <label for="notes-${feeId}" class="form-label">Notes (Optional)</label>
                    <textarea id="notes-${feeId}" name="notes" class="form-control"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Pay KES ${amount}</button>
                </div>
            </form>
        `;
    } else if (method === 'paypal') {
        html = `
            <form id="payment-form-${feeId}" action="{{ url('finance/payment/pay') }}/${feeId}" method="POST">
                @csrf
                <input type="hidden" name="amount" value="${amount}">
                <div class="mb-3">
                    <label for="notes-${feeId}" class="form-label">Notes (Optional)</label>
                    <textarea id="notes-${feeId}" name="notes" class="form-control"></textarea>
                </div>
                <p class="text-muted">You will be redirected to PayPal to complete the payment.</p>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Pay KES ${amount} with PayPal</button>
                </div>
            </form>
        `;
    } else if (method === 'card') {
        html = `
            <div class="text-center">
                <p class="text-muted">Card payment is not yet fully implemented. Please select another method.</p>
            </div>
        `;
    } else if (method === 'bank_transfer') {
        html = `
            <form id="payment-form-${feeId}" action="{{ url('finance/payment/bank-transfer') }}/${feeId}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="amount" value="${amount}">
                <div class="mb-3">
                    <label for="bank_reference-${feeId}" class="form-label">Bank Transaction Reference</label>
                    <input type="text" id="bank_reference-${feeId}" name="bank_reference" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="payment_slip-${feeId}" class="form-label">Upload Payment Slip</label>
                    <input type="file" id="payment_slip-${feeId}" name="payment_slip" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="notes-${feeId}" class="form-label">Notes (Optional)</label>
                    <textarea id="notes-${feeId}" name="notes" class="form-control"></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Submit for Confirmation</button>
                </div>
            </form>
        `;
    }

    container.innerHTML = html;
}

async function handleFormSubmit(event, feeId, url) {
    event.preventDefault();
    const form = document.getElementById(`payment-form-${feeId}`);
    const formData = new FormData(form);
    
    const modalContent = document.querySelector(`#payNowModal-${feeId} .payment-modal-content`);
    const processingView = document.querySelector(`#payNowModal-${feeId} .payment-processing-view`);
    const resultView = document.querySelector(`#payNowModal-${feeId} .payment-result-view`);

    modalContent.style.display = 'none';
    processingView.style.display = 'block';

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });

        const result = await response.json();
        
        let resultHtml = '';
        if (response.ok) {
            resultHtml = `
                <div class="text-center text-success">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h4>Payment Initiated!</h4>
                    <p>${result.message}</p>
                    <p class="text-muted small">Please check your phone to enter your M-Pesa PIN.</p>
                    <button class="btn btn-primary mt-3" onclick="location.reload()">Done</button>
                </div>
            `;
        } else {
            resultHtml = `
                <div class="text-center text-danger">
                    <i class="fas fa-times-circle fa-3x mb-3"></i>
                    <h4>Payment Failed</h4>
                    <p>${result.message || 'An unexpected error occurred.'}</p>
                    <button class="btn btn-secondary mt-3" onclick="location.reload()">Try Again</button>
                </div>
            `;
        }
        resultView.innerHTML = resultHtml;

    } catch (error) {
        resultView.innerHTML = `
            <div class="text-center text-danger">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <h4>Network Error</h4>
                <p>Could not connect to the server. Please check your connection and try again.</p>
                <button class="btn btn-secondary mt-3" onclick="location.reload()">Try Again</button>
            </div>
        `;
    } finally {
        processingView.style.display = 'none';
        resultView.style.display = 'block';
    }
}
</script>
@endpush 