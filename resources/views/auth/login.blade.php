<!DOCTYPE html>
<html lang="ca" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sessió - LaSala</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            {{-- Logo --}}
            <div class="text-center">
                <a href="/" class="text-4xl font-bold text-purple-500">LaSala</a>
                <h2 class="mt-6 text-3xl font-bold text-white">
                    Iniciar Sessió
                </h2>
                <p class="mt-2 text-sm text-gray-400">
                    O 
                    <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300">
                        crea un compte nou
                    </a>
                </p>
            </div>

            {{-- Flash Messages --}}
            @if (session('message'))
                <div class="bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Formulari --}}
            <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">
                            Correu electrònic
                        </label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               value="{{ old('email') }}"
                               required 
                               class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300">
                            Contrasenya
                        </label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required 
                               class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('password')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center">
                        <input id="remember" 
                               name="remember" 
                               type="checkbox" 
                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-700 rounded bg-gray-800">
                        <label for="remember" class="ml-2 block text-sm text-gray-300">
                            Recordar-me
                        </label>
                    </div>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                        Entrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>