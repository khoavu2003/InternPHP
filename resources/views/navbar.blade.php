<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .navbar {
            background-color: #ccc;
            margin-left: 100px;
        }

        /* Màu nền của navbar items khi được chọn */
        .navbar .nav-link.active {
            color: #fff;
            background-color: #e60000;
        }

        /* Màu nền khi hover */
        .navbar .nav-link:hover {
            background-color: #ddd;
        }

        /* Đặt vị trí biểu tượng người dùng ở góc phải */
        .navbar .navbar-nav.ml-auto {
            margin-left: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/productManager" onclick="setActive(this, 'product')">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/customerManager" onclick="setActive(this, 'customer')">Khách hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/userManager" onclick="setActive(this, 'user')">Users</a>
                    </li>
                </ul>
                <!-- Đoạn code dropdown user với biểu tượng -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> User
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li> <!-- Gọi action logout -->
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


</body>
<script>
    // Hàm để set active cho navbar
    function setActive(element, page) {
        let links = document.querySelectorAll('.nav-link');
        links.forEach(link => link.classList.remove('active')); // Remove class active from all links

        // Add active class to the clicked link
        element.classList.add('active');

        // Save the active page to localStorage
        localStorage.setItem('activePage', page);
    }

    // Khi trang được load, kiểm tra trạng thái active đã lưu trong localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const activePage = localStorage.getItem('activePage');
        if (activePage) {
            const links = document.querySelectorAll('.nav-link');
            links.forEach(link => {
                if (link.getAttribute('href').includes(activePage)) {
                    link.classList.add('active');
                }
            });
        }
    });
</script>

</html>