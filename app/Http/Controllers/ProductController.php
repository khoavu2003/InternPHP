<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ProductService;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;

class ProductController extends Controller
{
    protected $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    protected $productService;
    // public function __construct(ProductService $productService)
    // {
    //     $this->productService = $productService;
    // }
    public function showProductManager()
    {
        return view("product.productManager");
    }
    public function deleteProduct($id)
    {
        try {
            $this->productRepository->delete($id);
            return response()->json([
                'status' => 'Success',
                'message' => 'Xoá sản phẩm thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể xoá sản phẩm'
            ]);
        }
    }

    public function showProductDetail($product_id = null)
    {
        $product = $product_id ? Product::find($product_id) : null;
        return view('product.productDetailPage', compact('product'));
    }


    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}0-9\s._\-]+$/u'],
            'product_price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:1000', 'regex:/^[^!#$%^&*(),?":{}|<>]+$/'],
            'is_sales' => ['required', 'in:0,1'],
            'product_image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ], messages: [
            'product_name.regex' => 'Tên sản phẩm không được chứa ký tự đặc biệt.',
            'description.regex' => 'Mô tả không được chứa ký tự đặc biệt.',
            'description.required' => 'Mô tả không được để trống',
            'product_name.required' => 'Tên không được để trống',
            'product_price.regex' => "Giá sản phẩm phải là số",
            'product_price.min' => "Giá sản phẩm phải lớn hơn 0",
            'is_sales.required' => "Trạng thái không được để trống",
            "is_sale.regex" => "Trạng thái không đúng định dạng",
            'product_image.image' => 'File phải là hình ảnh.',
            'product_image.required' => 'Ảnh không được để trống',
            'product_image.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc gif.',
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $data = $validator->validated();
        $imageFile = $request->file('product_image');
        $customFileName = $request->input('productImageFileName');

        // Gọi service xử lý lưu + ảnh
        //$product = $this->productService->createProduct($data, $imageFile, $customFileName);
        $firstChar = strtoupper(substr($data['product_name'], 0, 1));

        $latestProduct = Product::where('product_id', 'like', $firstChar . '%')
            ->orderByDesc('product_id')
            ->first();

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
        };
        $createData = [
            'product_id' => $productId,
            'product_name' => $data['product_name'],
            'product_price' => $data['product_price'],
            'description' => $data['description'],
            'is_sales' => $data['is_sales'],
            'product_image' => $imagePath,
        ];
        $product = $this->productRepository->create($createData);
        return response()->json([
            'status' => 'success',
            'message' => 'Thêm sản phẩm thành công.',
            'product' => $product
        ]);
    }
    public function updateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required'],
            'product_name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}0-9\s._\-]+$/u'],
            'product_price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:1000', 'regex:/^[^!#$%^&*(),?":{}|<>]+$/'],
            'is_sales' => ['required', 'in:0,1'],
            'product_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'existingImage' => 'nullable|string'

        ], messages: [
            'product_name.regex' => 'Tên sản phẩm không được chứa ký tự đặc biệt.',
            'description.regex' => 'Mô tả không được chứa ký tự đặc biệt.',
            'description.required' => 'Mô tả không được để trống',
            'product_name.required' => 'Tên không được để trống',
            'product_price.regex' => "Giá sản phẩm phải là số.",
            'product_price.min' => "Giá sản phẩm phải lớn hơn 0.",
            'is_sales.required' => "Trạng thái không được để trống.",
            "is_sale.regex" => "Trạng thái không đúng định dạng.",
            'product_image.image' => 'File phải là hình ảnh.',
            'product_image.required' => 'Ảnh không được để trống',
            'product_image.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc gif.',
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        //$product = $this->productService->updateProduct($validator->validated(), $request->file('product_image'));
        $data = $validator->validated();
        if ($request->file('product_image')) {
            $fileName = $data["productImageFileName"] ?? $request->file('product_image')->getClientOriginalName();
            $imagePath = $request->file('product_image')->storeAs('products', $fileName, 'public');
        } else {
            $imagePath = $data['existingImage'] ?? null;
        }
        $dataUpdate = [
            'product_name' => $data['product_name'],
            'product_price' => $data['product_price'],
            'description' => $data['description'],
            'is_sales' => $data['is_sales'],
            'product_image' => $imagePath
        ];
        $product = $this->productRepository->update($data['product_id'], $dataUpdate);
        return response()->json([
            'status' => 'Success',
            'message' => 'Cập nhật người dùng thành công',
            'product' => $product
        ]);
    }

    public function searchProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}0-9\s._@\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]+$/u'],
            'is_sales' => ['nullable', 'boolean'],
            'min_price' => ['nullable', 'numeric', 'min:0.01'],
            'max_price' => ['nullable', 'numeric', 'min:0.01']
        ], [
            'product_name.regex' => 'Tên sản phẩm không được chứa ký tự đặc biệt.',
            'is_sales.regex' => 'Trạng thái sản phẩm không hợp lệ.',
            'min_price.numeric' => 'Giá trị của min_price phải là một số.',
            'min_price.min' => 'Giá trị min_price phải lớn hơn hoặc bằng 0.',
            'max_price.numeric' => 'Giá trị của max_price phải là một số.',
            'max_price.min' => 'Giá trị max_price phải lớn hơn hoặc bằng 0.',
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }

        $product = $this->productRepository->searchProduct($validator->validated());

        return response()->json([
            'status' => 'success',
            'productList' => $product->items(),
            'pagination' => [
                'current_page' => $product->currentPage(),
                'last_page' => $product->lastPage(),
                'per_page' => $product->perPage(),
                'total' => $product->total(),
            ]
        ]);
    }
}
