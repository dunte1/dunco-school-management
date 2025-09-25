<?php

namespace Modules\Attendance\Exports;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Modules\HR\Models\StaffAttendanceRecord;

class AttendanceReportExport implements FromCollection, WithHeadings, Responsable
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $studentQuery = \DB::table('academic_attendance_records')
            ->join('academic_students', 'academic_attendance_records.student_id', '=', 'academic_students.id')
            ->select(
                'academic_attendance_records.date',
                'academic_students.first_name',
                'academic_students.last_name',
                'academic_attendance_records.class_id',
                'academic_attendance_records.status',
                'academic_attendance_records.remarks',
                \DB::raw("'Student' as type")
            );
        if (!empty($this->filters['start_date'])) $studentQuery->where('date', '>=', $this->filters['start_date']);
        if (!empty($this->filters['end_date'])) $studentQuery->where('date', '<=', $this->filters['end_date']);
        if (!empty($this->filters['class_id'])) $studentQuery->where('academic_attendance_records.class_id', $this->filters['class_id']);
        if (!empty($this->filters['student_id'])) $studentQuery->where('student_id', $this->filters['student_id']);
        if (!empty($this->filters['subject_id'])) $studentQuery->where('subject_id', $this->filters['subject_id']);

        $staffQuery = StaffAttendanceRecord::query()
            ->join('staff', 'staff_attendance_records.staff_id', '=', 'staff.id')
            ->select(
                'staff_attendance_records.date',
                'staff.first_name',
                'staff.last_name',
                'staff_attendance_records.staff_id as class_id', // For compatibility
                'staff_attendance_records.status',
                'staff_attendance_records.remarks',
                \DB::raw("'Staff' as type")
            );
        if (!empty($this->filters['start_date'])) $staffQuery->where('date', '>=', $this->filters['start_date']);
        if (!empty($this->filters['end_date'])) $staffQuery->where('date', '<=', $this->filters['end_date']);
        if (!empty($this->filters['staff_id'])) $staffQuery->where('staff_id', $this->filters['staff_id']);

        $students = $studentQuery->get();
        $staff = $staffQuery->get();
        return $students->concat($staff);
    }

    public function headings(): array
    {
        return [
            'Date', 'First Name', 'Last Name', 'Class/Staff ID', 'Status', 'Remarks', 'Type'
        ];
    }
}
