<?php

namespace App\Http\Controllers;

use App\Helpers\FunctionsHelper;
use App\Http\Filters\Employ\FilterEmploy;
use App\Http\Requests\Employee\CreateRequest;
use App\Http\Requests\Employee\EditRequest;
use App\Http\Requests\GeneralRequest;
use App\Models\Employee;
use App\Models\Company;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GeneralRequest $request)
    {
        [$employee_query, $items] = FunctionsHelper::filters_with_sorting($request->validated(), Employee::class, FilterEmploy::class);
        $employees = $employee_query->paginate($items);
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        return view('employees.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $validated_data = $request->validated();
        $employee = Employee::create($validated_data);

        return redirect()->route('employees.index')
            ->with('success', __('messages.employee_created_successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $companies = Company::all();
        return view('employees.edit', compact('employee', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditRequest $request, Employee $employee)
    {
        $validated_data = $request->validated();
        $employee->update($validated_data);

        return redirect()->route('employees.index')
            ->with('success', __('messages.employee_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->forceDelete();

        return redirect()->route('employees.index')
            ->with('success', __('messages.employee_deleted_successfully'));
    }
}