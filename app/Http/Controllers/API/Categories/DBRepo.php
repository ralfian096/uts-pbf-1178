<?php

namespace App\Http\Controllers\API\Categories;

use Illuminate\Database\QueryException;
use App\Models\ModelCategories;
use App\Http\Controllers\API\BaseAPI;

class DBRepo extends BaseAPI
{
    public function createCategory(array $data)
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

    public function getCategories()
    {
        $categories = (ModelCategories::all())->all();

        return $this->sendSuccessResponse('get data berhasil', $categories);
    }

    public function deleteCategory($id = null)
    {
        // Check id
        $find = ModelCategories::find($id);

        if (!$find) {
            return $this->sendErrorResponse(...['message' => 'id tidak ditemukan', 'statusCode' => 404]);
        }

        // Delete data
        try {
            $find->delete();

            return $this->sendSuccessResponse('delete kategori berhasil');
        } catch (QueryException $e) {

            return $this->sendErrorResponse(...['message' => 'kesalahan pada server. gagal delete data', 'statusCode' => 500]);
        }
    }

    public function updateCategory($id = null, array $data)
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
}
