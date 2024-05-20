<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\BaseAPI;
use App\Models\ModelCategories;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class Create extends BaseAPI
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
        return $this->createCategory($data);
    }

    protected function createCategory(array $data)
    {
        // Mencoba meng-insert data
        try {
            ModelCategories::insert([
                'name' => $data['name']
            ]);

            return $this->sendSuccessResponse('insert kategori berhasil');
        } catch (QueryException $e) {

            return $this->sendErrorResponse(...['message' => 'kesalahan pada server. gagal insert data', 'statusCode' => 500]);
        }
    }

    protected function validateData(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
        ]);
    }
}
