<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use Modules\Timetable\Models\Timetable;

class TimetablePolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('timetable.view')
            || $this->hasRole($user, ['timetable_manager', 'academic_admin']);
    }

    public function view(User $user, Timetable $timetable): bool
    {
        if ($timetable->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('timetable.view')
            || $this->hasRole($user, ['timetable_manager', 'academic_admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('timetable.create')
            || $this->hasRole($user, ['timetable_manager', 'academic_admin']);
    }

    public function update(User $user, Timetable $timetable): bool
    {
        if ($timetable->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('timetable.edit')
            || $this->hasRole($user, ['timetable_manager', 'academic_admin']);
    }

    public function delete(User $user, Timetable $timetable): bool
    {
        if ($timetable->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('timetable.delete')
            || $this->hasRole($user, ['timetable_manager', 'academic_admin']);
    }
}
