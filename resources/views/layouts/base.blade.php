<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Trang Quản Lý')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 12px;
            margin: 0 5px;
            text-decoration: none;
            color: black;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .pagination .active {
            background-color: red;
            color: white;
        }

        .pagination .disabled {
            color: #ccc;
            pointer-events: none;
        }

        .pagination .prev,
        .pagination .next {
            font-weight: bold;
        }

        #pagination-top {
            margin-bottom: 20px;
            text-align: center;
        }

        #pagination-bottom {
            margin-top: 20px;
            text-align: center;
        }
        .product-image-hover {
                display: none;
                position: absolute;
                top: 5px;
                left: 5px;
                width: 150px;
                height: 150px;
                border: 1px solid #ccc;
                background-color: #fff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                overflow: hidden;
            }

            .product-image-hover img {
                max-width: 100%;

                max-height: 100%;

                object-fit: contain;

            }

            /* Hover effect on product name */
            .product-name:hover {

                cursor: pointer;
            }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('head')
</head>

<body style="background-color: #f8f9fa;">
   
    @include('navbar')

   
    @yield('form') 

   
    @yield('search') 

   
    <div class="container mt-4">
        @yield('content')
    </div>

    @stack('scripts') 
</body>
</html>
