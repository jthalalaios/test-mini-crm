@extends('layouts.app')

@section('title', __('messages.companies'))

@section('content')
@php
    $columns = isset($columns) ? $columns : [
        'id', 'name', 'email', 'website', 'display_logo', 'created_at', 'actions'
    ];
    $columnTranslations = [
        'id' => __('messages.id'),
        'name' => __('messages.name'),
        'email' => __('messages.email'),
        'website' => __('messages.website'),
        'display_logo' => __('messages.display_logo'),
        'created_at' => __('messages.created_at'),
        'actions' => __('messages.actions'),
    ];
    $title = __('messages.companies');
    $tableId = 'companies-table';
    $ajaxUrl = route('companies.index');
    $model = \App\Models\Company::class;
@endphp


<a href="{{ route('companies.create') }}" class="btn btn-primary mb-3">
    <i class="fas fa-plus"></i> {{ __('messages.add_new_company_btn') }}
</a>
@include('components.datatable', compact('columns', 'title', 'tableId', 'ajaxUrl', 'model'))
@endsection

