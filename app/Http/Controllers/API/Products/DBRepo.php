<?php

namespace App\Http\Controllers\API\Products;

use Illuminate\Database\QueryException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\ModelCategories;
use App\Models\ModelProducts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DBRepo
{
    public function create(array $data)
    {
        // Ambil email dari JWT
        $client = JWTAuth::parseToken()->authenticate();

        // Mencoba meng-insert data
        try {
            ModelProducts::insert([
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'price' => $data['price'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'expired_at' => $data['expired_at'],
                'modified_by' => $client->email
            ]);

            return (object) [
                'status' => true,
            ];
        } catch (QueryException $e) {

            return (object) [
                'status' => false,
                'error_detail' => null
            ];
        }
    }

    public function saveImage(Request $request)
    {
        return $request->file('image')->store('public');
    }

    public function get()
    {
        return (ModelProducts::all())->all();
    }

    public function delete($id = null)
    {
        // Check id
        $find = ModelProducts::find($id);

        // Delete data
        try {
            $find->delete();

            return (object) [
                'status' => true,
            ];
        } catch (QueryException $e) {

            return (object) [
                'status' => false,
                'error_detail' => null
            ];
        }
    }

    public function update($id = null, array $data)
    {
        // Get email from JWT
        $client = JWTAuth::parseToken()->authenticate();

        $data['modified_by'] = $client->email;

        $find = ModelProducts::find($id);

        // Trying to update data
        try {
            $find->update($data);

            return (object) [
                'status' => true,
            ];
        } catch (QueryException $e) {

            return (object) [
                'status' => false,
                'error_detail' => null
            ];
        }
    }
}
