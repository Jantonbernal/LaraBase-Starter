<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Traits\HandlesStatus;
use App\Traits\Loggable;
use App\Traits\Paginatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class RoleController extends Controller
{
    use Loggable, Paginatable, HandlesStatus;

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Role::class);

        $response = Role::withCount(['permissions'])
            ->whereAny([
                'name',
            ], 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->getPerPage($request));

        return RoleResource::collection($response)->response();
    }

    /**
     * Obtener todos los roles
     */
    public function allRoles()
    {
        $response = Role::where('status', Status::ACTIVE)->orderBy('id', 'desc')->get();

        return RoleResource::collection($response)->response();
    }

    public function store(RoleRequest $request)
    {
        Gate::authorize('create', Role::class);

        DB::beginTransaction();

        try {
            $record = Role::create($request->validated());

            $record->permissions()->sync($request->permissions);

            DB::commit();
            return response()->json([
                'message'   => 'Rol creado exitosamente',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al registrar rol', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    /**
     * Obtener un rol y sus permisos asociados activos
     */
    public function show(Role $role)
    {
        Gate::authorize('view', $role);

        $record = $role->load(['permissions' => function ($query) {
            $query->where('status', Status::ACTIVE);
        }]);

        return new RoleResource($record);
    }

    public function update(RoleRequest $request, Role $role)
    {
        Gate::authorize('update', $role);

        DB::beginTransaction();

        try {
            $role->update($request->validated());

            $role->permissions()->sync($request->permissions);

            DB::commit();
            return response()->json([
                'message'   => 'Rol actualizado exitosamente',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al actualizar rol', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    public function toggle(Role $role)
    {
        Gate::authorize('delete', $role);

        // Pasamos el modelo y el nombre amigable para el mensaje
        return $this->respondWithStatus($role, 'El estado del rol ha cambiado a: ');
    }
}
