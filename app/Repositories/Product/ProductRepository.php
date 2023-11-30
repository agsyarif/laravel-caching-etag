<?php

namespace App\Repositories\Product;

use App\Http\Requests\ProductRequest;
use GuzzleHttp\Psr7\Request;
use LaravelEasyRepository\Repository;

interface ProductRepository extends Repository
{
    // funciton sendiri
    public function getAll($request);

    // funciton sendiri
    public function updateOrCreate(ProductRequest $request, $id = null);

    // extend dari repository
    public function create($data);

    // extend dari repository
    public function update($id, $data);

    // extend dari repository
    public function findOrFail($id);

    // extend dari repository
    public function delete($id);
}
