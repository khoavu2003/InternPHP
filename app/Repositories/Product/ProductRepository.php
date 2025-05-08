<?php

namespace App\Repositories\Product;
use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Repositories\Product\ProductRepositoryInterface;
class ProductRepository extends BaseRepository implements ProductRepositoryInterface{
    public function getModel()
    {
        return Product::class;
    }
    public function getAll()
    {
        return $this->model->all();
    }
    public function searchProduct(array $filters){
        $query = $this->model->query();

        if (!empty($filters['product_name'])) {
            $query->where('product_name', 'like', '%' . $filters['product_name'] . '%');
        }
        if (isset($filters['is_sales'])) {
            $query->where('is_sales', $filters['is_sales']);
        }
        if (!empty($filters['min_price'])) {
            $query->where('product_price', '>=',  $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('product_price', '<=',  $filters['max_price']);
        }
        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
    public function update($id, $attributes=[]){
        $product= $this->find($id);
        if($product){
            $product->update($attributes);
            return $product;
        }
        return false;
    }
    

}