@extends('layouts.app')

@section('title', __('messages.add_company'))

@section('content')
<div class="container">
    <h1>{{ __('messages.add_new_company') }}</h1>

    {{-- Global Errors --}}
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
            ['name' => 'name', 'type' => 'text', 'required' => true, 'label' => 'name'],
            ['name' => 'email', 'type' => 'email', 'required' => false, 'label' => 'email'],
            ['name' => 'website', 'type' => 'text', 'required' => false, 'label' => 'website'],
            ['name' => 'file', 'type' => 'file', 'required' => false, 'label' => 'logo'],
        ];
    @endphp

    <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @foreach ($fields as $field)
            <div class="form-group mb-3">
                <label>{{ __('messages.' . $field['label']) }}</label>

                @if ($field['type'] === 'file')
                    <input type="file" 
                           name="{{ $field['name'] }}" 
                           class="form-control @error($field['name']) is-invalid @enderror">
                    @error($field['name'])
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                @else
                    <input type="{{ $field['type'] }}" 
                           name="{{ $field['name'] }}" 
                           class="form-control @error($field['name']) is-invalid @enderror"
                           value="{{ old($field['name']) }}"
                           @if($field['required']) required @endif>
                    @error($field['name'])
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                @endif
            </div>
        @endforeach

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