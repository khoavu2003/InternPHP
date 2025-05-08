<!DOCTYPE html>
<html>

<head>
    <title>Đăng nhập</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .card {
            width: 600px;
            height: 500px;
        }

        .form-control {
            width: 100%;
            max-width: 100%;
            padding-left: 30px;
        }

        .input-group-text i {
            font-size: 18px;
        }

        .form-group {
            margin: 40px 40px;
        }

        .alert {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body style="background-color: #ccc;">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card p-4 shadow">
            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-person-fill"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" style="background-color: #ffffff;" required>
                    </div>
                    @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" style="background-color: #ffffff;" required>
                    </div>
                    @if(session('message'))
                    <div class="alert text-danger" role="alert">
                        {{ session('message') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="rememberMe" style="margin-top:-50px" /> Remember me
                    </label>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary w-20" style="margin-right: 50px; ">Đăng nhập</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
