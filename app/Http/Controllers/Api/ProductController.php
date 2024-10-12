<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;

class ProductController
{
    public function index()
    {
        return Product::all();
    }

    public function store(StoreProductRequest $request)
    {
        return Product::query()->create($request->validated());
    }
}
