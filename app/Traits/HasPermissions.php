<?php

namespace App\Traits;

trait HasPermissions
{
    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        return $this->roles()->whereHas('permissions', function($query) use ($permissions) {
            $query->whereIn('name', $permissions);
        })->exists();
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        $userPermissions = $this->roles->flatMap->permissions->pluck('name')->unique();
        
        return collect($permissions)->every(function($permission) use ($userPermissions) {
            return $userPermissions->contains($permission);
        });
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles()->where('name', $role)->exists();
        }
        
        return $this->roles->contains($role);
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Get all permissions for the user
     */
    public function getAllPermissions()
    {
        return $this->roles->flatMap->permissions->unique('id');
    }

    /**
     * Get all permission names for the user
     */
    public function getAllPermissionNames()
    {
        return $this->getAllPermissions()->pluck('name');
    }
} 