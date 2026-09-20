<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use Modules\Finance\Models\Payment;

class PaymentPolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('payment.view')
            || $this->hasRole($user, ['finance_manager', 'accountant', 'finance_officer']);
    }

    public function view(User $user, Payment $payment): bool
    {
        if ($payment->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('payment.view')
            || $this->hasRole($user, ['finance_manager', 'accountant', 'finance_officer']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('payment.create')
            || $this->hasRole($user, ['finance_manager', 'accountant', 'finance_officer']);
    }

    public function update(User $user, Payment $payment): bool
    {
        if ($payment->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('payment.edit')
            || $this->hasRole($user, ['finance_manager']);
    }

    public function delete(User $user, Payment $payment): bool
    {
        if ($payment->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('payment.delete')
            || $this->hasRole($user, ['finance_manager']);
    }
}
