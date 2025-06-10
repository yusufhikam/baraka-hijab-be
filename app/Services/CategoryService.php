<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryService
{

    public function __construct(protected CategoryRepositoryInterface $categoryRepository) {}

    public function create(array $data)
    {
        return $this->categoryRepository->create($data);
    }
}
