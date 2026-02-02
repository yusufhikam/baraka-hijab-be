<?php  

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface SubCategoryRepositoryInterface {

    public function getAllSubCategories();
    public function getAllSubCategoriesForCarousel();
}