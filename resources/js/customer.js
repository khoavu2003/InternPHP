var currentPage = 1;
function loadCustomers(page, customer_name, email, address, is_active) {
    localStorage.setItem('currentPage', page);
    currentPage = page;
    $.ajax({
        url: '/api/customers/searchCustomer?page=' + page,  // Action in Struts for loading customers
        type: 'GET',
        dataType: 'json',
        data: {
            page: page,
            customer_name: customer_name,
            email: email,
            address: address,
            is_active: is_active,
        },
        success: function (response) {
            console.log(response);
            $('#message').hide();
            if (response && response.customerList && response.customerList.length > 0) {
                $('#customerTable tbody').empty();  // Clear existing rows
                $.each(response.customerList, function (index, customer) {
                    var row = '<tr>';

                    row += '<td>' + customer.customer_name + '</td>';
                    row += '<td>' + customer.email + '</td>';
                    row += '<td>' + (customer.address || '') + '</td>';
                    row += '<td>' + (customer.tel_num || '') + '</td>';
                    row += '<td>';

                    // Edit button
                    row += '<a href="javascript:void(0);" class="edit-btn" data-id="' + customer.customer_id + '" title="Sửa">';
                    row += '<i class="bi bi-pencil-fill" style="color: #17A2B8;"></i></a> ';

                    row += '</td>';
                    row += '</tr>';
                    $('#customerTable tbody').append(row);
                });
                $('#pagination-top').show();  // Ẩn phân trang
                $('#pagination-bottom').show();  // Ẩn phân trang
                var startIndex = (response.pagination.current_page - 1) * response.pagination.per_page + 1;
                console.log(startIndex);
                var endIndex = Math.min(response.pagination.current_page * response.pagination.per_page, response.pagination.total);
                $('#start-index').text(startIndex);
                $('#end-index').text(endIndex);
                $('#total-customers').text(response.pagination.total);
                if (response.pagination.total <= 10) {
                    $('#pagination-top').hide();  // Ẩn phân trang
                    $('#pagination-bottom').hide();  // Ẩn phân trang
                }
                else {
                    updatePagination('#pagination-top', response.pagination.current_page, response.pagination.last_page);
                    updatePagination('#pagination-bottom', response.pagination.current_page, response.pagination.last_page);
                }
            } else {
                $('#customerTable tbody').empty();
                $('#customerTable tbody').append('<tr><td colspan="6" class="text-center">Không có khách hàng nào.</td></tr>');
                $('#pagination-top').hide();
                $('#pagination-bottom').hide();
            }

        },
        error: function (xhr, status, error) {
            $('#message').text('Lỗi khi tải danh sách khách hàng!').show();
            $('#customerTable tbody').empty();
        }
    });
}
loadCustomers(1, '', '', '', '');
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
    $pagination.find('a.page, a.page-control').off('click').on('click', function (e) {
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
        loadCustomers(page, $('#search-name').val(), $('#search-email').val(), $('#search-address').val(), is_active); // Gọi lại ajax với trang mới
    });
}

$('#search-button').click(function () {
    var customer_name = $('#search-name').val();
    var email = $('#search-email').val();
    var address = $('#search-address').val();
    var is_active = $('#search-status').val();
    var specialCharRegex = /[!#$%^&*(),?":{}|<>]/g;
    console.log(customer_name);
    if (specialCharRegex.test(customer_name)) {
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
    if (specialCharRegex.test(address)) {
        Swal.fire({
            icon: 'warning',
            title: 'Ký tự không hợp lệ!',
            text: 'Vui lòng không nhập các ký tự đặc biệt!',
        });
        $('#search-address').val('');
        return;
    }
    // Reload first page when performing search
    currentPage = 1;
    console.log('isActive value before sending:', is_active);
    if (is_active === "Đang Hoạt Động") {
        is_active = 1;
    } else if (is_active === "Tạm Khoá") {
        is_active = 0;
    } else {
        is_active = '';
    }
    console.log('isActive value before sending:', is_active);
    loadCustomers(currentPage, customer_name, email, address, is_active);
});
//handle excel button click

// Handle clear button to reset the search form and reload the first page
$('#clear-button').click(function () {
    $('#search-name').val('');
    $('#search-email').val('');
    $('#search-address').val('');
    $('#search-status').val('');
    currentPage = 1;
    // Load first page with no search filters
    loadCustomers(currentPage, '', '', '', '');
});

$('#add-new-customer-button').click(function () {
    $('#addCustomerForm')[0].reset();
    $('#addCustomerMessage').hide().text('');
    $('#addCustomerModalLabel').text('Thêm Khách Hàng Mới');
    $('#addCustomerModal').modal('show');
});


//Save customer
$('#saveCustomerBtn').click(function (e) {
    e.preventDefault();
    var customer_name = $('#customer-name').val();
    var email = $('#customer-email').val();
    var address = $('#customer-address').val();
    var tel_num = $('#customer-phone').val();
    var is_active = $('#customer-status').val();
    var specialCharRegex = /[!#$%^&*(),?":{}|<>]/g;

    //if (specialCharRegex.test(name) || specialCharRegex.test(email) || specialCharRegex.test(address)) {
    //    alert("Vui lòng không nhập các ký tự đặc biệt!");
    //    return;  // Dừng lại nếu có ký tự đặc biệt
    //}
    if (is_active === "Đang Hoạt Động") {
        is_active = 1;
    } else if (is_active === "Tạm Khoá") {
        is_active = 0;
    } else {
        is_active = '';
    }
    // Gửi dữ liệu form lên server
    $.ajax({
        url: '/api/customers/addCustomer',
        type: 'POST',
        dataType: 'json',
        data: {
            customer_name: customer_name,
            email: email,
            address: address,
            tel_num: tel_num,
            is_active: is_active
        },  // Lấy dữ liệu từ form
        success: function (response) {
            console.log('Phản hồi từ server:', response);
            console.log('Phản hồi từ server:', response.message);
            if (response && response.message) {
                if (response.message.includes('thành công')) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#addCustomerModal').modal('hide');
                        loadCustomers(currentPage, '', '', '', '');
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
        error: function (xhr, status, error) {
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
$(document).on('click', '.save-inline', function () {
    const row = $(this).closest('tr');
    const customerId = $(this).data('id');
    console.log("ID:", customerId);

    const customerName = row.find('input[data-field="customerName"]').val().trim();
    const email = row.find('input[data-field="email"]').val().trim();
    const address = row.find('input[data-field="address"]').val().trim();
    const telNum = row.find('input[data-field="telNum"]').val().trim();

    console.log("Lưu:", customerName, email, address, telNum);


    if (!customerName || !email || !address || !telNum) {
        Swal.fire({
            icon: 'warning',
            title: 'Cảnh báo!',
            text: 'Vui lòng nhập dầy đủ thông tin khách hàng',
        })

        return;
    }

    const phoneRegex = /^[0-9]{10,11}$/;
    if (!phoneRegex.test(telNum)) {
        Swal.fire({
            icon: 'warning',
            title: 'Cảnh báo!',
            text: 'Số điện thoại phải có từ 9-11 số',
        })
        return;
    }

    // ======== GỬI AJAX CẬP NHẬT ========
    $.ajax({
        url: 'api/customers/updateCustomer/' + customerId,
        type: 'POST',
        dataType: 'json',
        data: {
            customer_name: customerName,
            email: email,
            address: address,
            tel_num: telNum,
        },
        success: function (response) {
            console.log("Phản hồi server:", response);

            if (response && response.message.includes('thành công')) {
                // Cập nhật lại dòng dữ liệu
                row.find('td:eq(0)').text(customerName);
                row.find('td:eq(1)').text(email);
                row.find('td:eq(2)').text(address);
                row.find('td:eq(3)').text(telNum);
                row.find('td:eq(4)').html(`
                <a href="javascript:void(0);" class="edit-btn" data-id="${customerId}" title="Sửa">
                   <i class="bi bi-pencil-fill" style="color: #17A2B8;"></i>
                </a>
`);
                row.removeClass('editing');
            } else {
                alert(response.message || 'Không thể cập nhật khách hàng.');
            }
        },
        error: function (xhr, status, error) {
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


$(document).on('click', '.edit-btn', function () {
    const row = $(this).closest('tr');
    const customerId = $(this).data('id');

    // Nếu đang ở chế độ sửa rồi thì bỏ qua
    if (row.hasClass('editing')) return;

    row.addClass('editing');

    // LẤY DỮ LIỆU TỪ data-*
    const customerName = row.find('td:eq(0)').text().trim();
    const email = row.find('td:eq(1)').text().trim();
    const address = row.find('td:eq(2)').text().trim();
    const telNum = row.find('td:eq(3)').text().trim();
    console.log(customerName);
    // Đổ dữ liệu vào các ô input để chỉnh sửa
    const inputName = $('<input type="text" class="form-control form-control-sm" data-field="customerName">').val(customerName);
    const inputEmail = $('<input type="email" class="form-control form-control-sm" data-field="email">').val(email);
    const inputAddress = $('<input type="text" class="form-control form-control-sm" data-field="address">').val(address);
    const inputTel = $('<input type="text" class="form-control form-control-sm" data-field="telNum">').val(telNum);

    // Gán vào cột
    row.find('td:eq(0)').html(inputName);
    row.find('td:eq(1)').html(inputEmail);
    row.find('td:eq(2)').html(inputAddress);
    row.find('td:eq(3)').html(inputTel);
    const saveBtn = $('<button class="btn btn-sm btn-success save-inline">Lưu</button>');
    saveBtn.attr('data-id', customerId);

    const cancelBtn = $('<button class="btn btn-sm btn-secondary cancel-inline">Hủy</button>');
    // Nút lưu
    row.find('td:eq(4)').html('').append(saveBtn).append(cancelBtn);
});
//Handle cancel inline
$(document).on('click', '.cancel-inline', function () {
    const row = $(this).closest('tr');

    Swal.fire({
        title: 'Bạn có chắc chắn muốn hủy chỉnh sửa?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Không'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.reload();
        }
    });
});
$(document).on('click', '#import-excel-btn', function (e) {
    e.preventDefault();
    $('#excel-file').click();
});

$('#excel-file').on('change', function () {
    let form = $('#excelUploadForm')[0];
    let formData = new FormData(form);
    var name = $('#search-name').val();
    var email = $('#search-email').val();
    var address = $('#search-address').val();
    var isActive = $('#search-status').val();
    if (isActive === "Đang Hoạt Động") {
        isActive = true;
    } else if (isActive === "Tạm Khoá") {
        isActive = 0;
    } else {
        isActive = 1;
    }
    console.log('File:', formData.get('excelFile'));
    $.ajax({
        url: '/customers/import', // Backend action URL
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);  // Log the response from backend
            console.log(response.message)
            if (response.message.includes('thành công')) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    currentPage = 1;
                    loadCustomers(currentPage, '', '', '', '');
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: response.message
                });
            }
        },
        error: function (xhr, status, error) {
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


$('#export-excel-btn').click(function () {
    let name = $('#search-name').val();
    let email = $('#search-email').val();
    let address = $('#search-address').val();
    let isActive = $('#search-status').val();
    if (isActive === "Đang Hoạt Động") {
        isActive = 1;
    } else if (isActive === "Tạm Khoá") {
        isActive = 0;
    } else {
        isActive = '';
    }

    // Tạo URL query string
    let params = $.param({ currentPage, name, email, address, isActive });

    // Redirect để tải file
    window.location.href = '/customers/export?' + params;
});


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
