@extends('layouts.app')

@section('title', __('messages.add_employee'))

@section('content')
<div class="container">
    <h1>{{ __('messages.add_employee') }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>{{ __('messages.whoops') }}</strong> {{ __('messages.there_were_some_problems_with_your_input.') }}
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $fields = [
            ['name' => 'first_name', 'type' => 'text', 'required' => true, 'label' => 'first_name'],
            ['name' => 'last_name', 'type' => 'text', 'required' => true, 'label' => 'last_name'],
            ['name' => 'email', 'type' => 'email', 'required' => true, 'label' => 'email'],
            ['name' => 'phone', 'type' => 'text', 'required' => false, 'label' => 'phone'],
        ];
    @endphp

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        @foreach ($fields as $field)
            <div class="form-group mb-3">
                <label>{{ __('messages.' . $field['label']) }}</label>
                <input type="{{ $field['type'] }}" 
                       name="{{ $field['name'] }}" 
                       class="form-control @error($field['name']) is-invalid @enderror"
                       value="{{ old($field['name']) }}"
                       @if($field['required']) required @endif>
                @error($field['name'])
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        {{-- Company Dropdown --}}
        <div class="form-group mb-3">
            <label>{{ __('messages.company') }}</label>
            <select name="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                <option value="">{{ __('messages.company') }}</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
            @error('company_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mt-3 d-flex">
            <button type="submit" class="btn btn-success mr-2">
                {{ __('messages.save') }}
            </button>
            <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                {{ __('messages.back') }}
            </button>
        </div>        
        
    </form>
</div>
@endsection