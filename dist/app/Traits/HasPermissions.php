<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    public function hasAnyPermission($permissions)
    {
        $userPermissions = $this->getAllPermissionNames();
        foreach ($permissions as $permission) {
            if ($userPermissions->contains($permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions($permissions)
    {
        $userPermissions = $this->getAllPermissionNames();
        foreach ($permissions as $permission) {
            if (!$userPermissions->contains($permission)) {
                return false;
            }
        }
        return true;
    }

    public function getAllPermissionNames()
    {
        $cacheKey = "user_permissions_{$this->id}";
        
        return Cache::remember($cacheKey, 300, function () { // Cache for 5 minutes
            return $this->roles()
                ->with('permissions')
                ->get()
                ->flatMap(function ($role) {
                    return $role->permissions;
                })
                ->pluck('name')
                ->unique();
        });
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function getAllRoleNames()
    {
        $cacheKey = "user_roles_{$this->id}";
        
        return Cache::remember($cacheKey, 300, function () { // Cache for 5 minutes
            return $this->roles()->pluck('name');
        });
    }

    /**
     * Clear permission cache for this user
     */
    public function clearPermissionCache()
    {
        $cacheKey = "user_permissions_{$this->id}";
        $roleCacheKey = "user_roles_{$this->id}";
        
        Cache::forget($cacheKey);
        Cache::forget($roleCacheKey);
    }
} 