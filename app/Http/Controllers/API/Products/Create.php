<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\API\BaseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class Create extends BaseAPI
{
    protected DBRepo $dbRepo;

    public function __construct(DBRepo $dbRepo)
    {
        $this->dbRepo = new DBRepo();
    }

    public function index(Request $request, Response $response)
    {
        $data = $request->all();

        // Validasi data
        $validator = $this->validateData($data);

        if ($validator->fails()) {
            return $this->sendErrorResponse('payload tidak valid', $validator->error(), 400);
        }

        // Kalau validasi berhasil
        // Simpan upload image
        $pathFile = $this->dbRepo->saveImage($request);
        $data['image'] = $pathFile;

        return $this->dbRepo->createProduct($data);
    }

    protected function validateData(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|max:99999999999',
            'category_id' => 'required',
            'expired_at' => 'required|date|date_format:Y-m-d',
            'image' => 'required|file|mimes:jpg,png,jpeg,webp',
        ]);
    }
}
