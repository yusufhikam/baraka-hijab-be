<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SubCategoryBestSellerResource;
use App\Http\Resources\Api\SubCategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{

    public function index(){
        $subCategories = SubCategory::with(['products','category'])->get();

        return SubCategoryResource::collection($subCategories);
    }
    public function carousel()
    {
        $subCategories = SubCategory::with([
            'products' => fn($q2) => $q2->select('id','thumbnail', 'sub_category_id','name')->latest()->limit(1),
            'category',
        ])
        ->whereHas('category', function ($q){
            $q->where('name', 'hijabs');
        })
        ->select('id','name','category_id')
        ->latest()->take(7)->get();

        return SubCategoryBestSellerResource::collection($subCategories);
    }
}
