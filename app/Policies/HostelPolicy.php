<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use Modules\Hostel\Models\Hostel;

class HostelPolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('hostel.view')
            || $this->hasRole($user, ['hostel_warden', 'hostel_manager']);
    }

    public function view(User $user, Hostel $hostel): bool
    {
        if ($hostel->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('hostel.view')
            || $this->hasRole($user, ['hostel_warden', 'hostel_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('hostel.create')
            || $this->hasRole($user, ['hostel_manager']);
    }

    public function update(User $user, Hostel $hostel): bool
    {
        if ($hostel->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('hostel.edit')
            || $this->hasRole($user, ['hostel_manager']);
    }

    public function delete(User $user, Hostel $hostel): bool
    {
        if ($hostel->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('hostel.delete')
            || $this->hasRole($user, ['hostel_manager']);
    }
}
