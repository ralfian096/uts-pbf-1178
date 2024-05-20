<?php

namespace App\Http\Controllers\API\Categories;

use Illuminate\Database\QueryException;
use App\Models\ModelCategories;

class DBRepo
{
    /**
     * Get data from database
     * @return array
     */
    public function get()
    {
        return (ModelCategories::all())->all();
    }

    public function create($payload = [])
    {
        // Mencoba meng-insert data
        try {
            ModelCategories::insert([
                'name' => $payload['name']
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

    public function delete($id = null)
    {
        // Check id
        $find = ModelCategories::find($id);

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
        // Check id
        $find = ModelCategories::find($id);

        // Update data
        try {
            $find->update([
                'name' => $data['name'],
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
}
