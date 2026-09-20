<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use Modules\Academic\Models\Subject;

class SubjectPolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('subject.view')
            || $this->hasRole($user, ['teacher', 'academic_admin']);
    }

    public function view(User $user, Subject $subject): bool
    {
        if ($subject->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('subject.view')
            || $this->hasRole($user, ['teacher', 'academic_admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('subject.create')
            || $this->hasRole($user, ['academic_admin']);
    }

    public function update(User $user, Subject $subject): bool
    {
        if ($subject->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('subject.edit')
            || $this->hasRole($user, ['academic_admin']);
    }

    public function delete(User $user, Subject $subject): bool
    {
        if ($subject->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('subject.delete')
            || $this->hasRole($user, ['academic_admin']);
    }
}
