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
        return $this->hasRole($user, ['finance_manager', 'accountant', 'finance_officer']);
    }

    public function view(User $user, Payment $payment): bool
    {
        return $this->hasRole($user, ['finance_manager', 'accountant', 'finance_officer']);
    }

    public function create(User $user): bool
    {
        return $this->hasRole($user, ['finance_manager', 'accountant', 'finance_officer']);
    }

    public function update(User $user, Payment $payment): bool
    {
        return $this->hasRole($user, ['finance_manager']);
    }

    public function delete(User $user, Payment $payment): bool
    {
        return false; // admin only
    }
}
