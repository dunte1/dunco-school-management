<?php

namespace Modules\Nursing\Policies;

use App\Models\User;
use Modules\Nursing\Models\Placement;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlacementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin', 'system_administrator', 'nursing_admin', 'nursing_instructor'])
            || $user->hasPermission('nursing.placements.view');
    }

    public function view(User $user, Placement $placement): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin', 'system_administrator'])) {
            return true;
        }

        if ($placement->school_id !== $user->school_id) {
            return false;
        }

        if ($user->hasAnyRole(['student'])) {
            $student = $user->academicStudent;
            return $student && $placement->student_id === $student->id;
        }

        if ($user->hasAnyRole(['nursing_instructor', 'clinical_instructor'])) {
            $staff = $user->staff;
            return $staff && $placement->instructor_id === $staff->id;
        }

        return $user->hasPermission('nursing.placements.view');
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin', 'system_administrator', 'nursing_admin'])
            || $user->hasPermission('nursing.placements.create');
    }

    public function update(User $user, Placement $placement): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin', 'system_administrator'])) {
            return true;
        }

        return $placement->school_id === $user->school_id
            && ($user->hasPermission('nursing.placements.edit') || $user->hasAnyRole(['nursing_admin']));
    }

    public function delete(User $user, Placement $placement): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin', 'system_administrator'])) {
            return true;
        }

        return $placement->school_id === $user->school_id
            && ($user->hasPermission('nursing.placements.delete') || $user->hasAnyRole(['nursing_admin']));
    }
}
