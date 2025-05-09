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