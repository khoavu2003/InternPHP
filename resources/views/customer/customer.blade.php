@extends('layouts.base')
@section('title', 'Danh Sách khách hàng')
@section('form')
@include('customer.addCustomer')
@endsection

@section('search')
@include('customer.searchCustomer')
@endsection

@section('content')
<div class="container mt-4">
    <div id="userListSummary" class="mb-3">
        <p>Hiển thị từ <span id="start-index">1</span> đến <span id="end-index">10</span> trong tổng
            số <span id="total-customers">0</span> khách hàng.</p>
    </div>
    <div class="pagination" id="pagination-top">
 
    </div>

    <!-- Pagination controls at the top -->
    <div class="pagination" id="pagination-top">

    </div>
   
    <div id="message" class="alert alert-danger" style="display:none;"></div>

    <table class="table table-bordered table-striped" id="customerTable">
        <thead style="background-color: red">
            <tr style="color: white">
                <th>Họ tên</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Điện thoại</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
          
        </tbody>
    </table>
 
    <div class="pagination" id="pagination-bottom">
        
    </div>

</div>

@endsection
@push('scripts')
@vite('resources/js/customer.js')
@endpush