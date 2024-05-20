<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseAPI;
use App\Models\ModelUsers;
use Illuminate\Database\QueryException;

class Register extends BaseAPI
{
    protected $payloadRules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'password' => 'required|string|max:255',
    ];


    public function main()
    {
        $insertData = $this->payload;

        try {

            ModelUsers::insert([
                'name' => $insertData['name'],
                'email' => $insertData['email'],
                'password' => bcrypt($insertData['password']),
            ]);

            return $this->successResponse('Account registration success');
        } catch (QueryException $e) {

            if ($e->errorInfo[1] == '1062') {
                // Email terduplikasi
                return $this->errorResponse(...[
                    'message' => 'Email already registered',
                    'statusCode' => 409
                ]);
            } else {
                // Gagal insert data
                return $this->errorResponse(...[
                    'message' => 'Error on server. Failed to insert data',
                    'statusCode' => 500
                ]);
            }
        }
    }
}
