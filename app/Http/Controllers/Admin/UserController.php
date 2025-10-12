<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('is-admin');
        return Inertia::render('Admin/Usuarios/Index', [
            'usuarios' => User::all(),
        ]);
    }

    public function create()
    {
        Gate::authorize('is-admin');
        return Inertia::render('Admin/Usuarios/Create');
    }

    public function store(Request $request)
    {
        Gate::authorize('is-admin');
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'rol' => 'required|string|in:Administrador,Cajero,Empleado',
        ]);

        User::create([
            'nombre_completo' => $request->nombre_completo,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $usuario)
    {
        Gate::authorize('is-admin');
        return Inertia::render('Admin/Usuarios/Edit', [
            'usuario' => $usuario,
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        Gate::authorize('is-admin');
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'rol' => 'required|string|in:Administrador,Cajero,Empleado',
        ]);

        $usuario->nombre_completo = $request->nombre_completo;
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;

        if ($request->password) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $usuario)
    {
        Gate::authorize('is-admin');
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }
        $usuario->delete();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
