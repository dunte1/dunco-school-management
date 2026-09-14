<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use Modules\Communication\Models\Message;

class MessagePolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Message $message): bool
    {
        return $message->sender_id === $user->id
            || $message->recipients()->where('recipient_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Message $message): bool
    {
        return $message->sender_id === $user->id;
    }

    public function delete(User $user, Message $message): bool
    {
        return $message->sender_id === $user->id;
    }
}
