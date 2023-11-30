<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $sortDirection = $request->sort_dir ?? 'asc';
        $softField = $request->sort_field ?? 'name';
        $limit = $request->limit ?: null;

        $categories = ProductCategory::query();
        $categories->when($request->q, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            });
        })
            ->orderBy($softField, $sortDirection)
            ->orderByDesc('created_at');

        if ($limit) {
            $categories = $categories->paginate($limit)->withQueryString();
        } else {
            $categories = $categories->get();
        }

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $newCategory = ProductCategory::create([
            'name' => $request->name
        ]);

        return $newCategory;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = ProductCategory::findOrFail($id);
        return new CategoryResource($categories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categories = ProductCategory::findOrFail($id);
        $categories->update([
            'name' => $request->name
        ]);

        return new CategoryResource($categories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categories = ProductCategory::findOrFail($id);
        $categories->delete();
    }
}
