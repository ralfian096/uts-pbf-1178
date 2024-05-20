<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\API\BaseAPI;
use App\Models\ModelProducts;
use App\Models\ModelCategories;

class Update extends BaseAPI
{
    protected $payloadRules = [
        'name' => 'string|max:255',
        'price' => 'integer|max:99999999999',
        'expired_at' => 'date|date_format:Y-m-d',
        'image' => 'file|mimes:jpg,png,jpeg,webp',
    ];

    public function main($id = null)
    {
        $dbRepo = new DBRepo();

        $data = $this->payload;

        // Cek product id
        $find = ModelProducts::find($id);

        if (!$find) {
            return $this->errorResponse(...[
                'message' => 'ID not found',
                'statusCode' => 404
            ]);
        }

        if (isset($data['category_id'])) {
            // Check category_id
            $checkCategoryId = ModelCategories::where('name', 'like', "%{$data['category_id']}%")->get();

            if (count($checkCategoryId) <= 0) {
                return $this->errorResponse(...[
                    'message' => '"category_id" not found',
                    'statusCode' => 404
                ]);
            }

            $data['category_id'] = $checkCategoryId[0]['id'];
        }


        // Save image
        if (isset($data['image'])) {
            $data['image'] = $this->request->file('image')->store('public');
        }

        $update = $dbRepo->update($id, $data);

        if (!$update->status) {
            return $this->errorResponse('Server error. Failed to update data');
        }

        return $this->successResponse('Success update data');
    }
}
