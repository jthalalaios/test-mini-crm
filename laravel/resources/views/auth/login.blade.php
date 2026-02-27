<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.login_title') }} - Mini-CRM</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-md">
        <div class="card shadow-lg">
            <div class="card-header bg-blue-600 text-white text-center font-bold text-xl">
                {{ __('messages.mini_crm_login') }}
            </div>
            <div class="card-body p-6">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="email" class="font-semibold">{{ __('messages.email') }}</label>
                        <input type="email" name="email" id="email" 
                               value="{{ old('email') }}"
                               class="form-control border-gray-300 rounded-md w-full @error('email') is-invalid @enderror" 
                               required autofocus>
                    </div>

                    <div class="form-group mb-4">
                        <label for="password" class="font-semibold">{{ __('messages.password') }}</label>
                        <input type="password" name="password" id="password" 
                               class="form-control border-gray-300 rounded-md w-full @error('password') is-invalid @enderror" 
                               required>
                    </div>

                    <div class="form-group form-check mb-4">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label class="form-check-label" for="remember">{{ __('messages.remember_me') }}</label>
                    </div>

                    <button type="submit" 
                            class="btn btn-primary w-full py-2 font-bold hover:bg-blue-700">
                        {{ __('messages.login') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS + jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>