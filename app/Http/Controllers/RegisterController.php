<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class RegisterController extends Controller
{
      /**
    * @OA\Post(
    *     path="/register",
    *     summary="User registration",
    *     tags={"Registration"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"name","email","password"},
    *             @OA\Property(property="name", type="string", format="name", example="John Doe"),
    *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
    *             @OA\Property(property="password", type="string", format="password", example="password123"),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Usuario registrado exitosamente"),
    *             @OA\Property(property="accessToken", type="string"),
    *             @OA\Property(property="token_type", type="string", example="Bearer"),
    *             @OA\Property(property="user", type="object"),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Invalid input",
    *     ),
    * )
    */
    public function register(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        // Crear el usuario
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Generar token de acceso
        $accessToken = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'accesToken' => $accessToken,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
}
