<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\BaseAPI;
use App\Models\ModelCategories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Read extends BaseAPI
{
    public function index(Request $request, Response $response)
    {
        return $this->getCategories();
    }

    protected function getCategories()
    {
        $categories = (ModelCategories::all())->all();

        return $this->sendSuccessResponse('get data berhasil', $categories);
    }
}
