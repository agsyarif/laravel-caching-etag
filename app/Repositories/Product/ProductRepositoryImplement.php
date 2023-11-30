<?php

namespace App\Repositories\Product;

use App\Http\Requests\ProductRequest;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Product;
use GuzzleHttp\Psr7\Request;

class ProductRepositoryImplement extends Eloquent implements ProductRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)

    public function getAll($request)
    {
        $sortDirection = $request->sort_dir ?? 'asc';
        $softField = $request->sort_field ?? 'name';
        $limit = $request->limit ?: null;

        $products = $this->model->when($request->q, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            });
        })
            ->orderBy($softField, $sortDirection)
            ->orderByDesc('created_at');

        if ($limit) {
            $products = $products->paginate($limit)->withQueryString();
        } else {
            $products = $products->get();
        }

        return $products->load('productCategory');
    }

    public function updateOrCreate(ProductRequest $request, $id = null)
    {
        if ($id) {
            $product = $this->model->findOrFail($id);
            $product->update($request->validated());

            return $product->load('productCategory');
        }

        $product = $this->model->create($request->validated());
        return $product->load('productCategory');
    }

    public function create($data)
    {
        $product = $this->model->create($data);
        return $product->load('productCategory');
    }

    public function update($id, $data)
    {
        $product = $this->model->findOrFail($id);
        $product->update($data);

        return $product->load('productCategory');
    }

    public function findOrFail($id)
    {
        $product = $this->model->findOrFail($id);
        return $product->load('productCategory');
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }
}
