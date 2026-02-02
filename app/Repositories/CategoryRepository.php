<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{


    public function getAllCategories()
    {
        $categories = Category::select('id', 'name', 'slug')
                            ->with(['subCategories:id,name,category_id,slug'])
                            ->get();

        return $categories;
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    
}