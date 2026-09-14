<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;

class UserPolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return false; // admin only (handled by before())
    }

    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return false; // admin only
    }

    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return false; // admin only
    }
}
