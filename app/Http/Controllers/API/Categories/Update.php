<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\API\BaseAPI;
use App\Models\ModelCategories;

class Update extends BaseAPI
{
    protected $payloadRules = [
        'name' => 'string|max:255',
    ];

    public function main($id = null)
    {
        // Check id
        $find = ModelCategories::find($id);

        if (!$find) {
            return $this->errorResponse(...[
                'message' => 'ID not found',
                'statusCode' => 404
            ]);
        }

        $dbRepo = new DBRepo();
        $update = $dbRepo->update($id, $this->payload);

        if (!$update->status) {
            return $this->errorResponse('Server error. Failed to update data');
        }

        // Kalau validasi berhasil
        return $this->successResponse('Success update data');
    }
}
