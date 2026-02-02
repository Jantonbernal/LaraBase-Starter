<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Http\Resources\CompanyResource;
use App\Traits\Loggable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class CompanyController extends Controller
{
    use Loggable;

    public function show(Company $company)
    {
        Gate::authorize('viewAny', Company::class);

        $record = Company::with('logo')->first();
        return (new CompanyResource($record))->response();
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
