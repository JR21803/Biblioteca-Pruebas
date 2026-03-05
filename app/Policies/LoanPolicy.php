<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    /**
     * Ver préstamos.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('bibliotecario');
    }

    /**
     * Ver detalles de un préstamo.
     */
    public function view(User $user, Loan $loan): bool
    {
        return $user->hasRole('bibliotecario');
    }

    /**
     * Crear un nuevo préstamo.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['estudiante', 'docente']);
    }

    /**
     * Devolver un préstamo.
     */
    public function update(User $user, Loan $loan): bool
    {
        return $user->hasRole('bibliotecario');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Loan $loan): bool
    {
        return $user->hasRole('bibliotecario');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Loan $loan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Loan $loan): bool
    {
        return false;
    }
}
