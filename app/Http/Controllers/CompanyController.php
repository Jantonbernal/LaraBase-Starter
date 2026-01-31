<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Loggable;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class CompanyController extends Controller
{
    use Loggable;

    public function show()
    {
        Gate::authorize('viewAny', Company::class);

        $company = Company::with('logo')->first();
        return (new CompanyResource($company))->response();
    }

    public function update(CompanyRequest $request, Company $company)
    {
        Gate::authorize('update', $company);

        DB::beginTransaction();

        try {
            $company->update($request->validated());
            DB::commit();

            return (new CompanyResource($company->load('logo')))->response();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->registerLog('error', 'Error al actualizar empresa', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Error interno'], 500);
        }
    }
}
