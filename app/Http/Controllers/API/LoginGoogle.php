<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Session;

class LoginGoogle extends Login
{
    public function authenticateGoogle(Request $request, Response $response)
    {
        Session::forget('state');
        Session::forget('code');
        Session::forget('oauth_state');

        return Socialite::driver('google')->redirect();
    }

    public function handleCallback(Request $request, Response $response)
    {
        $userGoogle = Socialite::driver('google')->user();

        try {

            $user = User::firstOrCreate([
                'name' => $userGoogle->getName(),
                'email' => $userGoogle->getEmail(),
                'password' => 'none'
            ]);

            $token = JWTAuth::fromUser($user);

            // Beri respon token
            return $this->sendSuccessResponse('login sukses', [
                'token' => $token
            ]);
        } catch (Exception $e) {

            return $this->sendErrorResponse(...['message' => 'tidak bisa membuat token', 'statusCode' => 500]);
        }
    }
}
