<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\BaseAPI;
use App\Models\ModelProducts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;

class Delete extends BaseAPI
{
    public function index($id = null, Request $request, Response $response)
    {
        return $this->deleteProduct($id);
    }

    protected function deleteProduct($id = null)
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
}
