@extends('layouts.app')

@section('title', __('messages.employees'))

@section('content')
<div class="container">
    <h1 class="mb-4">{{ __('messages.employees') }}</h1>
    <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">{{ __('messages.add_new_employee_btn') }}</a>

    <table class="table table-bordered" id="employees-table">
        <thead>
            <tr>
                <th>{{ __('messages.first_name') }}</th>
                <th>{{ __('messages.last_name') }}</th>
                <th>{{ __('messages.company') }}</th>
                <th>{{ __('messages.email') }}</th>
                <th>{{ __('messages.phone') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->first_name }}</td>
                <td>{{ $employee->last_name }}</td>
                <td>
                    @if($employee->company)
                        <a href="{{ route('companies.index', $employee->company->id) }}">
                            {{ $employee->company->name }}
                        </a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->phone }}</td>
                <td>
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">{{ __('messages.edit') }}</a>
                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.delete_confirm') }}')">
                            {{ __('messages.delete') }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Laravel pagination links (optional, can remove if using DataTables client-side) --}}
    <div class="mt-3">
        {{ $employees->links() }}
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.3em 0.8em;
        margin-left: 2px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
    let locale = "{{ app()->getLocale() }}";
    let langUrl = '';

    switch(locale) {
        case 'el':
        case 'gr':
            langUrl = '//cdn.datatables.net/plug-ins/1.13.6/i18n/el.json';
            break;
        case 'en':
        default:
            langUrl = '//cdn.datatables.net/plug-ins/1.13.6/i18n/en-GB.json';
    }

    $('#employees-table').DataTable({
        paging: true,
        ordering: true,
        info: false,
        searching: true,
        lengthChange: true, // allow user to select number of records
        pageLength: 10,     // default 10 records per page
        lengthMenu: [5, 10, 25, 50], // options
        language: { url: langUrl },
        columnDefs: [
            { orderable: false, targets: 5 } // Actions column not orderable
        ]
    });
});
</script>
@endpush