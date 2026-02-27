<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.new_company_created') }}</title>
</head>
<body>
    <h1>{{ __('messages.new_company_created') }}</h1>
    
    <p>{{ __('messages.company_name') }}: {{ $company->name }}</p>
    <p>{{ __('messages.company_id') }}: {{ $company->id }}</p>
    <p>{{ __('messages.company_email') }}: {{ $company->email }}</p>
    
    <p>{{ __('messages.greeting') }}</p>
</body>
</html>