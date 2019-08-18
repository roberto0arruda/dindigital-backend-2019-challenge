<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $jwtAuth)
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
        $this->jwtAuth = $jwtAuth;
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = $this->jwtAuth->attempt($credentials) ) {
            return responder()->error('unauthorized', 'Usuário ou senha invalidos')->respond(401);
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
        if (! $user = $this->jwtAuth->parseToken()->authenticate()) {
            return responder()->error('unauthenticated', 'Acesso não autorizado')->respond(403);
        }

        return responder()->success($user)->only('id', 'name', 'email')->respond();
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return responder()->success('message', 'Usuário deslogado com sucesso')->respond();
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
        $newToken = $this->jwtAuth->refresh($this->jwtAuth->getToken());

        return responder()->success([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in_seconds' => auth()->factory()->getTTL() * 60
        ])->respond();
    }
}
