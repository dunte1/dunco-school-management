<?php

namespace App\Policies;

use App\Models\User;
use Modules\Academic\Models\StudentFee;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentFeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can pay the student fee.
     *
     * @param  \App\Models\User  $user
     * @param  \Modules\Academic\Models\StudentFee  $studentFee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function pay(User $user, StudentFee $studentFee)
    {
        // Check if the user is the student
        if ($user->id === $studentFee->student->user_id) {
            return true;
        }

        // Check if the user is a parent of the student
        if ($user->hasRole('parent')) {
            return $studentFee->student->parents()->where('users.id', $user->id)->exists();
        }

        return false;
    }
}
