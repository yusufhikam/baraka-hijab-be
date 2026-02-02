<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface{

    // get all products with filters
    public function getAllProducts(array $filters, int $perPage): LengthAwarePaginator;

    // get product by slug
    public function getProductBySlug(string $slug);

    // get new arrival products
    public function getNewArrivalProducts(int $limit);

    // get similar products by category slug
    public function getSimilarProductsByCategorySlug(int $productId,string $categorySlug, int  $limit);

    // get product by product_variant_option_id
    public function getProductByProductVariantOptionId(array $productVariantOptionIds);
}