<?php

namespace App\Policies\Concerns;

trait AuthorizesAdmins
{
    /**
     * Administrators are implicitly granted every ability.
     */
    public function before($user, string $ability)
    {
        if ($user && method_exists($user, 'hasAnyRole') && $user->hasAnyRole($this->adminRoles())) {
            return true;
        }

        return null;
    }

    /**
     * @return list<string>
     */
    protected function adminRoles(): array
    {
        return ['admin', 'super_admin', 'system_administrator'];
    }

    protected function hasRole($user, array $roles): bool
    {
        return $user
            && method_exists($user, 'hasAnyRole')
            && $user->hasAnyRole($roles);
    }
}
