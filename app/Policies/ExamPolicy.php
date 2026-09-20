<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use Modules\Examination\Models\Exam;

class ExamPolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('exam.view')
            || $this->hasRole($user, ['teacher', 'exam_officer', 'academic_admin']);
    }

    public function view(User $user, Exam $exam): bool
    {
        if ($exam->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('exam.view')
            || $this->hasRole($user, ['teacher', 'exam_officer', 'academic_admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('exam.create')
            || $this->hasRole($user, ['teacher', 'exam_officer', 'academic_admin']);
    }

    public function update(User $user, Exam $exam): bool
    {
        if ($exam->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('exam.edit')
            || $this->hasRole($user, ['teacher', 'exam_officer', 'academic_admin']);
    }

    public function delete(User $user, Exam $exam): bool
    {
        if ($exam->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('exam.delete')
            || $this->hasRole($user, ['exam_officer', 'academic_admin']);
    }
}
