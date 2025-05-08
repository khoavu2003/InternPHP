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
                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="Product Image" />
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
<script>
    $('#product-image').on('change', function(e) {
        var reader = new FileReader();
        reader.onload = function(event) {
            $('#product-preview').html('<img src="' + event.target.result + '" alt="Product Image">');
        }
        reader.readAsDataURL(this.files[0]);

        // Set the file name in the input field
        var fileName = $(this).val().split('\\').pop();
        $('#image-name').val(fileName); // Chỉ cập nhật tên file khi người dùng chọn ảnh
    });
    $('#image-name').on('click', function() {
        $(this).prop('readonly', false); // Allow user to edit the file name
    });
    $(document).on('click', '#save-product-btn', function(e) {
        e.preventDefault();

        var productName = $('#product-name').val().trim();
        var productPrice = $('#product-price').val().trim();
        var description = $('#product-description').val().trim();
        var isSales = $('#product-status').val(); // Get the product status
        var formData = new FormData();
        var specialCharRegex = /[!#$%^&@*(),?".:{}|<>]/g;
        // Get the image file and its name
        var productImage = $('#product-image')[0].files[0];
        var imageName = $('#image-name').val().trim(); // Get the custom image name
        var existingImage = $("input[name='existingImage']").val();


        // Get productId to check if we are editing or adding
        var productId = $("input[name='productId']").val(); // Check if productId exists

        // Check if the fields are filled



        // Append data to formData
        formData.append('product_name', productName);
        formData.append('product_price', productPrice);
        formData.append('description', description);
        formData.append('is_sales', isSales);

        // Only append image name if the user has edited it
        if (productImage) {
            formData.append('product_image', productImage); // Append image file
            if (imageName) {
                formData.append('productImageFileName', imageName); // Append custom image name if it exists
                console.log("Appended custom image name:", imageName);
            } else {
                formData.append('productImageFileName', productImage.name);
                console.log("Appended original image name:", productImage.name); // Use original name if user hasn't edited it
            }
        }
        if (!productImage && existingImage) {
            formData.append('existingImage', existingImage);
        }
        console.log("exist image ", existingImage);
        console.log(productId);
        console.log(productImage);
        if (productId) {
            formData.append('product_id', productId);
            $.ajax({
                url: '/api/products/updateProduct', // Endpoint to handle updating the product
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response.message);
                    if (response.message.includes('thành công')) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cập nhật sản phẩm thành công!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '/productManager';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    let message = 'Đã xảy ra lỗi.';

                    if (xhr.responseJSON) {
                        const res = xhr.responseJSON;

                        // Lấy thông báo chính
                        message = res.message || message;

                        // Nếu có chi tiết lỗi
                        if (res.errors) {
                            const detailErrors = Object.values(res.errors).flat().join('\n');
                            message += '\n' + detailErrors;
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: message
                    });
                }
            });
        } else {
            // Otherwise, it’s an add
            $.ajax({
                url: '/api/products/addProduct', // Endpoint to handle adding product
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    console.log("o day la message" + response.message)
                    if (response.message.includes('thành công')) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cập nhật sản phẩm thành công!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload page to update the product list
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    let message = 'Đã xảy ra lỗi.';

                    if (xhr.responseJSON) {
                        const res = xhr.responseJSON;

                        // Lấy thông báo chính
                        message = res.message || message;

                        // Nếu có chi tiết lỗi
                        if (res.errors) {
                            const detailErrors = Object.values(res.errors).flat().join('\n');
                            message += '\n' + detailErrors;
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: message
                    });
                }
            });
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endpush