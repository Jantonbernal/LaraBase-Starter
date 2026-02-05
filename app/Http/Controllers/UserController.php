<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\UserRequest;
use App\Http\Requests\VerifyCode;
use App\Http\Resources\UserResource;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Services\FileUploadService;
use App\Traits\HandlesStatus;
use App\Traits\Loggable;
use App\Traits\Paginatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    use Loggable, Paginatable, HandlesStatus;

    public function index(Request $request)
    {
        Gate::authorize('viewAny', User::class);

        $response = User::with(['photo'])
            ->whereAny([
                'code',
                'name',
                'last_name',
                'email',
                'phone',
                'status',
            ], 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->getPerPage($request));

        return UserResource::collection($response)->response();
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);

        $user->load(['photo', 'roles']);

        return (new UserResource($user))->response();
    }

    public function store(UserRequest $request)
    {
        Gate::authorize('create', User::class);

        DB::beginTransaction();

        try {
            $fileId = null;

            // Si se envía un nuevo archivo, se sube y se obtiene su ID
            if ($request->hasFile('file')) {
                $service = resolve(FileUploadService::class);

                $uploadedFile = $service->uploadSingleFile($request->file('file'), 'users');
                $fileId = $uploadedFile->id;
            }

            $user = User::create([
                'name'      => $request['name'],
                'last_name' => $request['last_name'],
                'email'     => $request['email'],
                'password'  => $request['password'],
                'phone'     => $request['phone'] ?? null,
                'file_id'   => $fileId ?? null,
            ]);

            // Asignar roles
            $user->roles()->sync($request->roles);

            DB::commit();

            $user->load(['photo', 'roles', 'permissions']);

            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'data'    => $user
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al crear el usuario', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Error al crear el usuario ',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        DB::beginTransaction();

        try {
            $fileId = $user->file_id;

            // Si se envía un nuevo archivo, se sube y se obtiene su ID
            if ($request->hasFile('file')) {
                $service = resolve(FileUploadService::class);

                $uploadedFile = $service->uploadSingleFile($request->file('file'), 'users');
                $fileId = $uploadedFile->id;
            }

            $user->update([
                'name'      => $request['name']     ?? $user->name,
                'last_name' => $request['last_name'] ?? $user->last_name,
                'email'     => $request['email']    ?? $user->email,
                'password'  => isset($request['password']) ? $request['password'] : $user->password,
                'phone'     => $request['phone']    ?? $user->phone,
                'file_id'   => $fileId,
                'status'    => $request['status']   ?? $user->status,
            ]);

            // Asignar roles
            $user->roles()->sync($request->roles);

            DB::commit();

            $user->load(['photo']);

            return response()->json([
                'message' => 'Usuario actualizado exitosamente',
                'data'    => new UserResource($user)
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al actualizar el usuario', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Error al actualizar el usuario ',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function toggle(User $user)
    {
        Gate::authorize('delete', $user);

        // Pasamos el modelo y el nombre amigable para el mensaje
        return $this->respondWithStatus($user, 'El estado del usuario ha cambiado a: ');
    }

    public function verifyCode(VerifyCode $request)
    {
        try {
            // Verificar código de seguridad
            $user = User::where([
                ['email', $request->email],
                ['verification_code', $request->code],
                ['status', Status::ACTIVE],
            ])->first();

            if (!$user) {
                return response()->json(['message' => "Parece que el código de verificación ingresado no es correcto. Por favor, verifica el código enviado a tu correo electrónico y vuelve a intentarlo."], 500);
            }

            // Actualizó la nueva contraseña y seteo a vacío el código de verificación
            $user->status = Status::ACTIVE;
            $user->verification_code = null;

            if ($user->save()) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Cuenta activada exitosamente! Ahora puedes iniciar sesión.',
                ], 201);
            }
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al verificar el código', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    public function assignPermissions(Request $request, User $user)
    {
        Gate::authorize('assignPermissions', $user);

        DB::beginTransaction();

        try {
            $user->permissions()->sync($request->permissions);

            DB::commit();
            return response()->json([
                'message'   => 'Permisos asignados exitosamente',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al asignar permiso', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    public function allAccess(Request $request)
    {
        // 1. Cargamos al usuario UNA SOLA VEZ con sus relaciones ya filtradas
        $user = $request->user(); // Ya tenemos al usuario autenticado

        // 2. Obtener permisos directos del usuario
        $directPermissions = $user->permissions()
            ->where('status', Status::ACTIVE)
            ->get(['id', 'slug']);

        // 3. Determinar el Rol Seleccionado
        $role = null;
        if ($request->filled('roleSelected')) {
            // Si el usuario seleccionó uno, buscamos ese
            $role = Role::where('status', Status::ACTIVE)->find($request->roleSelected);
        } else {
            // Si no hay selección, tomamos el primero activo del usuario
            $role = $user->roles()
                ->where('status', Status::ACTIVE)
                ->orderBy('name')
                ->first();
        }

        // 4. Obtener permisos del Rol (si existe)
        $rolePermissions = $role
            ? $role->permissions()->where('status', Status::ACTIVE)->get(['id', 'slug'])
            : collect();

        // 5. Unificar permisos y extraer IDs y Slugs
        $allPermissionsCollection = $rolePermissions->merge($directPermissions)->unique('id');

        $allPermissionsIds = $allPermissionsCollection->pluck('id');
        $allPermissionsSlugs = $allPermissionsCollection->pluck('slug');

        // 6. Obtener todos los roles activos del usuario
        $roles = $user->roles()->where('status', Status::ACTIVE)->orderBy('name')->get();

        // 7. Cargar Menús con filtrado eficiente
        // Usamos 'whereHas' para que el menú principal solo aparezca si tiene hijos con permiso
        $menus = Menu::with(['allChildrenMenus' => function ($query) use ($allPermissionsIds) {
            $query->whereIn('permission_id', $allPermissionsIds)
                ->with('permission'); // Eager loading del permiso del hijo
        }])
            ->where('hierarchy', 1)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'allPermissions' => $allPermissionsSlugs,
            'allRoles'       => $roles,
            'role'           => $role,
            'allMenus'       => $menus,
        ]);
    }
}
