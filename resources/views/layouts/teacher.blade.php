<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Trang chủ')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png"
        href="https://cdn.haitrieu.com/wp-content/uploads/2023/01/Logo-Truong-Cao-dang-Ky-thuat-Cao-Thang.png">
    @yield('styles')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .nav-button.active {
            background-color: #e6ddfb !important;
            border-color: #6f42c1 !important;
            color: #6f42c1 !important;
        }

        .nav-button:hover {
            background-color: #e6ddfb !important;
            border-color: #6f42c1 !important;
            color: #6f42c1 !important;
        }

        .btn-logout:hover {
            background-color: #e6ddfb !important;
            color: red !important;
            border-color: red !important;
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- HEADER -->
    <header class="sticky-top bg-white border-bottom shadow-sm">
        <nav class="container d-flex align-items-center justify-content-between py-2">
            <img src="https://cdn.haitrieu.com/wp-content/uploads/2023/01/Logo-Truong-Cao-dang-Ky-thuat-Cao-Thang.png"
                alt="Logo" class="img-fluid" style="height: 50px;">
            <div class="d-flex gap-2" id="teacher-tabs">
                <a href="{{ route('teacher.home') }}" class="btn nav-button text-dark">Thông tin giáo viên</a>
                <a href="#" class="btn nav-button text-dark">Khóa học</a>
            </div>
            <div>
                <a href="{{ route('login') }}" class="btn btn-outline-dark d-flex align-items-center gap-2 btn-logout"
                    onclick="clearActiveTabs()">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </nav>
    </header>
    <!-- MAIN CONTENT -->
    <main class="flex-grow-1 py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('#teacher-tabs a');
            // Lấy route đã được lưu trước đó từ localStorage
            const activeRoute = localStorage.getItem('activeTeacherTab');
            if (activeRoute) {
                buttons.forEach(btn => {
                    if (btn.getAttribute('href') === activeRoute) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
            }
            // Khi người dùng bấm vào button, lưu lại route và gán class active
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    localStorage.setItem('activeTeacherTab', this.getAttribute('href'));
                });
            });
        });

        function clearActiveTabs() {
            localStorage.removeItem('activeTabRoute'); // student
            localStorage.removeItem('activeTeacherTab'); // teacher
            localStorage.removeItem('activeDropdownItem'); // Dropdown
        }
    </script>
    @yield('js');
</body>

</html>
