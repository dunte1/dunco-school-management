<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;

class RolePolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return false; // admin only
    }

    public function view(User $user, Role $role): bool
    {
        return false; // admin only
    }

    public function create(User $user): bool
    {
        return false; // admin only
    }

    public function update(User $user, Role $role): bool
    {
        return false; // admin only
    }

    public function delete(User $user, Role $role): bool
    {
        return false; // admin only
    }
}
