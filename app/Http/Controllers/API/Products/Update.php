<?php

namespace App\Http\Controllers\API\Products;

use App\Http\Controllers\API\BaseAPI;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class Update extends BaseAPI
{
    protected DBRepo $dbRepo;

    public function __construct(DBRepo $dbRepo)
    {
        $this->dbRepo = new DBRepo();
    }

    public function index($id = null, Request $request, Response $response)
    {
        $data = $request->all();

        // Validasi data
        $validator = $this->validateData($data);

        if ($validator->fails()) {
            return $this->sendErrorResponse('payload tidak valid', $validator->error(), 400);
        }

        // Kalau validasi berhasil
        if (isset($data['image'])) {
            $data['image'] = $request->file('image')->store('public');
        }

        return $this->dbRepo->updateProduct($id, $data);
    }

    protected function validateData(array $data)
    {
        return Validator::make($data, [
            'name' => 'string|max:255',
            'price' => 'integer|max:99999999999',
            'expired_at' => 'date|date_format:Y-m-d',
            'image' => 'file|mimes:jpg,png,jpeg,webp',
        ]);
    }
}
