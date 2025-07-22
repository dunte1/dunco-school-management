<div style="width:340px;height:210px;border:2px solid #003366;border-radius:12px;padding:16px;display:flex;align-items:center;background:#f8f9fa;box-shadow:0 2px 8px #00336622;">
    <div style="flex:0 0 90px;text-align:center;">
        @if($student->passport)
            <img src="{{ asset('storage/' . $student->passport) }}" alt="Photo" style="width:80px;height:100px;object-fit:cover;border-radius:8px;border:2px solid #003366;">
        @else
            <div style="width:80px;height:100px;background:#e0e0e0;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#888;">No Photo</div>
        @endif
    </div>
    <div style="flex:1 1 auto;padding-left:16px;">
        <div style="font-size:1.1em;font-weight:bold;color:#003366;">{{ $school->name ?? 'School Name' }}</div>
        <div style="font-size:0.9em;color:#555;">{{ $school->address ?? '' }}</div>
        <hr style="margin:8px 0;">
        <div><strong>Name:</strong> {{ $student->name }}</div>
        <div><strong>Adm No:</strong> {{ $student->admission_number }}</div>
        <div><strong>Class:</strong> {{ $student->class->name ?? '-' }}</div>
        <div><strong>DOB:</strong> {{ $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '-' }}</div>
        <div style="margin-top:8px;font-size:0.85em;color:#003366;">STUDENT ID CARD</div>
    </div>
</div> 