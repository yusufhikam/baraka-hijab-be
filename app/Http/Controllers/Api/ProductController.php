<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductNewArrivalResource;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        ProductVariant::where('is_ready', true)
                        ->where('stock', 0)
                        ->update(['is_ready' => false]);
                        
        $query = Product::with(['subCategory', 'subCategory.category', 'productVariants', 'productVariants.product', 'photos'])
                        ->whereHas('productVariants', function ($q){
                            $q->where('is_ready', true);
                        });


        if ($request->filled('category')) {
            $query->whereHas('subCategory.category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->filled('subCategory')) {
            $query->whereHas('subCategory', function ($q) use ($request) {
                $q->where('name', $request->subCategory);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('filter')) {
            $query->latest();
        }

        $products = $query->latest()->paginate(6);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $product = Product::with(['subCategory', 'subCategory.category', 'productVariants', 'photos'])
            ->where('slug', $product->slug)
            ->firstOrFail();

        return new ProductResource($product);
    }

    public function newArrivals()
    {
        $products = Product::latest()->take(3)->get();

        return ProductNewArrivalResource::collection($products);
    }
}