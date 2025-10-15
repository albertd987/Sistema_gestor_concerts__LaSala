<!DOCTYPE html>
<html lang="ca" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar-se - LaSala</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            {{-- Logo --}}
            <div class="text-center">
                <a href="/" class="text-4xl font-bold text-purple-500">LaSala</a>
                <h2 class="mt-6 text-3xl font-bold text-white">
                    Crear Compte
                </h2>
                <p class="mt-2 text-sm text-gray-400">
                    O 
                    <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300">
                        inicia sessió
                    </a>
                </p>
            </div>

            {{-- Formulari --}}
            <form class="mt-8 space-y-6" action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    {{-- Nom --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300">
                            Nom complet <span class="text-red-400">*</span>
                        </label>
                        <input id="name" 
                               name="name" 
                               type="text" 
                               value="{{ old('name') }}"
                               required 
                               class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">
                            Correu electrònic <span class="text-red-400">*</span>
                        </label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               value="{{ old('email') }}"
                               required 
                               class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300">
                            Contrasenya <span class="text-red-400">*</span>
                        </label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required 
                               class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="mt-1 text-xs text-gray-400">Mínim 8 caràcters</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmar Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300">
                            Confirmar contrasenya <span class="text-red-400">*</span>
                        </label>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               required 
                               class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    {{-- Rol (hidden, siempre artista) --}}
                    <input type="hidden" name="rol" value="artista">

                    {{-- Divisor --}}
                    <div class="pt-4 border-t border-gray-700">
                        <p class="text-sm font-medium text-gray-300 mb-3">Informació del grup/artista</p>
                    </div>

                    {{-- Nom del grup --}}
                    <div>
                        <label for="nomGrup" class="block text-sm font-medium text-gray-300">
                            Nom del grup/artista <span class="text-red-400">*</span>
                        </label>
                        <input id="nomGrup" 
                               name="nomGrup" 
                               type="text" 
                               value="{{ old('nomGrup') }}"
                               required 
                               class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('nomGrup')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gènere musical --}}
                    <div>
                        <label for="genere" class="block text-sm font-medium text-gray-300">
                            Gènere musical
                        </label>
                        <input id="genere" 
                               name="genere" 
                               type="text" 
                               value="{{ old('genere') }}"
                               placeholder="Rock, Pop, Jazz..."
                               class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('genere')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Telèfon --}}
                    <div>
                        <label for="tlf_contacte" class="block text-sm font-medium text-gray-300">
                            Telèfon de contacte
                        </label>
                        <input id="tlf_contacte" 
                               name="tlf_contacte" 
                               type="tel" 
                               value="{{ old('tlf_contacte') }}"
                               placeholder="+34 666 777 888"
                               class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @error('tlf_contacte')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-300">
                            Biografia curta
                        </label>
                        <textarea id="bio" 
                                  name="bio" 
                                  rows="3" 
                                  placeholder="Descriu el teu estil musical, influències..."
                                  class="mt-1 block w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('bio') }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                        Crear Compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>