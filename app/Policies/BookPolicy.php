<?php

namespace App\Policies;

use App\Models\Modules\Library\Models\Book;
use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;

class BookPolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Book $book): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->hasRole($user, ['librarian', 'library_manager']);
    }

    public function update(User $user, Book $book): bool
    {
        return $this->hasRole($user, ['librarian', 'library_manager']);
    }

    public function delete(User $user, Book $book): bool
    {
        return $this->hasRole($user, ['librarian', 'library_manager']);
    }
}
