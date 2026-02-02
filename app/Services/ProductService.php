<?php
namespace App\Services;

use App\Repositories\ProductRepsitory;

class ProductService {

    protected $productRepository;


    public function __construct(ProductRepsitory $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(array $filters, int $perPage = 10){
        return $this->productRepository->getAllProducts($filters, $perPage);
    }

    // get detail product by slug
    public function getProductBySlug(string $slug){

        return $this->productRepository->getProductBySlug($slug);
    }

    // get new arrival products
    public function getNewArrivalProducts(int $limit){
        return $this->productRepository->getNewArrivalProducts($limit);
    }

    // get similar products by category slug
    public function getSimilarProductsByCategorySlug(int $productId,string $categorySlug, int $limit){
        return $this->productRepository->getSimilarProductsByCategorySlug($productId, $categorySlug, $limit);
    }

    // get product by product_variant_option_id
    public function getProductByProductVariantOptionId(array $productVariantOptionIds){
        return $this->productRepository->getProductByProductVariantOptionId($productVariantOptionIds);
    }
}