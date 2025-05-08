<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Quản lý sản phẩm')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .breadcrumb {
                    background-color: transparent;
                    padding: 10px 0;
                }

                .breadcrumb a {
                    color: #007bff;
                    text-decoration: none;
                }

                .breadcrumb a:hover {
                    text-decoration: underline;
                }

                .breadcrumb span {
                    color: #6c757d;
                }

                .container {
                    margin-top: 20px;
                }

                .form-group {
                    margin-bottom: 15px;
                }

                .product-preview {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    border: 1px solid #ccc;
                    padding: 10px;
                    height: 400px;
                    width: 400px;
                    background-color: #f8f9fa;
                }

                .product-preview img {
                    max-height: 100%;
                    max-width: 100%;
                    object-fit: cover;
                }

                .form-section {
                    display: flex;
                    gap: 20px;
                }

                .form-left {
                    flex: 1;
                }

                .form-right {
                    flex: 0.5;
                }

                /* Styling for the upload button and file name input */
                .file-upload-container {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .file-upload-container input[type="file"] {
                    display: none;
                }

                .file-upload-button {
                    padding: 8px 15px;
                    background-color: #007bff;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }


                .file-upload-button:hover {
                    background-color: #0056b3;
                }

                #remove-file-btn {
                    padding: 8px 15px;
                    /* Giữ khoảng cách padding giống như nút Upload */
                    background-color: #dc3545;
                    /* Red color for danger */
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                    /* Adjust font size */
                    line-height: 1.5;
                    /* Ensure the button height is consistent */
                    display: inline-block;
                    /* Ensure the button stays inline */
                    min-width: 82px;

                    min-height: 40px;
                    text-align: center;

                }

                /* Ensure hover state for the "Remove File" button */
                #remove-file-btn:hover {
                    background-color: #c82333;
                    /* Darker red on hover */
                }

                .file-name-input {
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    padding: 8px;
                    width: 200px;
                    margin-left: 10px;
                }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body style="background-color: #f8f9fa;">
    @include("navbar")
    <div class="container mt-4">
        @yield('content')
    </div>
    @stack('scripts') 
</body>
</html>
