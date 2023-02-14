<?php

namespace Wepa\Core\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends \App\Http\Controllers\Controller
{
    public function login(Request $request)
    {
        if (! Auth::attempt($request->only(['email', 'password']))) {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
}
