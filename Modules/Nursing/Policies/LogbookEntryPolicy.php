<?php

namespace Modules\Nursing\Policies;

use App\Models\User;
use Modules\Nursing\Models\LogbookEntry;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogbookEntryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LogbookEntry $logbook): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin', 'system_administrator'])) {
            return true;
        }

        if ($logbook->school_id !== $user->school_id) {
            return false;
        }

        if ($user->hasAnyRole(['student'])) {
            $student = $user->academicStudent;
            return $student && $logbook->student_id === $student->id;
        }

        if ($user->hasAnyRole(['nursing_instructor', 'clinical_instructor'])) {
            $staff = $user->staff;
            if (!$staff) return false;

            $placement = \Modules\Nursing\Models\Placement::where('id', $logbook->placement_id)
                ->where('instructor_id', $staff->id)
                ->exists();
            return $placement;
        }

        return $user->hasPermission('nursing.logbooks.view');
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['student'])
            || $user->hasPermission('nursing.logbooks.create');
    }

    public function update(User $user, LogbookEntry $logbook): bool
    {
        if (!$logbook->isEditable()) {
            return false;
        }

        if ($user->hasAnyRole(['student'])) {
            $student = $user->academicStudent;
            return $student && $logbook->student_id === $student->id;
        }

        return false;
    }

    public function delete(User $user, LogbookEntry $logbook): bool
    {
        if ($logbook->status !== 'draft') {
            return false;
        }

        if ($user->hasAnyRole(['student'])) {
            $student = $user->academicStudent;
            return $student && $logbook->student_id === $student->id;
        }

        return false;
    }

    public function review(User $user, LogbookEntry $logbook): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin', 'system_administrator'])) {
            return true;
        }

        if ($user->hasAnyRole(['nursing_instructor', 'clinical_instructor'])) {
            $staff = $user->staff;
            if (!$staff) return false;

            $placement = \Modules\Nursing\Models\Placement::where('id', $logbook->placement_id)
                ->where('instructor_id', $staff->id)
                ->exists();
            return $placement;
        }

        return $user->hasPermission('nursing.logbooks.review');
    }
}
