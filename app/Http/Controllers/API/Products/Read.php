<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\BaseAPI;
use App\Models\ModelProducts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Read extends BaseAPI
{
    public function index(Request $request, Response $response)
    {
        return $this->getProducts();
    }

    protected function getProducts()
    {
        $products = (ModelProducts::all())->all();

        return $this->sendSuccessResponse('get data berhasil', $products);
    }
}
