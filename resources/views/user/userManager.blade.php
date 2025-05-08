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
<script>
    var currentPage = 1;

    function loadUsers(page, name, email, group_role, is_active) {
        localStorage.setItem('currentPage', page);
        currentPage = page;
        $.ajax({
            url: '/api/users/searchUser?page=' + page, // Action in Struts for loading users
            method: 'GET',
            data: {
                name: name,
                email: email,
                group_role: group_role,
                is_active: is_active,
                page: page
            },
            success: function(response) {
                console.log(response)
                console.log(response.userList)
                $('#message').hide();
                if (response.userList && response.userList.length > 0) {
                    $('#userTable tbody').empty(); // Clear existing rows
                    $.each(response.userList, function(index, user) {
                        var row = '<tr>';
                        row += '<td><input type="checkbox" class="user-checkbox" data-id="' + user.id + '" /></td>';
                        row += '<td>' + user.name + '</td>';
                        row += '<td>' + user.email + '</td>';
                        row += '<td>' + (user.group_role || 'Chưa xác định') + '</td>';
                        row += '<td>' + (user.is_active ? '<span class="badge text-success">Đang hoạt động</span>' : '<span class="badge text-danger">Tạm khoá</span>') + '</td>';
                        row += '<td>';

                        // Edit button
                        row += '<a href="javascript:void(0);" class="edit-btn" data-id="' + user.id + '" title="Sửa">';
                        row += '<i class="bi bi-pencil-fill" style="color: #17a2b8; "></i></a> ';

                        // Delete button
                        row += '<a href="javascript:void(0);"  class="delete-btn" data-id="' + user.id + '" title="Xóa">';
                        row += '<i class="bi bi-trash-fill" style="color: #dc3545;"></i></a> ';

                        // Block/Unblock button
                        row += '<a href="javascript:void(0);" class="block-btn" data-id="' + user.id + '" title="Block/Unblock">';
                        row += '<i class="bi bi-person-fill-x" style="color: black"></i></a>';

                        row += '</td>';
                        row += '</tr>';
                        $('#userTable tbody').append(row);
                    });
                    $('#pagination-top').show();
                    $('#pagination-bottom').show();
                    var startIndex = (response.pagination.current_page - 1) * response.pagination.per_page + 1;
                    console.log(startIndex);
                    var endIndex = Math.min(response.pagination.current_page * response.pagination.per_page, response.pagination.total);
                    $('#start-index').text(startIndex);
                    $('#end-index').text(endIndex);
                    $('#total-users').text(response.pagination.total);
                    if (response.pagination.total <= 10) {
                        $('#pagination-top').hide(); // Hide pagination if fewer than 10 users
                        $('#pagination-bottom').hide(); // Hide pagination if fewer than 10 users
                    } else {
                        updatePagination('#pagination-top', response.pagination.current_page, response.pagination.last_page);
                        updatePagination('#pagination-bottom', response.pagination.current_page, response.pagination.last_page);
                    }
                } else {

                    $('#userTable tbody').empty();
                    $('#userTable tbody').append('<tr><td colspan="6" class="text-center">Không có người dùng nào.</td></tr>');
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
            var is_active = $('#search-status').val();
            if (is_active === "Đang Hoạt Động") {
                is_active = 1;
            } else if (is_active === "Tạm Khoá") {
                is_active = 0;
            } else {
                is_active = '';
            }
            const page = $(this).data('page');
            loadUsers(page, $('#search-name').val(), $('#search-email').val(), $('#search-group').val(), is_active); // Gọi lại ajax với trang mới
        });
    }
    $('#add-new-button').click(function() {
        // Clear form
        $('#addUserForm')[0].reset();
        $('#addMessage').hide().text('');
        $('#addUserModalLabel').text('Thêm Người Dùng Mới');
        $('#saveUserBtn').data('mode', 'add'); // Đặt trạng thái là thêm mới
        $('#addUserModal').modal('show');
    });
    $('#saveUserBtn').click(function(e) {
        e.preventDefault();
        const password = $('#add-password').val();
        const confirmPassword = $('#add-confirm-password').val();
        const name = $('#add-name').val();
        const email = $('#add-email').val();
        const group_role = $('#add-group').val();
        var is_active = $('#add-status').val();
        console.log(email);
        if (is_active === "Đang Hoạt Động") {
            is_active = 1;
        } else {
            is_active = 0;
        }
        console.log("o day tra is active", is_active)
        // Kiểm tra mật khẩu và xác nhận mật khẩu có khớp không
        if (password !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Mật khẩu không khớp',
                text: 'Mật khẩu và xác nhận mật khẩu không giống nhau!'
            });
            return;
        }

        const mode = $(this).data('mode');
        const userId = $(this).data('user-id');
        let url = '';
        if (mode === 'edit') {
            url = '/api/users/updateUser/' + userId;
        } else {
            url = '/api/users/addUser';
        }

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: {
                name: name,
                email: email,
                password: password,
                group_role: group_role,
                is_active: is_active
            },
            success: function(response) {
                console.log('Phản hồi từ server:', response);

                if (response && response.message) {
                    if (response.message.includes('thành công')) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            $('#addUserModal').modal('hide');
                            loadUsers(currentPage, '', '', '', '');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: response.message
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Không nhận được phản hồi từ server.'
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
    });

    $('#search-button').click(function() {
        var name = $('#search-name').val();
        var email = $('#search-email').val();
        var group_role = $('#search-group').val();
        var is_active = $('#search-status').val();
        var specialCharRegex = /[!#$%^&*(),?":{}|<>]/g;

        if (specialCharRegex.test(name)) {
            Swal.fire({
                icon: 'warning',
                title: 'Ký tự không hợp lệ!',
                text: 'Vui lòng không nhập các ký tự đặc biệt!',
            });
            $('#search-name').val('');
            return;
        }
        if (specialCharRegex.test(email)) {
            Swal.fire({
                icon: 'warning',
                title: 'Ký tự không hợp lệ!',
                text: 'Vui lòng không nhập các ký tự đặc biệt!',
            });
            $('#search-email').val('');
            return;
        }
        // Trước khi gửi, kiểm tra giá trị của isActive
        console.log('isActive value before sending:', is_active);

        // Khi isActive là null, chuyển thành null
        if (is_active === "Đang Hoạt Động") {
            is_active = 1;
        } else if (is_active === "Tạm Khoá") {
            is_active = 0;
        } else {
            is_active = '';
        }
        currentPage = 1;
        // Tiến hành gọi hàm AJAX
        loadUsers(currentPage, name, email, group_role, is_active);
    });
    $(document).on('click', '.delete-btn', function() {
        var userId = $(this).data('id'); // Lấy userId từ data-id
        var name = $('#search-name').val();
        var email = $('#search-email').val();
        var group_role = $('#search-group').val();
        var is_active = $('#search-status').val();
        if (is_active === "Đang Hoạt Động") {
            is_active = 1;
        } else if (is_active === "Tạm Khoá") {
            is_active = 0;
        } else {
            is_active = '';
        }
        console.log(is_active);

        // Sử dụng SweetAlert2 thay cho confirm
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
                // Nếu người dùng xác nhận thì mới gửi AJAX
                $.ajax({
                    url: '/api/users/delete/' + userId,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        userId: userId
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status.includes('Success')) {

                            Swal.fire(
                                'Đã xoá!',
                                'Người dùng đã được xoá thành công.',
                                'success'
                            )
                            loadUsers(currentPage, name, email, group_role, is_active);
                        } else {
                            Swal.fire(
                                'Lỗi!',
                                response.message || 'Xóa người dùng thất bại!',
                                'error'
                            )
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Lỗi khi xóa người dùng:", error);
                        Swal.fire(
                            'Lỗi hệ thống!',
                            'Không thể xóa người dùng do lỗi máy chủ.',
                            'error'
                        )
                    }
                });
            }
        });
    });
    $('#clear-button').click(function() {
        // Clear các giá trị tìm kiếm
        $('#search-name').val('');
        $('#search-email').val('');
        $('#search-group').val('');
        $('#search-status').val('');

        // Đặt lại trang về trang đầu tiên
        currentPage = 1;

        // Gọi lại hàm loadUsers để tải lại danh sách ban đầu
        loadUsers(currentPage, '', '', '', '');
    });
    loadUsers(1, '', '', '', '');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.edit-btn', function() {
        var userId = $(this).data('id');
        $.ajax({
            url: 'api/users/getUserByID/' + userId,
            type: 'GET',
            dataType: 'json',
            data: {
                userId: userId
            },

            success: function(response) {
                //console.log(" Response từ server:", response);
                console.log(" Response từ server:", response.user);
                if (response && response.user) {
                    const user = response.user;
                    console.log(" Dữ liệu người dùng nhận được:", user);

                    // Gán dữ liệu vào form
                    $('#add-name').val(user.name);
                    $('#add-email').val(user.email);

                    $('#add-group').val(user.group_role);

                    console.log(user.is_active);
                    console.log("o day tra user" + user.is_active);

                    // Gán trạng thái vào form
                    if (user.is_active === 0) {
                        $('#add-status').val('Tạm Khoá'); // Tạm Khoá
                    } else {
                        $('#add-status').val('Đang Hoạt Động'); // Đang Hoạt Động
                    }


                    // Set chế độ và id user đang sửa
                    $('#addUserModalLabel').text('Chỉnh sửa người dùng');
                    $('#saveUserBtn').data('mode', 'edit').data('user-id', user.id);

                    $('#addMessage').hide().text('');
                    $('#addUserModal').modal('show');
                }
            },
            error: function() {
                alert('Không thể tải dữ liệu người dùng.');
            }
        });
    });
    $('#delete-selected').on('click', function() {
        const selectedIds = $('.user-checkbox:checked').map(function() {
            return $(this).data('id');
        }).get();

        if (selectedIds.length === 0) {
            Swal.fire('Thông báo', 'Vui lòng chọn ít nhất một người dùng để xoá.', 'info');
            return;
        }
        var userIdsString = selectedIds.join(',');

        Swal.fire({
            title: 'Bạn chắc chắn?',
            text: `Bạn muốn xoá ${selectedIds.length} người dùng?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xoá',
            cancelButtonText: 'Huỷ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/api/users/deleteSelectedUsers',
                    type: 'POST',
                    data: JSON.stringify({
                        user_ids: selectedIds
                    }),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.status.includes('Success')) {
                            Swal.fire({
                                title: 'Xoá người dùng thành công',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            })
                            $('#check-all').prop('checked', false);
                            loadUsers(1, '', '', '', '');
                        } else {

                            Swal.fire({
                                title: 'Lỗi khi xoá người dùng!',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Lỗi khi xoá người dùng!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                });
            }
        });

    });
    $(document).on('change', '#select-all', function() {
        var isChecked = $(this).prop('checked');
        $('.user-checkbox').prop('checked', isChecked);
    });
    $(document).on('click', '.block-btn', function() {
        var userId = $(this).data('id'); // Lấy userId từ nút Block/Unblock
        var button = $(this); // Lưu đối tượng nút để thay đổi biểu tượng khi trạng thái thay đổi
        var name = $('#search-name').val();
        var email = $('#search-email').val();
        var group_role = $('#search-group').val();
        var is_active = $('#search-status').val();
        if (is_active === "Đang Hoạt Động") {
            is_active = 1;
        } else if (is_active === "Tạm Khoá") {
            is_active = 0;
        } else {
            is_active = '';
        }
        $.ajax({
            url: 'api/users/blockUser/' + userId, // Gọi action blockUser
            type: 'POST',
            dataType: 'json',
            data: {
                userId: userId
            }, // Truyền userId để xử lý
            success: function(response) {
                console.log('Phản hồi từ server:', response);
                console.log('Phản hồi từ server:', response.is_active);
                if (response.status.includes('Success')) {
                    loadUsers(currentPage, name, email, group_role, is_active);
                } else {
                    alert('Lỗi khi thay đổi trạng thái người dùng!');
                }
            },
            error: function(xhr, status, error) {
                console.log("Lỗi khi thay đổi trạng thái người dùng:", error);
                alert('Lỗi khi thay đổi trạng thái người dùng!');
            }
        });
    });
</script>
@endpush