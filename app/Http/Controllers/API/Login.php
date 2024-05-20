<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class Login extends BaseAPI
{
    public function index(Request $request, Response $response)
    {
        $data = $request->all();

        // Validasi data
        $validator = $this->validateData($data);

        if ($validator->fails()) {
            return $this->sendErrorResponse('payload tidak valid', $validator->error(), 400);
        }

        // Kalau validasi berhasil
        return $this->login($data);
    }

    protected function login(array $data)
    {
        // Mencoba login
        try {

            // Kalau login gagal
            if (!$token = JWTAuth::attempt($data)) {
                return $this->sendErrorResponse(...['message' => 'email atau password salah', 'statusCode' => 401]);
            }
        } catch (JWTException $e) {

            // Kalau gagal buat JWT
            return $this->sendErrorResponse(...['message' => 'tidak bisa membuat token', 'statusCode' => 500]);
        }

        // Kalau login berhasil
        return $this->sendSuccessResponse(
            'login sukses',
            [
                'token' => $token
            ]
        );
    }

    protected function validateData(array $data)
    {
        return Validator::make($data, [
            'email' => 'required',
            'password' => 'required',
        ]);
    }
}
