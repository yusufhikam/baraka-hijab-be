<?php  

namespace App\Services;

use App\Repositories\SubCategoryRepository;

class SubCategoryService {

    protected $subCategoryRepository;

    public function __construct(SubCategoryRepository $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }


    public function getAllSubCategories() {
        return $this->subCategoryRepository->getAllSubCategories();
    }

    public function getAllSubCategoriesForCarousel() {
        return $this->subCategoryRepository->getAllSubCategoriesForCarousel();
    }
}