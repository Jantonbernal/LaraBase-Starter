<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Throwable;

class PermissionController extends Controller
{
    use Loggable;

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Request::class);

        $response = Permission::whereAny([
            'slug',
            'name',
        ], 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(8);

        return PermissionResource::collection($response)->response();
    }

    /**
     * Obtener permisos que contienen 'index' o 'createOrUpdate' en el slug
     */
    public function permissionsForMenu()
    {
        $response = Permission::where('status', Status::ACTIVE)->get();
        $permissions = [];

        foreach ($response as $value) {
            // Solo permisos con 'index' o 'createOrUpdate' en el slug
            $contains = Str::contains($value->slug, ['index', 'createOrUpdate']);
            if ($contains) {
                // Si contiene, lo agregamos al array
                array_push($permissions, $value);
            }
        }

        return PermissionResource::collection($response)->response();
    }

    /**
     * Obtener todos los permisos activos
     */
    public function allPermissions()
    {
        $response = Permission::where('status', Status::ACTIVE)->get();

        return PermissionResource::collection($response)->response();
    }

    public function store(PermissionRequest $request)
    {
        Gate::authorize('create', Permission::class);

        DB::beginTransaction();

        try {
            Permission::create($request->validated());

            DB::commit();
            return response()->json([
                'message'   => 'Permiso creado exitosamente',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al registrar permiso', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    /**
     * Obtener un permiso con sus roles asociados activos
     */
    public function show(Permission $permission)
    {
        Gate::authorize('view', $permission);

        $response = Permission::where('id', $permission->id)
            ->with(['roles' => function (Builder $query) {
                $query->where('status', Status::ACTIVE);
            }])->first();

        return (new PermissionResource($response))->response();
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        Gate::authorize('update', $permission);

        DB::beginTransaction();

        try {
            $permission->update($request->validated());

            DB::commit();
            return response()->json([
                'message'   => 'Permiso actualizado exitosamente',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al actualizar permiso', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    public function toggle(Permission $permission)
    {
        Gate::authorize('delete', $permission);

        // Cambiamos el estado usando los casos del Enum
        $permission->status = $permission->status->toggle();
        $permission->save();

        return response()->json([
            'message'   => "Permiso {$permission->status->label()}",
            'data'      => $permission,
        ]);
    }
}
