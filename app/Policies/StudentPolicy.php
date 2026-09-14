<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use Modules\Academic\Models\Student;

class StudentPolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return $this->hasRole($user, ['teacher', 'academic_admin', 'hr_manager']);
    }

    public function view(User $user, Student $student): bool
    {
        if ($this->hasRole($user, ['teacher', 'academic_admin'])) {
            return true;
        }

        // A student may view their own record; a parent their children's.
        if (($student->user_id ?? null) === $user->id) {
            return true;
        }

        return $student->parents()->where('parent_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return false; // admin only
    }

    public function update(User $user, Student $student): bool
    {
        return false; // admin only
    }

    public function delete(User $user, Student $student): bool
    {
        return false; // admin only
    }
}
