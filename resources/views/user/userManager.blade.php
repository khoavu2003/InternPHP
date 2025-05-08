
@extends('layouts.base')
@section('title', 'Danh Sách Người Dùng')
@section('form')
    @include('user.add-user')
@endsection

@section('search')
    @include('user.searchUser')
@endsection

@section('content')
    <div id="userListSummary" class="mb-3">
        <p>Hiển thị từ <span id="start-index">1</span> đến <span id="end-index">10</span> trong tổng số <span id="total-users">0</span> User.</p>
    </div>

    <!-- Pagination controls at the top -->
    <div class="pagination" id="pagination-top"></div>

    <!-- Error message display -->
    <div id="message" class="alert alert-danger" style="display:none;"></div>

    <!-- User table -->
    <table class="table table-bordered table-striped" id="userTable">
        <thead style="background-color: red">
            <tr style="color:white">
                <th><input type="checkbox" id="select-all" /></th>
                <th>Tên</th>
                <th>Email</th>
                <th>Nhóm</th>
                <th>Trạng Thái</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
           
        </tbody>
    </table>

    <div class="pagination" id="pagination-bottom"></div>
   
@endsection
@push('scripts')
    @vite('resources/js/user.js')
@endpush
