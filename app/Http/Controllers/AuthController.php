<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Artista;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Mostrar formulari de login
     */
    public function showLogin()
    {
        if (auth()->check()) {
            return $this->redirectBasedOnRole(auth()->user());
        }

        return view('auth.login');
    }

    /**
     * Processar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'L\'email és obligatori',
            'email.email' => 'Format d\'email invàlid',
            'password.required' => 'La contrasenya és obligatòria',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return $this->redirectBasedOnRole(auth()->user());
        }

        return back()->withErrors([
            'email' => 'Les credencials no són correctes',
        ])->onlyInput('email');
    }

    /**
     * Mostrar formulari de registre
     */
    public function showRegister()
    {
        if (auth()->check()) {
            return $this->redirectBasedOnRole(auth()->user());
        }

        return view('auth.register');
    }

    /**
     * Processar registre
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'rol' => 'required|in:artista',
            // Camps d'artista (només si rol = artista)
            'nomGrup' => 'required_if:rol,artista|string|max:255',
            'genere' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'tlf_contacte' => 'nullable|string|max:20',
        ], [
            'name.required' => 'El nom és obligatori',
            'email.required' => 'L\'email és obligatori',
            'email.unique' => 'Aquest email ja està registrat',
            'password.required' => 'La contrasenya és obligatòria',
            'password.confirmed' => 'Les contrasenyes no coincideixen',
            'password.min' => 'La contrasenya ha de tenir almenys 8 caràcters',
            'nomGrup.required_if' => 'El nom del grup és obligatori per artistes',
        ]);

        // Crear usuari i artista en una transacció
        DB::transaction(function () use ($validated, $request) {
            // Crear usuari
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'rol' => UserRole::from($validated['rol']),
            ]);

            // Si és artista, crear perfil d'artista
            if ($validated['rol'] === 'artista') {
                Artista::create([
                    'id_usuari' => $user->id,
                    'nomGrup' => $validated['nomGrup'],
                    'genere' => $validated['genere'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                    'tlf_contacte' => $validated['tlf_contacte'] ?? null,
                    'links_socials' => [],
                ]);
            }

            // Iniciar sessió automàticament
            Auth::login($user);
        });

        return $this->redirectBasedOnRole(auth()->user());
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Sessió tancada correctament');
    }

    /**
     * Redirigir segons el rol de l'usuari
     */
    private function redirectBasedOnRole(User $user)
    {
        return match ($user->rol->value) {
            'admin' => redirect('/admin/reserves'),
            'artista' => redirect('/artista/dashboard'),
            default => redirect('/'),
        };
    }
}