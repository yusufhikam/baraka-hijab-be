<?php  


namespace App\Repositories;

use App\Models\SubCategory;
use App\Repositories\Interfaces\SubCategoryRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SubCategoryRepository implements SubCategoryRepositoryInterface {


    public function getAllSubCategories(){
        return SubCategory::select('id', 'name', 'slug', 'category_id')
                            ->with(['category:id,name,slug', 'products:id,name,slug,thumbnail'])
                            ->get();
    }

    public function getAllSubCategoriesForCarousel()
    {
        return SubCategory::query()
                            ->with([
                                'category:id,name,slug',
                                'products' => fn ($q) => $q->latest()->take(1)->with([
                                    'productVariants' => fn ($v) => $v->select('id', 'product_id')->with([
                                        'photos' => fn ($p) => $p->select('id', 'product_variant_id', 'photo')->take(2)
                                    ])
                                ])
                            ])
                            ->whereHas('products')
                            ->latest()
                            ->take(7)
                            ->get(['id','name','category_id','slug']);
    
    }
}