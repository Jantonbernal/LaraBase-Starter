<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermission('rol.listar')
            ? Response::allow()
            : Response::deny('No tienes permiso para acceder al módulo de roles.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): Response
    {
        return $user->hasPermission('rol.ver')
            ? Response::allow()
            : Response::deny('No tienes permiso para ver roles.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermission('rol.crear')
            ? Response::allow()
            : Response::deny('No tienes permiso para registrar roles.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): Response
    {
        return $user->hasPermission('rol.editar')
            ? Response::allow()
            : Response::deny('No tienes permiso para actualizar roles.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): Response
    {
        return $user->hasPermission('rol.eliminar')
            ? Response::allow()
            : Response::deny('No tienes permiso para eliminar roles.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return false;
    }
}
