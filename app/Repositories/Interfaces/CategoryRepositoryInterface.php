<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;

interface CategoryRepositoryInterface
{

    public function create(array $data): Category;

    public function getAllCategories();
}