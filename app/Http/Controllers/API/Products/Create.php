<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\API\BaseAPI;
use App\Models\ModelCategories;

class Create extends BaseAPI
{
    protected $payloadRules = [
        'name' => 'required|string|max:255',
        'price' => 'required|integer|max:99999999999',
        'category_id' => 'required',
        'expired_at' => 'required|date|date_format:Y-m-d',
        'image' => 'required|file|mimes:jpg,png,jpeg,webp',
    ];

    public function main()
    {
        $dbRepo = new DBRepo();

        // Check category_id
        $checkCategoryId = ModelCategories::where('name', 'like', "%{$this->payload['category_id']}%")->get();

        if (count($checkCategoryId) <= 0) {
            return $this->errorResponse(...[
                'message' => '"category_id" not found',
                'statusCode' => 404
            ]);
        }

        $data = $this->payload;
        $data['category_id'] = $checkCategoryId[0]['id'];

        // Save uploaded image
        $pathFile = $dbRepo->saveImage($this->request);
        $data['image'] = $pathFile;

        $product = $dbRepo->create($data);

        if (!$product->status) {
            return $this->errorResponse('Server error. Failed to insert data');
        }

        // Kalau validasi berhasil
        return $this->successResponse('Success insert data');
    }
}
