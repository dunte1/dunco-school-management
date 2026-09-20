<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use Modules\HR\Models\Staff;

class StaffPolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('staff.view')
            || $this->hasRole($user, ['hr_manager']);
    }

    public function view(User $user, Staff $staff): bool
    {
        if ($staff->school_id !== $user->school_id) {
            return false;
        }

        if ($user->hasPermission('staff.view')) {
            return true;
        }

        if ($this->hasRole($user, ['hr_manager'])) {
            return true;
        }

        return ($staff->user_id ?? null) === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('staff.create')
            || $this->hasRole($user, ['hr_manager']);
    }

    public function update(User $user, Staff $staff): bool
    {
        if ($staff->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('staff.edit')
            || $this->hasRole($user, ['hr_manager']);
    }

    public function delete(User $user, Staff $staff): bool
    {
        if ($staff->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('staff.delete')
            || $this->hasRole($user, ['hr_manager']);
    }
}
