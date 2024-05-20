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
        // Simpan upload image
        $pathFile = $this->saveImage($request);
        $data['image'] = $pathFile;

        return $this->createProduct($data);
    }

    protected function createProduct(array $data)
    {
        // Ambil email dari JWT
        $client = JWTAuth::parseToken()->authenticate();

        // Check category_id
        $checkCategoryId = ModelCategories::where('name', 'like', "%{$data['category_id']}%")->get();

        if (count($checkCategoryId) <= 0) {
            return $this->sendErrorResponse(...['message' => '"category_id" tidak tersedia', 'statusCode' => 404]);
        }

        $data['category_id'] = $checkCategoryId[0]['id'];

        // Mencoba meng-insert data
        try {
            ModelProducts::insert([
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'price' => $data['price'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'expired_at' => $data['expired_at'],
                'modified_by' => $client->email
            ]);

            return $this->sendSuccessResponse('insert produk berhasil');
        } catch (QueryException $e) {

            return $this->sendErrorResponse('kesalahan pada server. gagal insert data', [$e->getMessage()], 500);
        }
    }

    protected function saveImage(Request $request)
    {
        return $request->file('image')->store('public');
    }

    protected function validateData(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|max:99999999999',
            'category_id' => 'required',
            'expired_at' => 'required|date|date_format:Y-m-d',
            'image' => 'required|file|mimes:jpg,png,jpeg,webp',
        ]);
    }
}
