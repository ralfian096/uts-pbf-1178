<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\API\BaseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ModelCategories;
use Illuminate\Database\QueryException;

class Delete extends BaseAPI
{
    protected DBRepo $dbRepo;

    public function __construct(DBRepo $dbRepo)
    {
        $this->dbRepo = $dbRepo ?? new DBRepo();
    }

    public function index($id = null, Request $request, Response $response)
    {
        return $this->dbRepo->deleteCategory($id);
    }
}
