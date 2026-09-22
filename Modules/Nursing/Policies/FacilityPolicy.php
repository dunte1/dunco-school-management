<?php

namespace Modules\Nursing\Policies;

use App\Models\User;
use Modules\Nursing\Models\Facility;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacilityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin', 'system_administrator', 'nursing_admin', 'nursing_instructor'])
            || $user->hasPermission('nursing.placements.view');
    }

    public function view(User $user, Facility $facility): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin', 'system_administrator'])) {
            return true;
        }

        return $facility->school_id === $user->school_id
            && ($user->hasPermission('nursing.placements.view') || $user->hasAnyRole(['nursing_admin', 'nursing_instructor']));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin', 'system_administrator', 'nursing_admin'])
            || $user->hasPermission('nursing.placements.create');
    }

    public function update(User $user, Facility $facility): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin', 'system_administrator'])) {
            return true;
        }

        return $facility->school_id === $user->school_id
            && ($user->hasPermission('nursing.placements.edit') || $user->hasAnyRole(['nursing_admin']));
    }

    public function delete(User $user, Facility $facility): bool
    {
        if ($user->hasAnyRole(['admin', 'super_admin', 'system_administrator'])) {
            return true;
        }

        return $facility->school_id === $user->school_id
            && ($user->hasPermission('nursing.placements.delete') || $user->hasAnyRole(['nursing_admin']));
    }
}
