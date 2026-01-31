<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermission('permiso.listar')
            ? Response::allow()
            : Response::deny('No tienes permiso para acceder al módulo de permiso.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Permission $permission): Response
    {
        return $user->hasPermission('permiso.ver')
            ? Response::allow()
            : Response::deny('No tienes permiso para ver permisos.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermission('permiso.crear')
            ? Response::allow()
            : Response::deny('No tienes permiso para registrar nuevos permisos.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Permission $permission): Response
    {
        return $user->hasPermission('permiso.editar')
            ? Response::allow()
            : Response::deny('No tienes permiso para actualizar permisos.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Permission $permission): Response
    {
        return $user->hasPermission('permiso.eliminar')
            ? Response::allow()
            : Response::deny('No tienes permiso para eliminar permisos.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Permission $permission): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        return false;
    }
}
