<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use Modules\Finance\Models\Fee;

class FeePolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('fee.view')
            || $this->hasRole($user, ['finance_manager', 'accountant', 'finance_officer']);
    }

    public function view(User $user, Fee $fee): bool
    {
        if ($fee->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('fee.view')
            || $this->hasRole($user, ['finance_manager', 'accountant', 'finance_officer']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('fee.create')
            || $this->hasRole($user, ['finance_manager']);
    }

    public function update(User $user, Fee $fee): bool
    {
        if ($fee->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('fee.edit')
            || $this->hasRole($user, ['finance_manager']);
    }

    public function delete(User $user, Fee $fee): bool
    {
        if ($fee->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('fee.delete')
            || $this->hasRole($user, ['finance_manager']);
    }
}
