@extends('layouts.product')

@section('title', $product ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm')

@section('styles')
/* ... các style khác */
@endsection

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('/productManager') }}">Sản phẩm</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Chi tiết sản phẩm</li>
        </ol>
    </nav>

    <h2 id="form-title">{{ $product ? 'Chỉnh Sửa Sản Phẩm' : 'Thêm Sản Phẩm Mới' }}</h2>

    <form action="{{ $product ? url('webapp/product/updateProduct') : url('api/products/addProduct') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-section">
            <input type="hidden" name="productId" value="{{ $product->product_id ?? '' }}">

            <div class="form-left">
                <div class="form-group">
                    <label for="product-name">Tên Sản Phẩm:</label>
                    <input type="text" id="product-name" name="productName" class="form-control"
                        value="{{ $product->product_name ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="product-price">Giá Bán:</label>
                    <input type="number" id="product-price" name="productPrice" class="form-control"
                        value="{{ $product->product_price ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="product-description">Mô Tả:</label>
                    <textarea id="product-description" name="description" class="form-control" rows="5" required>{{ $product->description ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label for="product-status">Trạng Thái:</label>
                    <select id="product-status" name="isSales" class="form-control" required>
                        <option value="1" {{ isset($product) && $product->is_sales == 1 ? 'selected' : '' }}>Đang Bán</option>
                        <option value="0" {{ isset($product) && $product->is_sales == 0 ? 'selected' : '' }}>Ngừng Bán</option>
                    </select>
                </div>
            </div>

            <!-- Right Section: Product Image Preview -->
            <div class="form-right">
                <h4>Hình ảnh</h4>
                <div class="product-preview" id="product-preview">
                    @if (!empty($product->product_image))
                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="Product Image"  />
                    @else
                    <p>Không có ảnh</p>
                    @endif
                </div>

                <div class="form-group file-upload-container">
                    <label for="product-image" class="file-upload-button">Upload</label>
                    <input type="file" id="product-image" name="productImage" accept="image/*" />
                    <input type="text" id="image-name" class="form-control"
                        value="{{ $product->product_image ?? '' }}" readonly />

                    <input type="hidden" name="existingImage"
                        value="{{ $product->product_image ?? '' }}" />

                </div>
            </div>
        </div>

        <div class="form-group text-end">
            <button type="button" class="btn btn-secondary btn-lg"
                onclick="window.location.href='/productManager'">Huỷ</button>
            <button type="cancel" class="btn btn-danger btn-lg" id="save-product-btn">Lưu</button>
        </div>
    </form>
</div>
@endsection
@push('scripts')
@vite('resources/js/productAdd.js')
@endpush