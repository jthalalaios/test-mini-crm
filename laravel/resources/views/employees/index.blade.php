@extends('layouts.app')

@section('title', __('messages.employees'))

@section('content')
@php
    $columns = isset($columns) ? $columns : [
        'id', 'first_name', 'last_name', 'company_name', 'email', 'phone', 'created_at', 'actions'
    ];
    $title = __('messages.employees');
    $tableId = 'employees-table';
    $ajaxUrl = route('employees.index');
    $columnTranslations = [
        'id' => __('messages.id'),
        'first_name' => __('messages.first_name'),
        'last_name' => __('messages.last_name'),
        'company_name' => __('messages.company'),
        'email' => __('messages.email'),
        'phone' => __('messages.phone'),
        'created_at' => __('messages.created_at'),
        'actions' => __('messages.actions'),
    ];
    $model = \App\Models\Employee::class;
@endphp

<a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> {{ __('messages.add_new_employee_btn') }}
</a>
@include('components.datatable', compact('columns', 'title', 'tableId', 'ajaxUrl', 'columnTranslations', 'model'))
@endsection
