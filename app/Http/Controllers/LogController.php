<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogResource;
use App\Models\Log;
use App\Traits\Paginatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LogController extends Controller
{
    use Paginatable;

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Log::class);

        $search = $request->search;

        $response = Log::with('user:id,name,last_name,email,status')
            ->where(function ($query) use ($search) {
                // 1. Búsqueda por ID exacto (Si el término es numérico)
                if (is_numeric($search)) {
                    $query->where('id', $search);
                }

                // 2. Búsqueda por texto en Log (LIKE)
                $query->orWhere('message', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")

                    // 3. Búsqueda en la relación con User
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate($this->getPerPage($request));

        return LogResource::collection($response);
    }
}
