<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function searchProduct(array $filters)
    {
        // $query = Product::query();

        // if (!empty($filters['product_name'])) {
        //     $query->where('product_name', 'like', '%' . $filters['product_name'] . '%');
        // }
        // if (isset($filters['is_sales'])) {
        //     $query->where('is_sales', $filters['is_sales']);
        // }
        // if (!empty($filters['min_price'])) {
        //     $query->where('product_price', '>=',  $filters['min_price']);
        // }
        // if (!empty($filters['max_price'])) {
        //     $query->where('product_price', '<=',  $filters['max_price']);
        // }
        // return $query->orderBy('created_at', 'desc')->paginate(10);
        return $this->productRepository->searchProduct($filters);
    }
    public function deleteProductByID($id){
        $product= Product::find($id);
        $product->delete();
    }
    public function updateProduct(array $data,$imageFile =null){
        $product = Product::find($data["product_id"]);
        if($imageFile){
            $fileName = $data["productImageFileName"]??$imageFile->getClientOriginalName();
            $imagePath=$imageFile->storeAs('products',$fileName,'public');
        }else{
            $imagePath=$data['existingImage']??null;
        }
        $product->update([
            'product_name'=>$data['product_name'],
            'product_price'=>$data['product_price'],
            'description'=>$data['description'],
            'is_sales'=>$data['is_sales'],
            'product_image'=> $imagePath
        ]);
        return $product;
    }
    public function createProduct(array $data, ?UploadedFile $imageFile = null, ?string $customFileName = null)
    {
        // Tạo product_id tự động từ tên sản phẩm
        $firstChar = strtoupper(substr($data['product_name'], 0, 1));

        $latestProduct = Product::orderByDesc('product_id')->first();
        $nextNumber = 1;

        if ($latestProduct && strlen($latestProduct->product_id) > 1) {
            $numberPart = intval(substr($latestProduct->product_id, 1));
            $nextNumber = $numberPart + 1;
        }

        $productId = $firstChar . str_pad($nextNumber, 9, '0', STR_PAD_LEFT);

        // Xử lý lưu ảnh nếu có
        $imagePath = null;
        if ($imageFile) {
            $fileName = $customFileName ?? $imageFile->getClientOriginalName();
            $imagePath = $imageFile->storeAs('products', $fileName, 'public');
        }

        // Lưu vào DB
        return Product::create([
            'product_id' => $productId,
            'product_name' => $data['product_name'],
            'product_price' => $data['product_price'],
            'description' => $data['description'],
            'is_sales' => $data['is_sales'],
            'product_image' => $imagePath,
        ]);
    }

}
