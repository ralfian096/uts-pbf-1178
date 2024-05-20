<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\API\BaseAPI;
use App\Models\ModelProducts;

class Delete extends BaseAPI
{
    protected $payloadRules = [];

    public function main($id = null)
    {
        // Check id
        $find = ModelProducts::find($id);

        if (!$find) {
            return $this->errorResponse(...[
                'message' => 'ID not found',
                'statusCode' => 404
            ]);
        }

        $dbRepo = new DBRepo();

        $update = $dbRepo->delete($id);

        if (!$update->status) {
            return $this->errorResponse('Server error. Failed to delete data');
        }

        // Kalau validasi berhasil
        return $this->successResponse('Success delete data');
    }
}
