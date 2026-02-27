<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Helpers\FunctionsHelper;
use App\Http\Filters\Company\FilterCompany;
use App\Http\Requests\Company\CreateRequest;
use App\Http\Requests\Company\EditRequest;
use App\Http\Requests\GeneralRequest;
use App\Models\Company;
use App\Models\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index(GeneralRequest $request)
    {
        $validated_data = $request->validated();
        [$company_query, $items] = FunctionsHelper::filters_with_sorting($validated_data, Company::class, FilterCompany::class);
        $companies = $company_query->paginate($items);

        $companies->getCollection()->transform(function ($company) {
            $company_image = File::where('foreign_id', $company->id)->where('path', '/uploads/images/company/' .$company->id)->first();
            if($company_image) $company->display_logo = $company_image?->file_path ??  null;
            return $company;
        });

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(CreateRequest $request)
    {
        $validated_data = $request->validated();
        $company = Company::create(collect($validated_data)->except('file')->toArray());
        if ($request->hasFile('file')) {
            $upload_path = Constants::FILE_PATHS['base_image_path'] . '/company/' . $company->id;

            $request->merge([
                'path'       => $upload_path,
                'foreign_id' => $company->id,
            ]);
            $file_controller = new FileController();
            $file_controller->store_image($request);
        }

        return redirect()->route('companies.index')->with('success', __('messages.company_created_successfully'));
    }

    public function edit(Company $company)
    {
        $company_image = File::where('foreign_id', $company->id)->first();
        Log::info("company_image edwww-->" .json_encode($company_image));
        if($company_image) $company->display_logo = $company_image?->file_path ?? null;
        return view('companies.edit', compact('company'));
    }

   public function update(EditRequest $request, Company $company)
    {
        $validated_data = $request->validated();
        if ($request->hasFile('logo')) {
            $fileController = new FileController();
            $upload_path = Constants::FILE_PATHS['base_image_path'] . '/company/' . $company->id;
            $request->merge([
                'path'       => $upload_path,
                'foreign_id' => $company->id,
            ]);

            $new_file = $fileController->store_image($request); 
            if ($company->logo) {
                $old_file = \App\Models\File::where('file_path', $company->logo)->where('foreign_id', $company->id)->first();
                if ($old_file) {
                    if (\Illuminate\Support\Facades\Storage::disk('custom')->exists($old_file->file_path)) {
                        \Illuminate\Support\Facades\Storage::disk('custom')->delete($old_file->file_path);
                    }
                    $old_file->forceDelete();
                }
            }
            $validated_data['logo'] = $new_file ? $new_file?->file_path : null;
        }

        $company->update(collect($validated_data)->except('file')->toArray());
        return redirect()->route('companies.index')->with('success', __('messages.company_updated_successfully'));
    }

    public function destroy(Company $company)
    {
        $company->employees()->each(function ($employee) {
            $employee->delete();
        });

        $company->forceDelete();
        return redirect()->route('companies.index')->with('success', __('messages.company_deleted_successfully'));
    }
}