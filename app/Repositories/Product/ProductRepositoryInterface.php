<?php

namespace App\Repositories\Product;

use App\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function searchProduct(array $filters);

    public function update($id, $attributes=[]);

    
}
