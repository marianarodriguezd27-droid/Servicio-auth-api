<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Registra un nuevo usuario en el sistema.
     */
    public function register(Request $request)
    {
        // Validamos que los datos del usuario sean obligatorios.
        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Creamos el usuario. Laravel protege la contraseña
        // aplicando el hash configurado en el modelo User.
        $usuario = User::create([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => $datos['password'],
        ]);

        // Devolvemos una respuesta JSON indicando que el registro fue exitoso.
        return response()->json([
            'mensaje' => 'Usuario registrado correctamente',
            'usuario' => $usuario->name,
        ], 201);
    }

    /**
     * Verifica las credenciales de un usuario.
     */
    public function login(Request $request)
    {
        // Validamos que se reciban el correo electrónico y la contraseña.
        $datos = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Buscamos el usuario por su correo electrónico.
        $usuario = User::where('email', $datos['email'])->first();

        // Comparamos la contraseña recibida con la contraseña almacenada.
        if (!$usuario || !Hash::check($datos['password'], $usuario->password)) {
            // Si las credenciales no coinciden, devolvemos un error.
            return response()->json([
                'mensaje' => 'Error en la autenticación',
            ], 401);
        }

        // Si las credenciales son correctas, devolvemos una respuesta satisfactoria.
        return response()->json([
            'mensaje' => 'Autenticación satisfactoria',
            'usuario' => $usuario->name,
        ], 200);
    }
}