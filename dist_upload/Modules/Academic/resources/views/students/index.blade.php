@extends('academic::layouts.app')

@section('content')
<div class="container">
    <h1>Student Enrollment</h1>
    <!-- Multi-Step Add Student Modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStudentWizardModal">Add Student</button>
    <div class="modal fade" id="addStudentWizardModal" tabindex="-1" aria-labelledby="addStudentWizardModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addStudentWizardModalLabel">Enroll Student</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="enrollWizard">
              <!-- Progress Bar -->
              <div class="progress mb-4">
                <div class="progress-bar" id="wizardProgressBar" role="progressbar" style="width: 20%">Step 1 of 5</div>
              </div>
              <form id="student-wizard-form" enctype="multipart/form-data">
                @csrf
                <!-- Step 1: Personal Info -->
                <div class="wizard-step" id="step-1">
                  <h6>Personal Information</h6>
                  <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                  <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                      <option value="">Select Gender</option>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                  </div>
                </div>
                <!-- Step 2: Academic Info (add transfer student logic, stream/house/group) -->
                <div class="wizard-step d-none" id="step-2">
                  <h6>Academic Information</h6>
                  <div class="mb-3">
                    <label for="class_id" class="form-label">Class</label>
                    <select class="form-control" id="class_id" name="class_id" required>
                      <option value="">Select Class</option>
                      <option value="1">Class 1</option>
                      <option value="2">Class 2</option>
                      <option value="3">Class 3</option>
                      <!-- Add more classes as needed -->
                    </select>
                  </div>
                  <div class="mb-3 d-none" id="streamGroupDiv">
                    <label for="stream" class="form-label">Stream/House/Group</label>
                    <input type="text" class="form-control" id="stream" name="stream">
                  </div>
                  <div class="mb-3">
                    <label for="admission_number" class="form-label">Admission Number</label>
                    <input type="text" class="form-control" id="admission_number" name="admission_number" readonly value="(Auto-generated after save)">
                  </div>
                  <div class="mb-3">
                    <label for="admission_date" class="form-label">Admission Date</label>
                    <input type="date" class="form-control" id="admission_date" name="admission_date" required>
                  </div>
                  <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" id="is_transfer" name="is_transfer">
                    <label class="form-check-label" for="is_transfer">Transfer Student</label>
                  </div>
                  <div class="mb-3 d-none" id="previousSchoolDiv">
                    <label for="previous_school" class="form-label">Previous School</label>
                    <input type="text" class="form-control" id="previous_school" name="previous_school">
                  </div>
                </div>
                <!-- Step 3: Parent Info (multiple parents, de-duplication) -->
                <div class="wizard-step d-none" id="step-3">
                  <h6>Parent/Guardian Information</h6>
                  <div id="parentList">
                    <div class="parent-entry mb-3">
                      <label class="form-label">Parent/Guardian Name</label>
                      <input type="text" class="form-control mb-2 parent-name" name="parent_name[]">
                      <label class="form-label">Contact Info (Email or Phone)</label>
                      <input type="text" class="form-control mb-2 parent-contact" name="parent_contact[]">
                      <button type="button" class="btn btn-danger btn-sm remove-parent d-none">Remove</button>
                      <div class="text-danger small d-none parent-duplicate">Duplicate parent detected!</div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-outline-primary btn-sm" id="addParentBtn">Add Another Parent/Guardian</button>
                </div>
                <!-- Step 4: Documents & Health -->
                <div class="wizard-step d-none" id="step-4">
                  <h6>Documents & Health</h6>
                  <div class="mb-3">
                    <label class="form-label">Passport Photo</label>
                    <div id="webcam-container" class="mb-2">
                      <video id="webcam" width="160" height="120" autoplay style="border-radius:8px;display:none;"></video>
                      <canvas id="webcam-canvas" width="160" height="120" style="display:none;"></canvas>
                      <button type="button" class="btn btn-outline-primary btn-sm" id="startWebcamBtn">Use Webcam</button>
                      <button type="button" class="btn btn-success btn-sm d-none" id="captureWebcamBtn">Capture Photo</button>
                      <button type="button" class="btn btn-secondary btn-sm d-none" id="retakeWebcamBtn">Retake</button>
                    </div>
                    <input type="hidden" id="webcam_photo" name="webcam_photo">
                    <div id="webcam-upload-fallback">
                      <input type="file" class="form-control" id="passport" name="passport" accept="image/*">
                      <small class="text-muted">Or upload a photo if you can't use the webcam.</small>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="birth_certificate" class="form-label">Birth Certificate</label>
                    <input type="file" class="form-control" id="birth_certificate" name="birth_certificate" accept="application/pdf,image/*">
                  </div>
                  <div class="mb-3">
                    <label for="immunizations" class="form-label">Immunizations (comma separated)</label>
                    <input type="text" class="form-control" id="immunizations" name="immunizations" placeholder="e.g. Polio, Measles, Hepatitis B">
                  </div>
                  <div class="mb-3">
                    <label for="chronic_conditions" class="form-label">Chronic Conditions</label>
                    <input type="text" class="form-control" id="chronic_conditions" name="chronic_conditions" placeholder="e.g. Asthma, Diabetes">
                  </div>
                  <div class="mb-3">
                    <label for="medical_conditions" class="form-label">Other Medical Conditions</label>
                    <textarea class="form-control" id="medical_conditions" name="medical_conditions"></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="disabilities" class="form-label">Disabilities</label>
                    <textarea class="form-control" id="disabilities" name="disabilities"></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="allergies" class="form-label">Allergies</label>
                    <textarea class="form-control" id="allergies" name="allergies"></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name">
                  </div>
                  <div class="mb-3">
                    <label for="emergency_contact_relationship" class="form-label">Emergency Contact Relationship</label>
                    <input type="text" class="form-control" id="emergency_contact_relationship" name="emergency_contact_relationship">
                  </div>
                  <div class="mb-3">
                    <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                    <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone">
                  </div>
                  <div class="mb-3">
                    <label for="doctor_name" class="form-label">Doctor's Name</label>
                    <input type="text" class="form-control" id="doctor_name" name="doctor_name">
                  </div>
                  <div class="mb-3">
                    <label for="doctor_phone" class="form-label">Doctor's Phone</label>
                    <input type="text" class="form-control" id="doctor_phone" name="doctor_phone">
                  </div>
                  <div class="mb-3">
                    <label for="insurance_provider" class="form-label">Health Insurance Provider</label>
                    <input type="text" class="form-control" id="insurance_provider" name="insurance_provider">
                  </div>
                  <div class="mb-3">
                    <label for="insurance_number" class="form-label">Insurance Number</label>
                    <input type="text" class="form-control" id="insurance_number" name="insurance_number">
                  </div>
                </div>
                <!-- Step 5: Review & Submit -->
                <div class="wizard-step d-none" id="step-5">
                  <h6>Review & Submit</h6>
                  <div id="reviewContent">
                    <!-- JS will fill this in -->
                  </div>
                </div>
                <div id="enroll-errors" class="alert alert-danger d-none"></div>
                <div class="d-flex justify-content-between mt-4">
                  <button type="button" class="btn btn-secondary" id="wizardPrevBtn" disabled>Previous</button>
                  <button type="button" class="btn btn-primary" id="wizardNextBtn">Next</button>
                  <button type="submit" class="btn btn-success d-none" id="wizardSubmitBtn">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Admission Number</th>
                <th>Class</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->admission_number }}</td>
                    <td>{{ $student->class->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('academic.students.edit', $student->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('academic.students.destroy', $student->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 

<script>
document.addEventListener('DOMContentLoaded', function() {
// Wizard logic
let currentStep = 1;
const totalSteps = 5;
function showStep(step) {
  document.querySelectorAll('.wizard-step').forEach((el, idx) => {
    el.classList.toggle('d-none', idx !== (step-1));
  });
  document.getElementById('wizardPrevBtn').disabled = (step === 1);
  document.getElementById('wizardNextBtn').classList.toggle('d-none', step === totalSteps);
  document.getElementById('wizardSubmitBtn').classList.toggle('d-none', step !== totalSteps);
  document.getElementById('wizardProgressBar').style.width = (step*20) + '%';
  document.getElementById('wizardProgressBar').textContent = `Step ${step} of ${totalSteps}`;
}
document.getElementById('wizardPrevBtn').onclick = function() {
  if (currentStep > 1) {
    currentStep--;
    showStep(currentStep);
  }
};
document.getElementById('wizardNextBtn').onclick = function() {
  if (currentStep < totalSteps) {
    currentStep++;
    showStep(currentStep);
    if (currentStep === totalSteps) {
      // Fill review content
      let review = '';
      const form = document.getElementById('student-wizard-form');
      new FormData(form).forEach((val, key) => {
        review += `<div><strong>${key}:</strong> ${val}</div>`;
      });
      document.getElementById('reviewContent').innerHTML = review;
    }
  }
};
showStep(currentStep);

// Dynamic fields for transfer student and stream/house/group
const classIdSelect = document.getElementById('class_id');
const streamGroupDiv = document.getElementById('streamGroupDiv');
classIdSelect.addEventListener('change', function() {
  // Example: Show stream/house/group for Class 3 only
  streamGroupDiv.classList.toggle('d-none', this.value !== '3');
});
const isTransfer = document.getElementById('is_transfer');
const previousSchoolDiv = document.getElementById('previousSchoolDiv');
isTransfer.addEventListener('change', function() {
  previousSchoolDiv.classList.toggle('d-none', !this.checked);
});
// Parent/Guardian management
const parentList = document.getElementById('parentList');
document.getElementById('addParentBtn').onclick = function() {
  const entry = parentList.firstElementChild.cloneNode(true);
  entry.querySelectorAll('input').forEach(input => input.value = '');
  entry.querySelector('.remove-parent').classList.remove('d-none');
  entry.querySelector('.parent-duplicate').classList.add('d-none');
  parentList.appendChild(entry);
};
parentList.addEventListener('click', function(e) {
  if (e.target.classList.contains('remove-parent')) {
    e.target.parentElement.remove();
  }
});
// De-duplication by contact info
parentList.addEventListener('input', function(e) {
  if (e.target.classList.contains('parent-contact')) {
    const contacts = Array.from(document.querySelectorAll('.parent-contact')).map(i => i.value.trim());
    document.querySelectorAll('.parent-contact').forEach((input, idx) => {
      const isDup = contacts.filter(c => c && c === input.value.trim()).length > 1;
      input.parentElement.querySelector('.parent-duplicate').classList.toggle('d-none', !isDup);
    });
  }
});

// Webcam photo capture logic
const webcam = document.getElementById('webcam');
const webcamCanvas = document.getElementById('webcam-canvas');
const startWebcamBtn = document.getElementById('startWebcamBtn');
const captureWebcamBtn = document.getElementById('captureWebcamBtn');
const retakeWebcamBtn = document.getElementById('retakeWebcamBtn');
const webcamPhotoInput = document.getElementById('webcam_photo');
const webcamUploadFallback = document.getElementById('webcam-upload-fallback');
let webcamStream = null;
if (startWebcamBtn) {
  startWebcamBtn.onclick = function() {
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(function(stream) {
        webcamStream = stream;
        webcam.srcObject = stream;
        webcam.style.display = 'block';
        captureWebcamBtn.classList.remove('d-none');
        retakeWebcamBtn.classList.add('d-none');
        webcamUploadFallback.style.display = 'none';
        startWebcamBtn.classList.add('d-none');
      });
  };
}
if (captureWebcamBtn) {
  captureWebcamBtn.onclick = function() {
    webcamCanvas.getContext('2d').drawImage(webcam, 0, 0, webcam.width, webcam.height);
    webcamCanvas.style.display = 'block';
    webcam.style.display = 'none';
    captureWebcamBtn.classList.add('d-none');
    retakeWebcamBtn.classList.remove('d-none');
    // Save image as base64
    webcamPhotoInput.value = webcamCanvas.toDataURL('image/png');
    // Stop webcam stream
    if (webcamStream) webcamStream.getTracks().forEach(track => track.stop());
  };
}
if (retakeWebcamBtn) {
  retakeWebcamBtn.onclick = function() {
    webcamCanvas.style.display = 'none';
    startWebcamBtn.classList.remove('d-none');
    retakeWebcamBtn.classList.add('d-none');
    webcamPhotoInput.value = '';
    webcamUploadFallback.style.display = 'block';
  };
}

// --- Document Requirements Config ---
const documentRequirements = {
  secondary: [
    { key: 'birth_certificate', label: 'Birth Certificate', required: true },
    { key: 'passport_photo', label: 'Passport-size Photo', required: true },
    { key: 'admission_letter', label: 'Admission Letter', required: true },
    { key: 'kcpe_certificate', label: 'KCPE Certificate', required: true, intake: 'form1' },
    { key: 'kcse_certificate', label: 'KCSE Certificate', required: true, intake: 'form5' },
    { key: 'transfer_certificate', label: 'Transfer/Leaving Certificate', required: true, transfer: true },
    { key: 'immunization_record', label: 'Immunization Record', required: false },
    { key: 'medical_clearance', label: 'Medical Clearance/Physical Exam', required: false },
    { key: 'special_needs_report', label: 'Special Needs Report', required: false },
    { key: 'previous_report_forms', label: 'Previous Report Forms', required: false },
  ],
  college: [
    { key: 'birth_certificate', label: 'Birth Certificate', required: true },
    { key: 'passport_photo', label: 'Passport-size Photo', required: true },
    { key: 'admission_letter', label: 'Admission Letter', required: true },
    { key: 'national_id', label: 'National ID / Passport', required: true },
    { key: 'transfer_certificate', label: 'Transfer/Leaving Certificate', required: true, transfer: true },
    { key: 'medical_clearance', label: 'Medical Clearance/Physical Exam', required: false },
    { key: 'special_needs_report', label: 'Special Needs Report', required: false },
    { key: 'previous_report_forms', label: 'Previous Report Forms', required: false },
  ],
  university: [
    { key: 'birth_certificate', label: 'Birth Certificate', required: true },
    { key: 'passport_photo', label: 'Passport-size Photo', required: true },
    { key: 'admission_letter', label: 'Admission Letter', required: true },
    { key: 'national_id', label: 'National ID / Passport', required: true },
    { key: 'transfer_certificate', label: 'Transfer/Leaving Certificate', required: true, transfer: true },
    { key: 'medical_clearance', label: 'Medical Clearance/Physical Exam', required: false },
    { key: 'special_needs_report', label: 'Special Needs Report', required: false },
    { key: 'previous_report_forms', label: 'Previous Report Forms', required: false },
  ]
};
// --- End Config ---

// Example: Assume a select for school type and intake exists (add if not)
const schoolTypeSelect = document.getElementById('school_type');
const intakeSelect = document.getElementById('intake');
const isTransferCheckbox = document.getElementById('is_transfer');
const documentUploadSection = document.createElement('div');
documentUploadSection.id = 'dynamicDocumentUploads';
const docStep = document.getElementById('step-4');
docStep.insertBefore(documentUploadSection, docStep.firstChild.nextSibling);

function renderDocumentUploads() {
  const schoolType = schoolTypeSelect ? schoolTypeSelect.value : 'secondary';
  const intake = intakeSelect ? intakeSelect.value : '';
  const isTransfer = isTransferCheckbox ? isTransferCheckbox.checked : false;
  const docs = documentRequirements[schoolType] || [];
  let html = '';
  docs.forEach(doc => {
    if ((doc.intake && doc.intake !== intake) || (doc.transfer && !isTransfer)) return;
    html += `<div class="mb-3">
      <label class="form-label">${doc.label}${doc.required ? ' <span class=\'text-danger\'>*</span>' : ''}</label>
      <input type="file" class="form-control required-doc" name="documents[${doc.key}]" id="doc_${doc.key}" ${doc.required ? 'required' : ''}>
    </div>`;
  });
  documentUploadSection.innerHTML = html;
}
if (schoolTypeSelect) schoolTypeSelect.addEventListener('change', renderDocumentUploads);
if (intakeSelect) intakeSelect.addEventListener('change', renderDocumentUploads);
if (isTransferCheckbox) isTransferCheckbox.addEventListener('change', renderDocumentUploads);
document.addEventListener('DOMContentLoaded', renderDocumentUploads);
// On submit, block if any required doc is missing
const wizardForm = document.getElementById('student-wizard-form');
if (wizardForm) {
  wizardForm.addEventListener('submit', function(e) {
    const requiredDocs = document.querySelectorAll('.required-doc');
    let missing = false;
    requiredDocs.forEach(input => {
      if (!input.value) missing = true;
    });
    if (missing) {
      e.preventDefault();
      alert('Please upload all required documents.');
    }
  });
}
});
</script> 