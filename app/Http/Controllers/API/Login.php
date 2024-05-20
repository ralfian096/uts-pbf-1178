<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseAPI;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Login extends BaseAPI
{
    protected $payloadRules = [
        'email' => 'required',
        'password' => 'required',
    ];

    /**
     * Generate JWT
     */
    public function main()
    {
        // Try to login
        try {

            // Kalau login gagal
            if (!$token = JWTAuth::attempt($this->payload)) {
                return $this->errorResponse(...[
                    'message' => 'Invalid email or password',
                    'statusCode' => 401
                ]);
            }
        } catch (JWTException $e) {

            // Kalau gagal buat JWT
            return $this->errorResponse(...[
                'message' => 'Cannot create token',
                'statusCode' => 500
            ]);
        }

        // Kalau login berhasil
        return $this->successResponse(
            'Login Success',
            [
                'token' => $token
            ]
        );
    }
}
