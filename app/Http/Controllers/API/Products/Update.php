<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\BaseAPI;
use App\Models\ModelCategories;
use App\Models\ModelProducts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        if (isset($data['image'])) {
            $data['image'] = $request->file('image')->store('public');
        }

        return $this->updateProduct($id, $data);
    }

    protected function updateProduct($id = null, array $data)
    {
        // Cek id
        $find = ModelProducts::find($id);

        if (!$find) {
            return $this->sendErrorResponse(...['message' => 'id tidak ditemukan', 'statusCode' => 404]);
        }

        // Ambil email dari JWT
        $client = JWTAuth::parseToken()->authenticate();

        if (isset($data['category_id'])) {
            // Check category_id
            $checkCategoryId = ModelCategories::where('name', 'like', "%{$data['category_id']}%")->get();

            if (count($checkCategoryId) <= 0) {
                return $this->sendErrorResponse(...['message' => '"category_id" tidak tersedia', 'statusCode' => 404]);
            }

            $data['category_id'] = $checkCategoryId[0]['id'];
        }

        $data['modified_by'] = $client->email;

        // Mencoba meng-update data
        try {
            $find->update($data);

            return $this->sendSuccessResponse('update produk berhasil');
        } catch (QueryException $e) {

            return $this->sendErrorResponse('kesalahan pada server. gagal update data', [$e->getMessage()], 500);
        }
    }

    protected function validateData(array $data)
    {
        return Validator::make($data, [
            'name' => 'string|max:255',
            'price' => 'integer|max:99999999999',
            'expired_at' => 'date|date_format:Y-m-d',
            'image' => 'file|mimes:jpg,png,jpeg,webp',
        ]);
    }
}
