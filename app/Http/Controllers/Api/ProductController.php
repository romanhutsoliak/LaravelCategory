<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\ProductListResource;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count   = request()->query('count', 25);
        
        $products = Product::with(['categories'])
            ->paginate($count);
        
        return ProductListResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('image')) {
            $imageName = md5(mt_rand()).'_'. time() .'.'.request()->file('image')->guessClientExtension();
            $request->file('image')->move(public_path(Product::getRelativeImagePath($imageName)), $imageName);
            $validated['image'] = $imageName;
        }
        
        $product = Product::create($validated);
        if (!empty($validated['categoryIds'])) {
            $product->categories()->sync($validated['categoryIds']);
            Category::updateCounters($validated['categoryIds']);
        }
        
        return $this->show($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load(['categories']);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('image')) {
            $imageName = md5(mt_rand()).'_'. time() .'.'.request()->file('image')->guessClientExtension();
            $request->file('image')->move(public_path(Product::getRelativeImagePath($imageName)), $imageName);
            $validated['image'] = $imageName;
        }
        
        if (!empty($validated['categoryIds'])) {
            $categoryIds = $product->categoryHasProducts->pluck('category_id')->merge(collect($validated['categoryIds']))->unique();
            $product->categories()->sync($validated['categoryIds']);
            Category::updateCounters($categoryIds);
        }
        
        $product->update($validated);
        
        return $this->show($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
    }
}
