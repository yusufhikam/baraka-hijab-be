<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\CartResource;
use App\Http\Resources\Api\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected $productService;
    public function __construct(ProductService $productService){
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
                        'category',
                        'sub_category', 
                        'min_price', 
                        'max_price', 
                        'search',
                        'sort'
                    ]);


        $perPage = 8;
        
        $products = $this->productService->getAllProducts($filters, $perPage);
    
        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully',
            'data' =>  ProductResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function show(Product $product)
    {
        $product = $this->productService->getProductBySlug($product->slug);

        return response()->json([
            'status' => true,
            'message' => 'Detail Product fetched successfully',
            'data' =>  new ProductResource($product)
        ]);
    }

    public function newArrivals()
    {
        $products = $this->productService->getNewArrivalProducts(5);

        return response()->json([
            'status' => true,
            'message' => 'New Arrival Products fetched successfully',
            'data' =>  ProductResource::collection($products)
        ]);
    }


    // get similar product by category : slug
    public function similarProducts(Request $request, $productId){


        $categorySlug = $request->query('category');

        $category = Category::where('slug', $categorySlug)->first();
        // $produc

        // validate response if data is not found
        if(!$category || !$categorySlug){
            return response()->json([
                'status' => false,
                'message' => "Similar products not found",
                'data' => null
            ], 200);
        }

        $products = $this->productService->getSimilarProductsByCategorySlug($productId,$category->slug, 10);


        return response()->json([
            'status' => true,
            'message' => "Similar products fetched successfully",
            'data' => ProductResource::collection($products)
        ]);
    }

    // method for get product by product_variant_option_id for localStorage Cart
    public function productByProductVariantOptionId(Request $request){

        // ...route/variant-options?ids=1,2,3
        $ids = array_filter(
            array_map(
                'intval', 
                explode(',', $request->query('ids', [])))
        );

        // limit ids to 50 for avoid abuse 
        if (count($ids) > 50) {
            return response()->json([
                'status' => false,
                'message' => "Too many IDs. Maximum 50 allowed.",
                'data' => null
            ], 400);
        }

        // validate minimal 1 product variant option id
        if(empty($ids)){
            return response()->json([
                'status' => false,
                'message' => "No valid product variant option IDs provided",
                'data' => null
            ], 400);
        }
        
        $products = $this->productService->getProductByProductVariantOptionId($ids);

        if($products->isEmpty()){
            return response()->json([
                'status' => false,
                'message' => "No products found for the given IDs",
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Product fetched successfully",
            'data' => CartResource::collection($products)
        ]);
    }
}