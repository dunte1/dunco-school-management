

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Edit Student</h1>
    <form method="POST" action="<?php echo e(route('academic.students.update', $student->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo e($student->name); ?>" required>
        </div>
        <div class="mb-3">
            <label for="admission_number" class="form-label">Admission Number</label>
            <input type="text" class="form-control" id="admission_number" name="admission_number" value="<?php echo e($student->admission_number); ?>" required>
        </div>
        <div class="mb-3">
            <label for="class_id" class="form-label">Class</label>
            <input type="number" class="form-control" id="class_id" name="class_id" value="<?php echo e($student->class_id); ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="active" <?php if($student->status == 'active'): ?> selected <?php endif; ?>>Active</option>
                <option value="suspended" <?php if($student->status == 'suspended'): ?> selected <?php endif; ?>>Suspended</option>
                <option value="transferred" <?php if($student->status == 'transferred'): ?> selected <?php endif; ?>>Transferred</option>
                <option value="graduated" <?php if($student->status == 'graduated'): ?> selected <?php endif; ?>>Graduated</option>
                <option value="dropped_out" <?php if($student->status == 'dropped_out'): ?> selected <?php endif; ?>>Dropped Out</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status_reason" class="form-label">Status Reason</label>
            <textarea class="form-control" id="status_reason" name="status_reason"><?php echo e($student->status_reason); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="parents" class="form-label">Parents/Guardians</label>
            <div id="parent-list">
                <?php $__currentLoopData = $student->parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" value="<?php echo e($parent->name); ?> (<?php echo e($parent->pivot->relationship); ?>)" readonly>
                        <span class="input-group-text">
                            <?php if($parent->pivot->is_primary): ?>
                                Primary
                            <?php endif; ?>
                        </span>
                        <form method="POST" action="<?php echo e(route('academic.students.remove_parent', [$student->id, $parent->id])); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <a href="#" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addParentModal">Add Parent/Guardian</a>
        </div>
        <div class="mb-3">
            <label class="form-label">Supporting Documents</label>
            <?php
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
            ?>
            <ul class="list-group mb-3">
                <?php $__currentLoopData = $requiredDocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center <?php if(!$uploadedDocs->has($doc['key'])): ?> list-group-item-danger <?php endif; ?>">
                        <span>
                            <?php echo e($doc['label']); ?>

                            <?php if(!$uploadedDocs->has($doc['key'])): ?>
                                <span class="badge bg-danger ms-2">Missing</span>
                            <?php else: ?>
                                <span class="badge bg-success ms-2">Uploaded</span>
                            <?php endif; ?>
                        </span>
                        <span>
                            <?php if($uploadedDocs->has($doc['key'])): ?>
                                <a href="<?php echo e(asset('storage/' . $uploadedDocs[$doc['key']]->file_path)); ?>" target="_blank" class="btn btn-sm btn-primary">View</a>
                            <?php endif; ?>
                        </span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <form method="POST" action="<?php echo e(route('academic.students.upload_document', $student->id)); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
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
                <?php $__currentLoopData = $student->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <?php echo e(ucfirst(str_replace('_', ' ', $doc->type))); ?>

                            <span class="badge ms-2
                                <?php if($doc->status == 'verified'): ?> bg-success
                                <?php elseif($doc->status == 'rejected'): ?> bg-danger
                                <?php else: ?> bg-secondary
                                <?php endif; ?>
                            ">
                                <?php echo e(ucfirst($doc->status)); ?>

                            </span>
                            <?php if($doc->review_note): ?>
                                <span class="ms-2 text-muted small">(<?php echo e($doc->review_note); ?>)</span>
                            <?php endif; ?>
                        </span>
                        <span class="d-flex align-items-center gap-2">
                            <a href="<?php echo e(asset('storage/' . $doc->file_path)); ?>" target="_blank" class="btn btn-sm btn-primary">View</a>
                            <form method="POST" action="<?php echo e(route('academic.students.delete_document', [$student->id, $doc->id])); ?>" style="display:inline-block;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            <!-- Admin verification/rejection controls -->
                            <form method="POST" action="<?php echo e(route('academic.students.verify_document', [$student->id, $doc->id])); ?>" class="d-flex align-items-center gap-1 ms-2">
                                <?php echo csrf_field(); ?>
                                <input type="text" name="review_note" class="form-control form-control-sm" placeholder="Note" value="<?php echo e($doc->review_note); ?>" style="width:120px;">
                                <button type="submit" name="action" value="verify" class="btn btn-success btn-sm" <?php if($doc->status == 'verified'): ?> disabled <?php endif; ?>>Verify</button>
                                <button type="submit" name="action" value="reject" class="btn btn-warning btn-sm" <?php if($doc->status == 'rejected'): ?> disabled <?php endif; ?>>Reject</button>
                            </form>
                        </span>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__currentLoopData = $student->enrollmentHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($history->class->name ?? '-'); ?></td>
                            <td><?php echo e($history->academic_year); ?></td>
                            <td><?php echo e(ucfirst($history->status)); ?></td>
                            <td><?php echo e($history->changed_at ? $history->changed_at->format('Y-m-d') : '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__currentLoopData = $student->fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($fee->category->name ?? 'N/A'); ?></td>
                            <td><?php echo e(number_format($fee->amount, 2)); ?></td>
                            <td><?php echo e($fee->due_date ? \Carbon\Carbon::parse($fee->due_date)->format('Y-m-d') : '-'); ?></td>
                            <td>
                                <?php if($fee->status == 'paid'): ?>
                                    <span class="badge bg-success">Paid</span>
                                <?php elseif($fee->status == 'partial'): ?>
                                    <span class="badge bg-warning text-dark">Partial</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Unpaid</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(number_format($fee->total_paid, 2)); ?></td>
                            <td><?php echo e(number_format($fee->outstanding_amount, 2)); ?></td>
                            <td>
                                <!-- Payment entry form -->
                                <form method="POST" action="<?php echo e(route('academic.students.record_payment', [$student->id, $fee->id])); ?>" class="d-flex align-items-center gap-1">
                                    <?php echo csrf_field(); ?>
                                    <input type="number" name="amount" class="form-control form-control-sm" placeholder="Amount" min="1" max="<?php echo e($fee->outstanding_amount); ?>" step="0.01" required style="width:90px;">
                                    <input type="date" name="payment_date" class="form-control form-control-sm" value="<?php echo e(now()->format('Y-m-d')); ?>" required style="width:130px;">
                                    <input type="text" name="method" class="form-control form-control-sm" placeholder="Method" style="width:90px;">
                                    <input type="text" name="reference" class="form-control form-control-sm" placeholder="Ref" style="width:90px;">
                                    <button type="submit" class="btn btn-success btn-sm">Record</button>
                                </form>
                                <!-- Payment history -->
                                <?php if($fee->payments->count()): ?>
                                    <ul class="list-unstyled mt-2 mb-0">
                                        <?php $__currentLoopData = $fee->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="small">
                                                <span class="text-success"><?php echo e(number_format($pay->amount, 2)); ?></span> on <?php echo e(\Carbon\Carbon::parse($pay->payment_date)->format('Y-m-d')); ?>

                                                <?php if($pay->method): ?> <span class="text-muted">(<?php echo e($pay->method); ?>)</span><?php endif; ?>
                                                <?php if($pay->reference): ?> <span class="text-muted">[<?php echo e($pay->reference); ?>]</span><?php endif; ?>
                                                <?php if($pay->note): ?> <span class="text-muted">- <?php echo e($pay->note); ?></span><?php endif; ?>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?php echo e(route('academic.students.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<!-- Add Parent Modal -->
<div class="modal fade" id="addParentModal" tabindex="-1" aria-labelledby="addParentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('academic.students.add_parent', $student->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="addParentModalLabel">Add Parent/Guardian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Select Parent (User)</label>
                        <select class="form-control" id="parent_id" name="parent_id" required>
                            <?php $__currentLoopData = $allParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('academic::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\students\edit.blade.php ENDPATH**/ ?>