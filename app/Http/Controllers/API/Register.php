<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\ModelUsers;
use Illuminate\Database\QueryException;

class Register extends BaseAPI
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
        return $this->registerUser($data);
    }

    protected function registerUser(array $data)
    {
        try {

            ModelUsers::insert([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            return $this->sendSuccessResponse('registrasi akun berhasil');
        } catch (QueryException $e) {

            if ($e->errorInfo[1] == '1062') {
                // Email terduplikasi
                return $this->sendErrorResponse(...['message' => 'email sudah terdaftar', 'statusCode' => 409]);
            } else {
                // Gagal insert data
                return $this->sendErrorResponse(...['message' => 'kesalahan pada server. gagal insert data', 'statusCode' => 500]);
            }
        }
    }

    protected function validateData(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);
    }
}
