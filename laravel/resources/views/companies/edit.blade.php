@extends('layouts.app')

@section('title', __('messages.edit_company'))

@section('content')
<div class="container">
    <h1>{{ __('messages.edit_company') }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>{{ __('messages.whoops') }}</strong> {{ __('messages.there_were_some_problems_with_your_input.') }}
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $fields = [
            ['name' => 'name', 'type' => 'text', 'required' => true, 'label' => 'name'],
            ['name' => 'email', 'type' => 'email', 'required' => false, 'label' => 'email'],
            ['name' => 'website', 'type' => 'text', 'required' => false, 'label' => 'website'],
            ['name' => 'logo', 'type' => 'file', 'required' => false, 'label' => 'logo'],
        ];
    @endphp

    <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        @foreach ($fields as $field)
            <div class="form-group mb-3">
                <label>{{ __('messages.' . $field['label']) }}</label>

                @if ($field['type'] === 'file')
                    <input type="file" 
                           name="{{ $field['name'] }}" 
                           class="form-control @error($field['name']) is-invalid @enderror">
                    @error($field['name'])
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror

                    @if($company->logo && $field['name'] === 'logo')
                        <img src="{{ asset('storage/' . $company->logo) }}" 
                             alt="{{ __('messages.logo') }}" 
                             style="max-width:100px; margin-top:10px;">
                    @endif

                @else
                    <input type="{{ $field['type'] }}" 
                           name="{{ $field['name'] }}" 
                           class="form-control @error($field['name']) is-invalid @enderror" 
                           value="{{ old($field['name'], $company->{$field['name']} ?? '') }}" 
                           @if($field['required']) required @endif>
                    @error($field['name'])
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                @endif
            </div>
        @endforeach

        <div class="form-group mt-3 d-flex">
            <button type="submit" class="btn btn-success mr-2">
                {{ __('messages.update') }}
            </button>
            <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                {{ __('messages.back') }}
            </button>
        </div> 
    </form>
</div>
@endsection