<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * Listar libros
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['bibliotecario', 'estudiante', 'docente']);
    }

    /**
     * Ver detalles de un libro
     */
    public function view(User $user, Book $book): bool
    {
        return $user->hasAnyRole(['bibliotecario', 'estudiante', 'docente']);
    }

    /**
     * Crear un nuevo libro
     */
    public function create(User $user): bool
    {
        return $user->hasRole('bibliotecario');
    }

    /**
     * Actualizar un libro 
     */
    public function update(User $user, Book $book): bool
    {
        return $user->hasRole('bibliotecario');
    }

    /**
     * Eliminar un libro
     */
    public function delete(User $user, Book $book): bool
    {
        return $user->hasRole('bibliotecario');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Book $book): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Book $book): bool
    {
        return false;
    }
}
