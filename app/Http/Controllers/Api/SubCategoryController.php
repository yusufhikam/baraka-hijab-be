<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SubCategoryBestSellerResource;
use App\Http\Resources\Api\SubCategoryResource;
use App\Models\SubCategory;
use App\Services\SubCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SubCategoryController extends Controller
{

    protected $subCategoryService;

    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    public function index(){
        $subCategories = SubCategory::with(['products','category'])->get();

        return SubCategoryResource::collection($subCategories);
    }
    public function carousel()
    {
        $TTL = 3600; // cache data for 1 hour
        $key = 'subCategories_carousel_response';

        $response = Cache::remember($key, $TTL, function ()  {
            $subCategories = $this->subCategoryService->getAllSubCategoriesForCarousel();

            $data = SubCategoryBestSellerResource::collection($subCategories)->resolve();

            return [
                'status' => true,
                'message' => 'Sub Category for Carousel fetched successfully',
                'data' => $data
            ];
            
        });

        return response()->json($response);
    }
}