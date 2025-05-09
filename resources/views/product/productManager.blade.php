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
    @include('scripts.product');
@endpush