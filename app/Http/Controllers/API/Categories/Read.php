<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\API\BaseAPI;

class Read extends BaseAPI
{
    protected $payloadRules = [];

    public function main()
    {
        $dbRepo = new DBRepo();
        $data = $dbRepo->get();

        return $this->successResponse('Success to get data', $data);
    }
}
