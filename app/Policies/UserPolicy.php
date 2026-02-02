<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermission('usuario.listar')
            ? Response::allow()
            : Response::deny('No tienes permiso para acceder al módulo de usuario.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response
    {
        return $user->hasPermission('usuario.ver')
            ? Response::allow()
            : Response::deny('No tienes permiso para ver usuarios.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermission('usuario.crear')
            ? Response::allow()
            : Response::deny('No tienes permiso para registrar nuevos usuarios.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        return $user->hasPermission('usuario.editar')
            ? Response::allow()
            : Response::deny('No tienes permiso para actualizar usuarios.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        return $user->hasPermission('usuario.eliminar')
            ? Response::allow()
            : Response::deny('No tienes permiso para eliminar usuarios.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }

    public function assignPermissions(User $user, User $model): Response
    {
        // Validamos que tenga el permiso y, opcionalmente, 
        // que no intente asignarse permisos a sí mismo si no es admin
        return $user->hasPermission('usuario.permiso')
            ? Response::allow()
            : Response::deny('No tienes permiso para asignar permisos a otros usuarios.');
    }
}
