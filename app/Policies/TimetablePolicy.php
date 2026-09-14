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
        return true;
    }

    public function view(User $user, Timetable $timetable): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->hasRole($user, ['timetable_manager', 'academic_admin']);
    }

    public function update(User $user, Timetable $timetable): bool
    {
        return $this->hasRole($user, ['timetable_manager', 'academic_admin']);
    }

    public function delete(User $user, Timetable $timetable): bool
    {
        return $this->hasRole($user, ['timetable_manager', 'academic_admin']);
    }
}
