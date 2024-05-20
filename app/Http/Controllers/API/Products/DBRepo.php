<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\API\BaseAPI;
use Illuminate\Database\QueryException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\ModelCategories;
use App\Models\ModelProducts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DBRepo extends BaseAPI
{
    public function createProduct(array $data)
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

    public function getProducts()
    {
        $products = (ModelProducts::all())->all();

        return $this->sendSuccessResponse('get data berhasil', $products);
    }

    public function deleteProduct($id = null)
    {
        // Check id
        $find = ModelProducts::find($id);

        if (!$find) {
            return $this->sendErrorResponse(...['message' => 'id tidak ditemukan', 'statusCode' => 404]);
        }

        // Delete data
        try {
            $find->delete();

            return $this->sendSuccessResponse('delete produk berhasil');
        } catch (QueryException $e) {

            return $this->sendErrorResponse(...[
                'message' => 'kesalahan pada server. gagal delete data',
                'statusCode' => 500
            ]);
        }
    }

    public function updateProduct($id = null, array $data)
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
}
