<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginAuthRequest;
use App\Http\Requests\Api\V1\RegisterAuthRequest;
use App\Permissinons\V1\Abilities;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Login
     *
     * Authenticates the user and returns the user's API token.
     *
     * @unauthenticated
     * @group Authentication
     * @response 200 {
     *   "data": {
     *     "token": "{YOUR_TOKEN_HERE}"
     *   },
     *    "message": "Authenticated",
     *    "status": 200
     * }
     * @response 422 {
     *   "message": "The password field is required.",
     *   "errors": {
     *     "password": [
     *       "The password field is required."
     *     ]
     *   }
     * }
     */
    public function login(LoginAuthRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }

        $user = auth()->user();

        return $this->ok(
            'Authenticated',
            [
                'token' => $user
                    ->createToken(
                        name: 'default-' . $user->email,
                        abilities: Abilities::getAbilities($user),
                        expiresAt: null
                    )
                    ->plainTextToken
            ]
        );
    }

    /**
     * Register
     *
     * Registers the user and returns the user's API token.
     *
     * @unauthenticated
     * @group Authentication
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function register(RegisterAuthRequest $request)
    {
        return $this->ok($request->get('email'));
    }

    /**
     * Logout
     *
     * Logouts the user
     *
     * @group Authentication
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('', [], 204);
    }
}
