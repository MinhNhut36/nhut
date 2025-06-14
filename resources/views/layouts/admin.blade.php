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
    <style>
        @yield('styles')

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .nav-button.active,
        .dropdown-item.active {
            background-color: #e6ddfb !important;
            color: #6f42c1 !important;
        }

        .nav-button:hover,
        .dropdown-item:hover {
            background-color: #e6ddfb !important;
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

            <!-- DROPDOWN BUTTONS -->
            <div class="d-flex gap-2">
                <!-- Dropdown Quản lý thông tin -->
                <div class="dropdown">
                    <button class="btn nav-button dropdown-toggle text-dark" type="button" id="dropdownThongTin"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Quản lý thông tin
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownThongTin">
                        <li><a class="dropdown-item" href="{{route('admin.studentlist')}}" data-id="sv">Quản lý sinh viên</a></li>
                        <li><a class="dropdown-item" href="#" data-id="gv">Quản lý giảng viên</a></li>
                    </ul>
                </div>

                <!-- Dropdown Quản lý khóa học -->
                <div class="dropdown">
                    <button class="btn nav-button dropdown-toggle text-dark" type="button" id="dropdownKhoaHoc"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Quản lý khóa học
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownKhoaHoc">
                        <li><a class="dropdown-item" href="#" data-id="list-kh">Danh sách khóa học</a></li>
                        <li><a class="dropdown-item" href="#" data-id="add-kh">Thêm khóa học</a></li>
                    </ul>
                </div>
            </div>

            <!-- Logout Button -->
            <div>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 btn-logout">
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

    <!-- Active Dropdown Item Script -->
    <script>
        @yield('js');
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.dropdown-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownItems = document.querySelectorAll('.dropdown-item');

            // Đọc từ localStorage
            const activeDropdownItemId = localStorage.getItem('activeDropdownItem');
            if (activeDropdownItemId) {
                dropdownItems.forEach(item => {
                    if (item.dataset.id === activeDropdownItemId) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }

            // Gán sự kiện click để lưu ID vào localStorage
            dropdownItems.forEach(item => {
                item.addEventListener('click', function() {
                    localStorage.setItem('activeDropdownItem', this.dataset.id);
                });
            });
        });

        // Hàm xóa active khi logout
        function clearActiveTabs() {
            localStorage.removeItem('activeTabRoute'); // Tabs sinh viên
            localStorage.removeItem('activeTeacherTab'); // Tabs giáo viên
            localStorage.removeItem('activeDropdownItem'); // Dropdown
        }
    </script>
</body>

</html>
