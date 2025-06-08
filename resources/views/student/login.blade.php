<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    {{-- logo --}}
    <link rel="icon" type="image/png"
        href="https://cdn.haitrieu.com/wp-content/uploads/2023/01/Logo-Truong-Cao-dang-Ky-thuat-Cao-Thang.png">
    <style>
        body {
            background-color: #e6f2f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .background-shape {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background-color: #c4e3f3;
            top: -150px;
            left: -100px;
            z-index: -1;
        }

        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
        }

        .left-side {
            position: relative;
            padding: 0;
            background-color: #e6f2f9;
            overflow: hidden;
        }

        .image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .illustration {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .right-side {
            padding: 30px;
            position: relative;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 70px;
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            cursor: pointer;
        }

        .university-name {
            font-weight: bold;
            font-size: 1.25rem;
            margin-bottom: 0;
        }

        .student-panel {
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 20px;
        }

        .back-button {
            position: absolute;
            top: 15px;
            left: 15px;
            color: #212529;
            font-size: 20px;
            z-index: 10;
        }

        .website-url {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #6c757d;
            font-size: 14px;
        }

        /* Form wrappers and transitions */
        .forms-container {
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .form-wrapper {
            width: 100%;
            position: absolute;
            transition: transform 0.6s ease-in-out;
            padding: 0 10px;
        }

        /* Divider title */
        .divider-title {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 1.5rem;
            margin-top: 1rem;
        }

        .divider-title::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-index: 0;
        }

        .divider-title h5 {
            position: relative;
            background-color: white;
            z-index: 1;
            padding: 0 15px;
            margin: 0;
            font-size: 1.125rem;
        }

        /* Switch role button */


        .btn-switch {
            background: none;
            border: none;
            font-size: 0.9rem;
            color: #555;
            cursor: pointer;
            text-decoration: underline;
            transition: color 0.3s;
            padding: 0.25rem 0.5rem;
        }

        .btn-switch:hover {
            color: #000;
        }

        /* Animation for login container */
        .login-inner {
            display: flex;
            transition: all 0.6s ease;
        }

        .login-inner.reverse .image-side img {
            transform: scaleX(-1);
        }

        .switch-role {
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="background-shape"></div>

    <div class="container">
        <div class="row min-vh-100 justify-content-center align-items-center">
            <div class="col-12">

                <div class="login-container mx-auto my-5">
                    <div class="row g-0 login-inner">
                        <div class="col-md-6 left-side p-0 ">
                            <a href="https://englishcenter.caothang.edu.vn/">
                                <div class="d-flex justify-content-center align-items-center illustration"
                                    style="height: 100%;">

                                    <img src="https://reviewedu.net/wp-content/uploads/2021/10/cao-dang-ky-thuat-cao-thang-1.jpg"
                                        alt="CKT_Cao_Thang" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>

                            </a>
                        </div>

                        <div class="col-md-6 right-side">
                            <!-- Logo and Title -->
                            <!-- Logo + Tiêu đề -->
                            <div class="text-center logo mb-4">
                                <img src="https://cdn.haitrieu.com/wp-content/uploads/2023/01/Logo-Truong-Cao-dang-Ky-thuat-Cao-Thang.png"
                                    alt="logo_cao_thang" style="width: 70px;">
                                <h5 class="fw-bold mt-3 mb-1 title-lg">Trường Cao Đẳng Kỹ Thuật Cao Thắng</h5>
                                <p class="mb-0 subtitle-sm">Trung Tâm Ngoại Ngữ</p>
                            </div>


                            <!-- Forms Container -->
                            <div class="forms-container" style="height: 300px;">
                                <!-- Student Form -->
                                <div class="form-wrapper student-form">
                                    <div class="divider-title">
                                        <h5 class="fw-bold">Sinh Viên Đăng Nhập</h5>
                                    </div>
                                    @if ($errors->has('StudentLoginFail'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('StudentLoginFail') }}
                                        </div>
                                    @endif
                                    <form action="{{ route('student.authlogin') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="text" name="username" class="form-control"
                                                placeholder="Tài khoản" value="{{ old('username') }}">
                                        </div>
                                        @if ($errors->has('username'))
                                            @foreach ($errors->get('username') as $error)
                                                <div class="text-danger mb-2">{{ $error }}</div>
                                            @endforeach
                                        @endif
                                        <div class="mb-3 password-container">
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Mật Khẩu" value="{{ old('password') }}">
                                            <span class="password-toggle">
                                                <i class="bi bi-eye"></i>
                                            </span>
                                        </div>                                           
                                        @if ($errors->has('password'))
                                            @foreach ($errors->get('password') as $error)
                                                <div class="text-danger mb-2">{{ $error }}</div>
                                            @endforeach
                                        @endif

                                        <button type="submit" class="btn btn-primary">Đăng nhập</button>
                                    </form>
                                    <div id="student-login-message"></div>
                                    <div class="form-wrapper student-form">                           
                                    </div>
                                    <div class="switch-role mt-3">
                                        <a href="{{route('teacher.login')}}">Giảng Viên Đăng Nhập</a>
                                    </div>
                                </div>                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const passwordInput = this.previousElementSibling;
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    // Chờ DOM tải xong
    document.addEventListener('DOMContentLoaded', function () {
        // Chọn tất cả các thông báo lỗi
        const alerts = document.querySelectorAll('.alert, .text-danger');
        if (alerts.length > 0) {
            setTimeout(() => {
                alerts.forEach(el => {
                    el.style.display = 'none';
                });
            }, 3000); // 3 giây
        }
    });

    </script>
</body>

</html>
