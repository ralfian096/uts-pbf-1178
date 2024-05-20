<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\API\BaseAPI;

class Create extends BaseAPI
{
    protected $payloadRules = [
        'name' => 'required|string|max:255',
    ];

    public function main()
    {
        $dbRepo = new DBRepo();

        $category = $dbRepo->create($this->payload);

        if (!$category->status) {
            return $this->errorResponse('Server error. Failed to insert data');
        }

        // Kalau validasi berhasil
        return $this->successResponse('Success insert data');
    }
}
