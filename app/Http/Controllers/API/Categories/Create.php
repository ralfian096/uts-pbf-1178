<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\API\BaseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class Create extends BaseAPI
{
    protected DBRepo $dbRepo;

    public function __construct(DBRepo $dbRepo)
    {
        $this->dbRepo = $dbRepo ?? new DBRepo();
    }

    public function index(Request $request, Response $response)
    {
        $data = $request->all();

        // Validasi data
        $validator = $this->validateData($data);

        if ($validator->fails()) {
            return $this->sendErrorResponse('payload tidak valid', $validator->error(), 400);
        }

        // Kalau validasi berhasil
        return $this->dbRepo->createCategory($data);
    }

    protected function validateData(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
        ]);
    }
}
