<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogResource;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LogController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Log::class);

        $response = Log::with('user:id,name,last_name,email,status')
            ->whereAny([
                'message',
            ], 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(6);

        return LogResource::collection($response);
    }
}
