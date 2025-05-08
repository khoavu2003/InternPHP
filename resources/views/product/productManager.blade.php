@extends('layouts.base')
@section('title', 'Danh Sách Người Dùng')


@section('search')
@include('product.searchProduct')
@endsection

@section('content')
<div id="productListSummary" class="mb-3">
    <p>Hiển thị từ <span id="start-index">1</span> đến <span id="end-index">10</span> trong tổng số
        <span id="total-products">0</span> sản phẩm.
    </p>
</div>
<div class="pagination" id="pagination-top">

</div>

<div id="message" class="alert alert-danger" style="display:none;"></div>


<table class="table table-bordered table-striped" id="productTable">
    <thead style="background-color:red">
        <tr style="color:white">
            <th>No.</th>
            <th>Tên Sản Phẩm</th>
            <th>Mô Tả</th>
            <th>Giá</th>
            <th>Tình Trạng</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>


<div class="pagination" id="pagination-bottom">

</div>


<div class="product-image-hover" id="product-image-hover">
    <img src="" alt="Product Image" id="hover-image" />
</div>

@endsection
@push('scripts')
<script>
    var currentPage = 1;

    function loadProducts(page, product_name, is_sales, min_price, max_price) {
        localStorage.setItem('currentPage', page);
        currentPage = page;
        $.ajax({
            url: '/api/products/searchProduct?page' + page, // Action in Struts for loading customers
            type: 'GET',
            dataType: 'json',
            data: {
                page: page,
                product_name: product_name,
                is_sales: is_sales,
                min_price: min_price,
                max_price: max_price,
            },
            success: function(response) {
                console.log(response);
                $('#message').hide();
                if (response && response.productList && response.productList.length > 0) {
                    $('#productTable tbody').empty(); // Clear existing rows
                    $.each(response.productList, function(index, product) {
                        var row = '<tr>';
                        row += '<td>' + (index + 1) + '</td>';
                        row += '<td class="product-name" data-image="/storage/' + product.product_image + '">' + product.product_name + '</td>';
                        row += '<td>' + (product.description || 'Chưa có mô tả') + '</td>';
                        row += '<td>' + (product.product_price || 'Chưa có giá') + ' VNĐ</td>';
                        row += '<td>' + (product.is_sales === 1 ? 'Đang Bán' : 'Ngừng Bán') + '</td>';
                        row += '<td>';
                        row += '<a href="javascript:void(0);" class="edit-btn" data-id="' + product.product_id + '" title="Sửa"> <i class="bi bi-pencil-fill" style="color: #17A2B8;"></i></a> ';
                        row += '<a href="javascript:void(0);" class="delete-product-btn" data-id="' + product.product_id + '" title="Xóa"><i class="bi bi-trash-fill" style="color: #dc3545;"></i></a>';
                        row += '</td>';
                        row += '</tr>';
                        $('#productTable tbody').append(row);
                    });
                    $('#pagination-top').show(); // Ẩn phân trang
                    $('#pagination-bottom').show(); // Ẩn phân trang
                    var startIndex = (response.pagination.current_page - 1) * response.pagination.per_page + 1;
                    console.log(startIndex);
                    var endIndex = Math.min(response.pagination.current_page * response.pagination.per_page, response.pagination.total);
                    $('#start-index').text(startIndex);
                    $('#end-index').text(endIndex);
                    $('#total-products').text(response.pagination.total);
                    if (response.pagination.total <= 10) {
                        $('#pagination-top').hide(); // Ẩn phân trang
                        $('#pagination-bottom').hide(); // Ẩn phân trang
                    } else {
                        updatePagination('#pagination-top', response.pagination.current_page, response.pagination.last_page);
                        updatePagination('#pagination-bottom', response.pagination.current_page, response.pagination.last_page);
                    }
                } else {
                    $('#productTable tbody').empty();
                    $('#productTable tbody').append('<tr><td colspan="6" class="text-center">Không có khách hàng nào.</td></tr>');
                    $('#pagination-top').hide();
                    $('#pagination-bottom').hide();
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
    loadProducts(1, '', '', '', '');
    //Update pagination
    function updatePagination(paginationId, currentPage, totalPages) {
        const $pagination = $(paginationId);
        currentPage = currentPage;
        $(paginationId).html('');
        if (currentPage > 1) {
            $pagination.append('<a href="#" class="page-control prev" data-page="' + (currentPage - 1) + '">‹</a> ');
        }
        for (var i = 1; i <= totalPages; i++) {
            if (i == currentPage) {
                $(paginationId).append('<a href="#" class="page active" data-page="' + i + '">' + i + '</a> ');
            } else {
                $(paginationId).append('<a href="#" class="page" data-page="' + i + '">' + i + '</a> ');
            }
        }
        if (currentPage < totalPages) {
            $pagination.append('<a href="#" class="page-control next" data-page="' + (currentPage + 1) + '">›</a>');
        }
        $pagination.find('a.page, a.page-control').off('click').on('click', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            loadProducts(page, $('#search-name').val(), $('#search-status').val(), $('#search-min-price').val(), $('#search-max-price').val()); // Gọi lại ajax với trang mới
        });
    }
    $(document).on('mouseenter', '.product-name', function(e) {
        var imageUrl = $(this).data('image');
        var offset = $(this).offset();
        $('#hover-image').attr('src', imageUrl);
        $('#product-image-hover')
            .css({
                left: offset.left + $(this).outerWidth() + -200 + 'px',
                top: offset.top - 150 + 'px' // Align it with the text vertically
            })
            .show();
    });
    $(document).on('mouseleave', '.product-name', function() {
        $('#product-image-hover').hide();
    });
    $('#clear-button').click(function() {
        $('#search-name').val('');
        $('#search-status').val('');
        $('#search-min-price').val('');
        $('#search-max-price').val('');
        currentPage = 1;
        // Load first page with no search filters
        loadProducts(currentPage, '', '', '', '');
    });
    $('#search-button').click(function() {
        var product_name = $('#search-name').val();
        var is_sales = $('#search-status').val();
        var min_price = $('#search-min-price').val();
        var max_price = $('#search-max-price').val();
        var specialCharRegex = /[!#$%^&*(),?":{}|<>]/g;
        if (specialCharRegex.test(product_name)) {
            Swal.fire({
                icon: 'warning',
                title: 'Ký tự không hợp lệ!',
                text: 'Vui lòng không nhập các ký tự đặc biệt!',
            });
            $('#search-name').val('');
            return;
        }
        if (min_price < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Giá trị không hợp lệ',
                text: 'Giá trị min_price không được nhỏ hơn 0.',
            });
            $('#search-min-price').val('');
            return;
        }
        if (max_price < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Giá trị không hợp lệ',
                text: 'Giá trị max_price không được nhỏ hơn 0.',
            });
            $('#search-max-price').val('');
            return;
        }
        currentPage = 1; // Reset to the first page when searching
        loadProducts(currentPage, product_name, is_sales, min_price, max_price);
    });
    $('#add-new-product-button').click(function() {
        // Chuyển hướng đến trang thêm sản phẩm
        window.location.href = '/productManager/productDetail'; // Đảm bảo đường dẫn đúng đến trang thêm sản phẩm
    });

    $(document).on('click', '.edit-btn', function() {
        var productId = $(this).data('id'); // Lấy ID sản phẩm từ thuộc tính 'data-id'
        window.location.href = "/productManager/productDetail/" + productId;
    });

    $(document).on('click', '.delete-product-btn', function() {
        const productId = $(this).data('id');
        var name = $('#search-name').val();
        var status = $('#search-status').val();
        var minPrice = $('#search-min-price').val();
        var maxPrice = $('#search-max-price').val();
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Bạn sẽ không thể hoàn tác sau khi xóa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Huỷ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/api/products/delete/' + productId, // Đảm bảo đúng endpoint đã cấu hình trong Struts
                    type: 'POST',
                    data: {
                        product_id: productId
                    },
                    success: function(response) {
                        console.log(response.message);
                        if (response.message.includes("thành công")) {
                            Swal.fire(
                                'Đã xoá!',
                                'Người dùng đã được xoá thành công.',
                                'success'
                            )
                            loadProducts(currentPage, name, status, minPrice, maxPrice)
                        } else {
                            Swal.fire(
                                'Lỗi!',
                                response.message || 'Xóa người dùng thất bại!',
                                'error'
                            )
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Lỗi!',
                            'error'
                        )
                    }
                });
            }
        });


    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endpush