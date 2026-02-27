@extends('layouts.app')

@section('title', __('messages.companies'))

@section('content')
<div class="container">
    <h1 class="mb-4">{{ __('messages.companies') }}</h1>
    <a href="{{ route('companies.create') }}" class="btn btn-primary mb-3">{{ __('messages.add_new_company_btn') }}</a>

    <table class="table table-bordered" id="companies-table">
        <thead>
            <tr>
                <th>{{ __('messages.id') }}</th>
                <th>{{ __('messages.logo') }}</th>
                <th>{{ __('messages.name') }}</th>
                <th>{{ __('messages.email') }}</th>
                <th>{{ __('messages.website') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->id }}</td>

                {{-- Company Logo --}}
                <td>
                    @if($company->display_logo)
                        <img src="{{ config('app.storage_url') . '/' . $company->display_logo }}" 
                            alt="{{ $company->name }}" 
                            style="height:50px; width:auto; object-fit:contain;">
                    @else
                        <span>{{ __('messages.no_image') }}</span>
                    @endif
                </td>

                <td>{{ $company->name }}</td>
                <td>{{ $company->email }}</td>
                <td>
                    @if($company->website)
                        <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                    @endif
                </td>
                <td>
                    <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-warning">{{ __('messages.edit') }}</a>
                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" class="d-inline">
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

    {{-- Laravel pagination links --}}
    <div class="mt-3">
        {{ $companies->links() }}
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

    switch (locale) {
        case 'el':
        case 'gr':
            langUrl = '//cdn.datatables.net/plug-ins/1.13.6/i18n/el.json';
            break;
        case 'en':
        default:
            langUrl = '//cdn.datatables.net/plug-ins/1.13.6/i18n/en-GB.json';
            break;
    }

    $('#companies-table').DataTable({
        paging: true,
        ordering: true,
        info: false,
        searching: true,
        lengthChange: true, // allow changing records per page
        pageLength: 10, // default 10 records per page
        lengthMenu: [5, 10, 25, 50], // selectable options
        language: {
            url: langUrl
        },
        columnDefs: [
            { orderable: false, targets: [1, 5] } // Logo + Actions not orderable
        ]
    });
});
</script>
@endpush