<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
        $products = Product::create($request->all());
        return new ProductResource($products);
    }

    public function show($id)
    {
        $products = Product::findOrFail($id);
        return new ProductResource($products);
    }

    public function update(Request $request, $id)
    {
        
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
        $product = Product::find($id);
        // dd($product);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->name = $request->name;
        $product->detail = $request->detail; 
        $product->save();

        return response()->json([
            'message' => 'Product update success',
            'data' => new ProductResource($product)
        ]);
    }



    public function destroy($id)
    {
        $products = Product::findOrFail($id);
        $products->delete();
        return response()->json([
            'message' => 'Product delete success',
            'data' => new ProductResource($products)
        ]);
    }
}
