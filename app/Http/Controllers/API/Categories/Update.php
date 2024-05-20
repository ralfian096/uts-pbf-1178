<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\BaseAPI;
use App\Models\ModelCategories;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class Update extends BaseAPI
{
    public function index($id = null, Request $request, Response $response)
    {
        $data = $request->all();

        // Validasi data
        $validator = $this->validateData($data);

        if ($validator->fails()) {
            return $this->sendErrorResponse('payload tidak valid', $validator->error(), 400);
        }

        // Kalau validasi berhasil
        return $this->updateCategory($id, $data);
    }

    protected function updateCategory($id = null, array $data)
    {
        // Check id
        $find = ModelCategories::find($id);

        if (!$find) {
            return $this->sendErrorResponse(...['message' => 'id tidak ditemukan', 'statusCode' => 404]);
        }

        // Update data
        try {
            $find->update([
                'name' => $data['name'],
            ]);

            return $this->sendSuccessResponse('update kategori berhasil');
        } catch (QueryException $e) {

            return $this->sendErrorResponse(...['message' => 'kesalahan pada server. gagal update data', 'statusCode' => 500]);
        }
    }

    protected function validateData(array $data)
    {
        return Validator::make($data, [
            'name' => 'string|max:255',
        ]);
    }
}
