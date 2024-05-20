<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\API\BaseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Delete extends BaseAPI
{
    protected DBRepo $dbRepo;

    public function __construct(DBRepo $dbRepo)
    {
        $this->dbRepo = new DBRepo();
    }

    public function index($id = null, Request $request, Response $response)
    {
        return $this->dbRepo->deleteProduct($id);
    }
}
