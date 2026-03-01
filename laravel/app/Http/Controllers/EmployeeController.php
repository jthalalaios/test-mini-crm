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
    public function index(GeneralRequest $request)
    {   
        $validated_data = $request->validated();
        [$employee_query, $items] = FunctionsHelper::filters_with_sorting($validated_data, Employee::class, FilterEmploy::class);

        if ($request->ajax()) {
            $data = $validated_data;
            // Remove per-column filters for searchable fields if global search is present
            if (isset($data['filter']['search']) && $data['filter']['search']) {
                $searchable_fields = (new \App\Models\Employee())->searchable_fields();
                foreach ($searchable_fields as $field) {
                    unset($data['filter'][$field]);
                }
            }
            $paginated = $employee_query->paginate($request->input('items', 10), ['*'], 'page', $request->input('page', 1));
            $paginated->getCollection()->transform(function ($employee) {
                $employee->company_name = optional($employee->company)->name;
                $employee->actions = view('employees.partials.actions', [
                    'edit' => route('employees.edit', $employee->id),
                    'delete' => route('employees.destroy', $employee->id),
                    'employee' => $employee
                ])->render();
                return $employee;
            });
            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $paginated->total(),
                'recordsFiltered' => $paginated->total(),
                'data' => $paginated->items(),
            ]);
        }

        $employees = $employee_query->paginate($items);
        return view('employees.index', compact('employees'));
    }


    public function create()
    {
        $companies = Company::all();
        return view('employees.create', compact('companies'));
    }


    public function store(CreateRequest $request)
    {
        $validated_data = $request->validated();
        $employee = Employee::create($validated_data);

        return redirect()->route('employees.index')
            ->with('success', __('messages.employee_created_successfully'));
    }

    public function edit(Employee $employee)
    {
        $companies = Company::all();
        return view('employees.edit', compact('employee', 'companies'));
    }

    public function update(EditRequest $request, Employee $employee)
    {
        $validated_data = $request->validated();
        $employee->update($validated_data);

        return redirect()->route('employees.index')
            ->with('success', __('messages.employee_updated_successfully'));
    }

    public function destroy(Employee $employee)
    {
        $employee->forceDelete();

        return redirect()->route('employees.index')
            ->with('success', __('messages.employee_deleted_successfully'));
    }

    public function show($id)
    {
        abort(404);
    }
}