<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AuthorizesAdmins;
use App\Models\Modules\Library\Models\Book;

class BookPolicy
{
    use AuthorizesAdmins;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('book.view')
            || $this->hasRole($user, ['librarian', 'library_manager']);
    }

    public function view(User $user, Book $book): bool
    {
        if ($book->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('book.view')
            || $this->hasRole($user, ['librarian', 'library_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('book.create')
            || $this->hasRole($user, ['librarian', 'library_manager']);
    }

    public function update(User $user, Book $book): bool
    {
        if ($book->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('book.edit')
            || $this->hasRole($user, ['librarian', 'library_manager']);
    }

    public function delete(User $user, Book $book): bool
    {
        if ($book->school_id !== $user->school_id) {
            return false;
        }

        return $user->hasPermission('book.delete')
            || $this->hasRole($user, ['library_manager']);
    }
}
