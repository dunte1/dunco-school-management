@extends('academic::layouts.app')

@section('content')
<div class="container">
    <h1>Edit Student</h1>
    <form method="POST" action="{{ route('academic.students.update', $student->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $student->name }}" required>
        </div>
        <div class="mb-3">
            <label for="admission_number" class="form-label">Admission Number</label>
            <input type="text" class="form-control" id="admission_number" name="admission_number" value="{{ $student->admission_number }}" required>
        </div>
        <div class="mb-3">
            <label for="class_id" class="form-label">Class</label>
            <input type="number" class="form-control" id="class_id" name="class_id" value="{{ $student->class_id }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="active" @if($student->status == 'active') selected @endif>Active</option>
                <option value="suspended" @if($student->status == 'suspended') selected @endif>Suspended</option>
                <option value="transferred" @if($student->status == 'transferred') selected @endif>Transferred</option>
                <option value="graduated" @if($student->status == 'graduated') selected @endif>Graduated</option>
                <option value="dropped_out" @if($student->status == 'dropped_out') selected @endif>Dropped Out</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status_reason" class="form-label">Status Reason</label>
            <textarea class="form-control" id="status_reason" name="status_reason">{{ $student->status_reason }}</textarea>
        </div>
        <div class="mb-3">
            <label for="parents" class="form-label">Parents/Guardians</label>
            <div id="parent-list">
                @foreach($student->parents as $parent)
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" value="{{ $parent->name }} ({{ $parent->pivot->relationship }})" readonly>
                        <span class="input-group-text">
                            @if($parent->pivot->is_primary)
                                Primary
                            @endif
                        </span>
                        <form method="POST" action="{{ route('academic.students.remove_parent', [$student->id, $parent->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addParentModal">Add Parent/Guardian</a>
        </div>
        <div class="mb-3">
            <label class="form-label">Supporting Documents</label>
            @php
                $schoolType = request('school_type', 'secondary');
                $intake = request('intake', '');
                $isTransfer = old('is_transfer', $student->is_transfer ?? false);
                $documentRequirements = [
                    'secondary' => [
                        ['key' => 'birth_certificate', 'label' => 'Birth Certificate', 'required' => true],
                        ['key' => 'passport_photo', 'label' => 'Passport-size Photo', 'required' => true],
                        ['key' => 'admission_letter', 'label' => 'Admission Letter', 'required' => true],
                        ['key' => 'kcpe_certificate', 'label' => 'KCPE Certificate', 'required' => true, 'intake' => 'form1'],
                        ['key' => 'kcse_certificate', 'label' => 'KCSE Certificate', 'required' => true, 'intake' => 'form5'],
                        ['key' => 'transfer_certificate', 'label' => 'Transfer/Leaving Certificate', 'required' => true, 'transfer' => true],
                    ],
                    'college' => [
                        ['key' => 'birth_certificate', 'label' => 'Birth Certificate', 'required' => true],
                        ['key' => 'passport_photo', 'label' => 'Passport-size Photo', 'required' => true],
                        ['key' => 'admission_letter', 'label' => 'Admission Letter', 'required' => true],
                        ['key' => 'national_id', 'label' => 'National ID / Passport', 'required' => true],
                        ['key' => 'transfer_certificate', 'label' => 'Transfer/Leaving Certificate', 'required' => true, 'transfer' => true],
                    ],
                    'university' => [
                        ['key' => 'birth_certificate', 'label' => 'Birth Certificate', 'required' => true],
                        ['key' => 'passport_photo', 'label' => 'Passport-size Photo', 'required' => true],
                        ['key' => 'admission_letter', 'label' => 'Admission Letter', 'required' => true],
                        ['key' => 'national_id', 'label' => 'National ID / Passport', 'required' => true],
                        ['key' => 'transfer_certificate', 'label' => 'Transfer/Leaving Certificate', 'required' => true, 'transfer' => true],
                    ],
                ];
                $docsConfig = $documentRequirements[$schoolType] ?? $documentRequirements['secondary'];
                $requiredDocs = collect($docsConfig)->filter(function($doc) use ($intake, $isTransfer) {
                    if (isset($doc['intake']) && $doc['intake'] !== $intake) return false;
                    if (isset($doc['transfer']) && $doc['transfer'] && !$isTransfer) return false;
                    return $doc['required'];
                });
                $uploadedDocs = $student->documents->keyBy('type');
            @endphp
            <ul class="list-group mb-3">
                @foreach($requiredDocs as $doc)
                    <li class="list-group-item d-flex justify-content-between align-items-center @if(!$uploadedDocs->has($doc['key'])) list-group-item-danger @endif">
                        <span>
                            {{ $doc['label'] }}
                            @if(!$uploadedDocs->has($doc['key']))
                                <span class="badge bg-danger ms-2">Missing</span>
                            @else
                                <span class="badge bg-success ms-2">Uploaded</span>
                            @endif
                        </span>
                        <span>
                            @if($uploadedDocs->has($doc['key']))
                                <a href="{{ asset('storage/' . $uploadedDocs[$doc['key']]->file_path) }}" target="_blank" class="btn btn-sm btn-primary">View</a>
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
            <form method="POST" action="{{ route('academic.students.upload_document', $student->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <select name="type" class="form-control" required>
                            <option value="">Select Document Type</option>
                            <option value="birth_cert">Birth Certificate</option>
                            <option value="kcpe_result">KCPE Result</option>
                            <option value="kcse_result">KCSE Result</option>
                            <option value="transfer_letter">Transfer Letter</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="file" name="document" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success">Upload</button>
                    </div>
                </div>
            </form>
            <ul class="list-group mt-3">
                @foreach($student->documents as $doc)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            {{ ucfirst(str_replace('_', ' ', $doc->type)) }}
                            <span class="badge ms-2
                                @if($doc->status == 'verified') bg-success
                                @elseif($doc->status == 'rejected') bg-danger
                                @else bg-secondary
                                @endif
                            ">
                                {{ ucfirst($doc->status) }}
                            </span>
                            @if($doc->review_note)
                                <span class="ms-2 text-muted small">({{ $doc->review_note }})</span>
                            @endif
                        </span>
                        <span class="d-flex align-items-center gap-2">
                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-primary">View</a>
                            <form method="POST" action="{{ route('academic.students.delete_document', [$student->id, $doc->id]) }}" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            <!-- Admin verification/rejection controls -->
                            <form method="POST" action="{{ route('academic.students.verify_document', [$student->id, $doc->id]) }}" class="d-flex align-items-center gap-1 ms-2">
                                @csrf
                                <input type="text" name="review_note" class="form-control form-control-sm" placeholder="Note" value="{{ $doc->review_note }}" style="width:120px;">
                                <button type="submit" name="action" value="verify" class="btn btn-success btn-sm" @if($doc->status == 'verified') disabled @endif>Verify</button>
                                <button type="submit" name="action" value="reject" class="btn btn-warning btn-sm" @if($doc->status == 'rejected') disabled @endif>Reject</button>
                            </form>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="mb-3">
            <label class="form-label">Enrollment History</label>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Academic Year</th>
                        <th>Status</th>
                        <th>Date Changed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->enrollmentHistory as $history)
                        <tr>
                            <td>{{ $history->class->name ?? '-' }}</td>
                            <td>{{ $history->academic_year }}</td>
                            <td>{{ ucfirst($history->status) }}</td>
                            <td>{{ $history->changed_at ? $history->changed_at->format('Y-m-d') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mb-3">
            <label class="form-label">Assigned Fees</label>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Total Paid</th>
                        <th>Outstanding</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->fees as $fee)
                        <tr>
                            <td>{{ $fee->category }}</td>
                            <td>{{ number_format($fee->amount, 2) }}</td>
                            <td>{{ $fee->due_date ? \Carbon\Carbon::parse($fee->due_date)->format('Y-m-d') : '-' }}</td>
                            <td>
                                @if($fee->status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($fee->status == 'partial')
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </td>
                            <td>{{ number_format($fee->total_paid, 2) }}</td>
                            <td>{{ number_format($fee->outstanding_amount, 2) }}</td>
                            <td>
                                <!-- Payment entry form -->
                                <form method="POST" action="{{ route('academic.students.record_payment', [$student->id, $fee->id]) }}" class="d-flex align-items-center gap-1">
                                    @csrf
                                    <input type="number" name="amount" class="form-control form-control-sm" placeholder="Amount" min="1" max="{{ $fee->outstanding_amount }}" step="0.01" required style="width:90px;">
                                    <input type="date" name="payment_date" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}" required style="width:130px;">
                                    <input type="text" name="method" class="form-control form-control-sm" placeholder="Method" style="width:90px;">
                                    <input type="text" name="reference" class="form-control form-control-sm" placeholder="Ref" style="width:90px;">
                                    <button type="submit" class="btn btn-success btn-sm">Record</button>
                                </form>
                                <!-- Payment history -->
                                @if($fee->payments->count())
                                    <ul class="list-unstyled mt-2 mb-0">
                                        @foreach($fee->payments as $pay)
                                            <li class="small">
                                                <span class="text-success">{{ number_format($pay->amount, 2) }}</span> on {{ \Carbon\Carbon::parse($pay->payment_date)->format('Y-m-d') }}
                                                @if($pay->method) <span class="text-muted">({{ $pay->method }})</span>@endif
                                                @if($pay->reference) <span class="text-muted">[{{ $pay->reference }}]</span>@endif
                                                @if($pay->note) <span class="text-muted">- {{ $pay->note }}</span>@endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('academic.students.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<!-- Add Parent Modal -->
<div class="modal fade" id="addParentModal" tabindex="-1" aria-labelledby="addParentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('academic.students.add_parent', $student->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addParentModalLabel">Add Parent/Guardian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Select Parent (User)</label>
                        <select class="form-control" id="parent_id" name="parent_id" required>
                            @foreach($allParents as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="relationship" class="form-label">Relationship</label>
                        <input type="text" class="form-control" id="relationship" name="relationship" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="is_primary" name="is_primary">
                        <label class="form-check-label" for="is_primary">Primary</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 