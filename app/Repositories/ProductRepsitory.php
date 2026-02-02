<?php  

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepsitory implements ProductRepositoryInterface{

    protected function applyBaseQuery(array $columns = ['*'], array $withRelations = [], bool $withPhotos = false){

        $query = Product::select($columns);

        if(empty($withRelations)){
            $withRelations = [
                'subCategory:id,name,category_id,slug', 
                'subCategory.category:id,name,slug', 
                'productVariants:id,name,weight,color,product_id',
                'productVariants.productVariantOptions:id,product_variant_id,size,stock,is_ready',
            ];

            if($withPhotos){
                $withRelations[] = 'productVariants.photos:id,product_variant_id,photo';
            }
        }

        return $query->with($withRelations);
                    // ->whereHas('productVariants', function ($q){
                    //     $q->where('is_ready', true);
                    // });
    } 

    public function getAllProducts(array $filters, int $perPage = 10): LengthAwarePaginator{

        $query = $this->applyBaseQuery(withPhotos:true, columns:[
            'id',
            'name',
            'slug',
            'thumbnail',
            'price',
            'sub_category_id',
        ], withRelations:[
            'subCategory:id,name,category_id,slug',
            'subCategory.category:id,name,slug',
        ]);

        // filter by sort
        if(!empty($filters['sort'])){
            switch($filters['sort']){
                case 'lowest':
                    $query->orderBy('price', 'asc');
                    break;
                case 'highest':
                    $query->orderBy('price', 'desc');
                    break;
            }
        }

        // filter by category
        if (!empty($filters['category'])) {
            $query->whereHas('subCategory.category', function ($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }

        // filter by sub category
        if (!empty($filters['sub_category'])) {
            $query->whereHas('subCategory', function ($q) use ($filters) {
                $q->where('slug', $filters['sub_category']);
            });
        }

        // filter by search
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters){
                    $q->where('name', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhereHas('subCategory', function ($q) use ($filters) {
                        $q->where('name', 'LIKE', '%' . $filters['search'] . "%");
                    })
                    ->orWhereHas("subCategory.category", function ($q) use ($filters){
                        $q->where('name', 'LIKE', '%' . $filters['search'] . "%");
                    });
            });
        }

        $issetPriceMin = isset($filters['min_price']);
        $issetPriceMax = isset($filters['max_price']);

        // filter by price range from low to high
        if($issetPriceMin && $issetPriceMax){
            $query->whereBetween('price', [$filters['min_price'], $filters['max_price']]);

        } 
        // filter by only min price
        elseif ($issetPriceMin) {
            $query->where('price', ">=" , $filters['min_price']);
        } 
        // filter by only max price
        elseif ($issetPriceMax) {
            $query->where('price', "<=" , $filters['max_price']);
        }

        $products = $query->latest()->paginate($perPage);
        
        return $products;
    }


    // get product by slug
    public function getProductBySlug(string $slug)
    {
        $product = $this->applyBaseQuery(
            withPhotos:true
        )->where('slug', $slug)->firstOrFail();

        return $product;
    }

    // get new arrival products
    public function getNewArrivalProducts(int $limit = 3){
        $products = $this->applyBaseQuery(
            columns: [
                'id', 
                'name', 
                'slug', 
                'thumbnail',
                'price',
                'sub_category_id'
            ], 
            withRelations: [
                'subCategory:id,name,category_id,slug',
                'subCategory.category:id,name,slug',
                // 'productVariants:id,product_id'
            ])
                ->latest()
                ->take($limit)
                ->get();

        return $products;
    }


    // get similar products by category slug
    public function getSimilarProductsByCategorySlug(int $productId,string $categorySlug, int $limit = 7){

        // jika query parameter categorySlug kosong maka kembalikan data kosong
        if(empty($categorySlug)){
            return collect();
        }
        
        $products = $this->applyBaseQuery(
                [
                    'id',
                    'name',
                    'slug',
                    'thumbnail',
                    'price',
                    'sub_category_id'
                ],
                [
                    'subCategory:id,name,slug,category_id',
                    'subCategory.category:id,name,slug',
                    'productVariants:id,product_id,name,color,weight',
                    'productVariants.productVariantOptions:id,product_variant_id,size,stock,is_ready',
                    'productVariants.photos:id,product_variant_id,photo'
                ]
            )->whereHas('subCategory.category', function ($q) use ($categorySlug){
                $q->where('slug', $categorySlug);
            })
            ->where('id', '!=', $productId)
            ->latest()
            ->take($limit)
            ->get();

        return $products;
        
    }


    // get product by product_variant_option_id
    public function getProductByProductVariantOptionId(array $productVariantOptionIds)
    {
        
        
        $products = $this->applyBaseQuery(
                                [
                                    'id',
                                    'name',
                                    'thumbnail',
                                    'slug',
                                    'price',
                                ], 
                                [
                                    'productVariants:id,product_id,name,color,weight', 
                                    'productVariants.productVariantOptions:id,product_variant_id,stock,size,is_ready'
                                ]
                            );
        
        return $products->whereHas('productVariants.productVariantOptions', function ($q) use ($productVariantOptionIds){
            $q->whereIn('id', $productVariantOptionIds);
        })->get();
    }
}