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
        return $this->hasRole($user, ['teacher', 'exam_officer', 'academic_admin']);
    }

    public function view(User $user, Exam $exam): bool
    {
        return $this->hasRole($user, ['teacher', 'exam_officer', 'academic_admin']);
    }

    public function create(User $user): bool
    {
        return $this->hasRole($user, ['teacher', 'exam_officer', 'academic_admin']);
    }

    public function update(User $user, Exam $exam): bool
    {
        return $this->hasRole($user, ['teacher', 'exam_officer', 'academic_admin']);
    }

    public function delete(User $user, Exam $exam): bool
    {
        return false; // admin only
    }
}
