<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    private $user;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);

        $this->user = $user;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return App\Http\Resources\UserResource
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register
     *
     * @return App\Http\Resources\UserResource
     */
    public function register(UserRequest $request)
    {
        $user = $this->user->create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'email' => $request->email,
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return App\Http\Resources\UserResource
     */
    public function me()
    {
        return new UserResource(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
