<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome - InternPHP</title>
    <!-- Thêm Tailwind CSS từ CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Thêm custom CSS -->
    <style>
        .welcome-text {
            animation: fadeInUp 2s ease-in-out infinite alternate;
            background: linear-gradient(
                90deg,
                #1e3a8a, /* Xanh đậm */
                #4b0082, /* Tím đậm */
                #6b7280, /* Xám đậm */
                #3b82f6, /* Xanh lam */
                #a855f7  /* Tím nhạt */
            );
            background-size: 200% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
            animation: gradientMove 4s linear infinite;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 200% 50%;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-button {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-button:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="bg-gray-300 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="welcome-text text-5xl font-bold text-blue-600 mb-6">
            Chào mừng đến với InternPHP!
        </h1>
        <p class="text-lg text-gray-700 mb-8">
            Hãy bắt đầu hành trình của bạn với chúng tôi.
        </p>
        <a href="/login" class="login-button bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600">
            Đăng nhập
        </a>
    </div>

    <!-- Thêm JavaScript cho hiệu ứng (nếu cần) -->
    <script>
        // Hiệu ứng đơn giản khi hover nút
        const button = document.querySelector('.login-button');
        button.addEventListener('mouseover', () => {
            button.style.transform = 'scale(1.1)';
        });
        button.addEventListener('mouseout', () => {
            button.style.transform = 'scale(1)';
        });
    </script>
</body>
</html>