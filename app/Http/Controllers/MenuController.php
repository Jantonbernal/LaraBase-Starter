<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\MenuRequest;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Traits\HandlesStatus;
use App\Traits\Loggable;
use App\Traits\Paginatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class MenuController extends Controller
{
    use Loggable, Paginatable, HandlesStatus;

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Menu::class);

        $response = Menu::with(['allChildrenMenus' => ['permission:id,name']])
            ->where('hierarchy', 1)
            ->whereAny([
                'menu',
            ], 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->getPerPage($request));

        return MenuResource::collection($response)->response();
    }

    public function allMenus()
    {
        $response = Menu::where('hierarchy', 1)->orderBy('id', 'desc')->get();

        return MenuResource::collection($response)->response();
    }

    public function allSubMenus()
    {
        $response = Menu::where('hierarchy', 2)
            ->orderBy('id', 'desc')
            ->get();

        return MenuResource::collection($response)->response();
    }

    public function allSubMenusByMenuId(Request $request, Menu $menu)
    {
        $response = Menu::with('permission:id,name')
            ->where('parent', $menu->id)
            ->where('hierarchy', 2)
            ->whereAny([
                'menu',
            ], 'LIKE', '%' . $request->search . '%')
            ->paginate(9);

        return MenuResource::collection($response)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuRequest $request)
    {
        Gate::authorize('create', Menu::class);

        DB::beginTransaction();

        try {
            Menu::create($request->validated());

            DB::commit();
            return response()->json([
                'message'   => 'Menú creado exitosamente',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al registrar menú', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        Gate::authorize('view', $menu);

        $record = Menu::where('id', $menu->id)
            ->with(['allChildrenMenus' => ['permission:id,name']])->first();

        return (new MenuResource($record))->response();
    }

    /**
     * Display the specified resource.
     */
    public function subMenuId(Menu $menu)
    {
        $record = Menu::where('id', $menu->id)->with(['permission:id,name'])->first();

        return (new MenuResource($record))->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        Gate::authorize('update', $menu);

        DB::beginTransaction();

        try {
            $menu->update($request->validated());

            // Desactivar el vinculo de los submenus con el menu principal
            // antes de asignar los nuevos submenus
            // Menu::where('parent', $menu->id)->update(['parent' => null]);

            // // Actualizar nuevos subbmenus
            // if (isset($request->menus) && sizeof($request->menus) > 0) {
            //     Menu::whereIn('id', $request->menus)->update([
            //         'parent' => $menu->id,
            //     ]);
            // }

            DB::commit();
            return response()->json([
                'message'   => 'Menú actualizado exitosamente',
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al actualizar menú', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }

    public function toggle(Menu $menu)
    {
        Gate::authorize('delete', $menu);

        // Pasamos el modelo y el nombre amigable para el mensaje
        return $this->respondWithStatus($menu, 'El estado del menú ha cambiado a: ');
    }
}
