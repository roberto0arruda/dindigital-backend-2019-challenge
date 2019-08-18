<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials) ) {
            return responder()->error('unauthorized' , 'Usuário ou senha invalidos')->respond(401);
        }

        return responder()->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in_seconds' => auth()->factory()->getTTL() * 60
        ])->respond();
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            return responder()->success(auth()->userOrFail())->only('id', 'name', 'email')->respond();
        } catch (\Throwable $th) {
            return responder()->error('', 'Acesso não autorizado')->respond(403);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();

            return responder()->success('message', 'Usuário deslogado com sucesso')->respond();
        } catch (\Throwable $th) {
            //throw $th;
            return responder()->error('', 'Acesso não autorizado')->respond(403);
        }
    }

    /**
     * Refresh a token.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return responder()->success([
                'access_token' => auth()->refresh(),
                'token_type' => 'bearer',
                'expires_in_seconds' => auth()->factory()->getTTL() * 60
            ])->respond();
        } catch (\Throwable $th) {
            //throw $th;
            return responder()->error('', 'Acesso não autorizado')->respond(403);
        }

    }
}
