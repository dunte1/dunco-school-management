<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use Modules\Transport\Models\Vehicle;

class VehiclePolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('transport.view')
            || $this->hasRole($user, ['transport_manager', 'driver']);
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        if ($vehicle->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('transport.view')
            || $this->hasRole($user, ['transport_manager', 'driver']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('transport.create')
            || $this->hasRole($user, ['transport_manager']);
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        if ($vehicle->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('transport.edit')
            || $this->hasRole($user, ['transport_manager']);
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        if ($vehicle->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('transport.delete')
            || $this->hasRole($user, ['transport_manager']);
    }
}
