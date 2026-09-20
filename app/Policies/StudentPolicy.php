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
        return $user->hasPermission('student.view')
            || $this->hasRole($user, ['teacher', 'academic_admin']);
    }

    public function view(User $user, Student $student): bool
    {
        if ($student->school_id !== $user->school_id) {
            return false;
        }

        if ($user->hasPermission('student.view')) {
            return true;
        }

        if ($this->hasRole($user, ['teacher', 'academic_admin'])) {
            return true;
        }

        if (($student->user_id ?? null) === $user->id) {
            return true;
        }

        return $student->parents()->where('parent_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('student.create')
            || $this->hasRole($user, ['academic_admin']);
    }

    public function update(User $user, Student $student): bool
    {
        if ($student->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('student.edit')
            || $this->hasRole($user, ['academic_admin']);
    }

    public function delete(User $user, Student $student): bool
    {
        if ($student->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('student.delete')
            || $this->hasRole($user, ['academic_admin']);
    }
}
