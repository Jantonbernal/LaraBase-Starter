<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Http\Resources\CompanyResource;
use App\Services\FileUploadService;
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
            $fileId = $company->file_id;

            // Si se envía un nuevo archivo, se sube y se obtiene su ID
            if ($request->hasFile('file')) {
                $service = resolve(FileUploadService::class);

                $uploadedFile = $service->uploadSingleFile($request->file('file'), 'company');
                $fileId = $uploadedFile->id;
            }

            $company->update([
                'business_name' =>  $request['business_name'],
                'trade_name'    =>  $request['trade_name'],
                'document'      =>  $request['document'],
                'email'         =>  $request['email'],
                'phone_number'  =>  $request['phone_number'],
                'file_id'       => $fileId,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Compañia actualizada exitosamente',
                'data'    => new CompanyResource($company->load('logo'))
            ], 201);
        } catch (Throwable $e) {
            return $this->handleException($e, 'Error al actualizar la empresa');
        }
    }
}
