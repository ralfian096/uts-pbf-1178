<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\API\BaseAPI;

class Read extends BaseAPI
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
        $data = $dbRepo->get();

        return $this->successResponse('Success to get data', $data);
    }
}
