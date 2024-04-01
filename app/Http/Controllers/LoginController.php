<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
     /**
    * @OA\Post(
    *     path="/login",
    *     summary="User login",
    *     tags={"Login"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"email","password"},
    *             @OA\Property(property="email", type="string"),
    *             @OA\Property(property="password", type="string"),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string"),
    *             @OA\Property(property="accessToken", type="string"),
    *             @OA\Property(property="token_type", type="string"),
    *             @OA\Property(property="user", type="object"),
    *         ),
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized",
    *     ),
    * )
    */
    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('email','password')))
        {
            // Si la autenticación falla, devuelve un error 401
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        // Si la autenticación es exitosa, busca al usuario en la base de datos
        $user = User::where('email',$request['email'])->firstOrFail();

        // Crea un token de acceso para el usuario
        $token = $user->createToken('auth_token')->accessToken;

        // Devuelve el token de acceso en la respuesta
        return response()
            ->json([
                'message' => 'Hola '.$user->name,
                'accessToken' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
    }
}
