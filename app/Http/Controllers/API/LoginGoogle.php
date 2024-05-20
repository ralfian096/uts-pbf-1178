<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Session;

class LoginGoogle extends Login
{
    public function authenticateGoogle()
    {
        Session::forget('state');
        Session::forget('code');
        Session::forget('oauth_state');

        return Socialite::driver('google')->redirect();
    }

    public function handleCallback()
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
            return $this->successResponse('login sukses', [
                'token' => $token
            ]);
        } catch (Exception $e) {

            return $this->errorResponse(...[
                'message' => 'tidak bisa membuat token',
                'statusCode' => 500
            ]);
        }
    }
}
