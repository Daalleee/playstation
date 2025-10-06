<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="antialiased p-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-semibold mb-4">Login</h1>
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block mb-1">Password</label>
                <input type="password" name="password" required class="w-full border rounded p-2">
            </div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="remember" class="mr-2"> Ingat saya
            </label>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Masuk</button>
                <a href="{{ route('register.show') }}" class="text-blue-600">Daftar</a>
            </div>
        </form>
    </div>
 </body>
</html>


