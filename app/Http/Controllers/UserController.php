<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index(): JsonResponse
    {
        // Obtén todos los usuarios
        $users = User::all();

        // Devuelve los usuarios como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        // Busca el usuario por ID
        $user = User::find($id);

        // Si el usuario no existe, devuelve un error
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Valida los datos del request
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        // Actualiza los datos del usuario
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }
        $user->update($validated);

        // Devuelve una respuesta de éxito con el usuario actualizado
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    public function destroy($id): JsonResponse
    {
        // Busca el usuario por ID
        $user = User::find($id);

        // Si el usuario no existe, devuelve un error
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Elimina el usuario
        $user->delete();

        // Devuelve una respuesta de éxito
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }
}
