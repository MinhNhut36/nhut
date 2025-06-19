<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard - Cao Thắng College')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png"
        href="https://cdn.haitrieu.com/wp-content/uploads/2023/01/Logo-Truong-Cao-dang-Ky-thuat-Cao-Thang.png">
    @yield('styles')
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles - Full Width */
        .admin-header {
            background: var(--bg-white);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        .admin-nav {
            padding: 1rem 2rem; /* Thay đổi từ container sang padding trực tiếp */
            width: 100%;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img {
            height: 48px;
            width: auto;
            border-radius: 8px;
            transition: var(--transition);
        }

        .logo-img:hover {
            transform: scale(1.05);
        }

        .brand-text {
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--text-dark);
            display: none;
        }

        @media (min-width: 768px) {
            .brand-text {
                display: block;
            }
        }

        /* Navigation Buttons */
        .nav-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-dropdown {
            position: relative;
        }

        .nav-button {
            background: var(--bg-white);
            border: 2px solid var(--border-color);
            color: var(--text-dark);
            font-weight: 500;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            text-decoration: none;
            min-width: 160px;
            justify-content: space-between;
        }

        .nav-button:hover,
        .nav-button.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .nav-button i {
            font-size: 1rem;
            transition: var(--transition);
        }

        .nav-button .fa-chevron-down {
            transition: transform 0.2s ease;
        }

        .nav-button[aria-expanded="true"] .fa-chevron-down {
            transform: rotate(180deg);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background: var(--bg-white);
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            padding: 8px;
            margin-top: 4px;
            min-width: 220px;
        }

        .dropdown-menu[data-bs-popper] {
            margin-top: 4px;
        }

        .dropdown-item {
            padding: 12px 16px;
            border-radius: 8px;
            color: var(--text-dark);
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .dropdown-item:hover,
        .dropdown-item.active {
            background: var(--primary-color);
            color: white;
            transform: translateX(4px);
            margin-bottom: 6px;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        /* Logout Button */
        .btn-logout {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            text-decoration: none;
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        /* Main Content - Full Width */
        .main-content {
            flex: 1;
            padding: 2rem;
            background: transparent;
            width: 100%;
        }

        .content-container {
            background: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            padding: 2rem;
            width: 100%; 
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-controls {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }

            .nav-button {
                width: 100%;
                min-width: auto;
            }

            .admin-nav {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem; /* Giảm padding trên mobile */
            }

            .content-container {
                margin: 0;
                padding: 1.5rem;
                border-radius: 8px;
            }

            .main-content {
                padding: 1rem; /* Giảm padding trên mobile */
            }
        }

        /* Loading Animation */
        .nav-button.loading {
            position: relative;
            pointer-events: none;
        }

        .nav-button.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 12px;
            width: 16px;
            height: 16px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Admin Badge */
        .admin-badge {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Glassmorphism effect for header */
        .admin-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Đảm bảo các element khác cũng full width */
        .container-fluid {
            padding: 0 !important;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header class="admin-header">
        <!-- Thay đổi từ <nav class="container"> thành <nav> với full width -->
        <nav>
            <div class="admin-nav d-flex align-items-center justify-content-between">
                <!-- Logo & Brand -->
                <div class="logo-container">
                    <img src="https://cdn.haitrieu.com/wp-content/uploads/2023/01/Logo-Truong-Cao-dang-Ky-thuat-Cao-Thang.png"
                        alt="Cao Thắng College Logo" class="logo-img">
                    <div class="brand-text">Admin Panel</div>
                    <span class="admin-badge">chào, {{Auth::user()->fullname;}}</span>
                </div>

                <!-- Navigation Controls -->
                <div class="nav-controls">
                    <!-- Dropdown Quản lý thông tin -->
                    <div class="nav-dropdown dropdown">
                        <button class="nav-button dropdown-toggle" type="button" id="dropdownThongTin"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span>
                                <i class="fas fa-users-cog me-2"></i>
                                Quản lý thông tin
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownThongTin">
                            <li>
                                <a class="dropdown-item" href="{{route('admin.studentlist')}}" data-id="sv">
                                    <i class="fas fa-user-graduate"></i>
                                    Quản lý sinh viên
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{route('admin.teacherlist')}}" data-id="gv">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    Quản lý giảng viên
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Dropdown Quản lý khóa học -->
                    <div class="nav-dropdown dropdown">
                        <button class="nav-button dropdown-toggle" type="button" id="dropdownKhoaHoc"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span>
                                <i class="fas fa-book-open me-2"></i>
                                Quản lý khóa học
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownKhoaHoc">
                            <li>
                                <a class="dropdown-item" href="#" data-id="list-kh">
                                    <i class="fas fa-list"></i>
                                    Danh sách các trình độ
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{route('admin.courses')}}" data-id="add-kh">
                                    <i class="fas fa-plus-circle"></i>
                                    Thêm khóa học
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Logout Button -->
                    <a href="{{ route('login') }}" class="btn-logout" onclick="clearActiveTabs()">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="d-none d-md-inline">Đăng xuất</span>
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <!-- MAIN CONTENT -->
    <main class="main-content">         
            @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Enhanced JavaScript -->
    <script>
        // Enhanced dropdown functionality with smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            const navButtons = document.querySelectorAll('.nav-button');

            // Load active state from localStorage
            const activeDropdownItemId = localStorage.getItem('activeDropdownItem');
            if (activeDropdownItemId) {
                dropdownItems.forEach(item => {
                    if (item.dataset.id === activeDropdownItemId) {
                        item.classList.add('active');
                        // Also mark parent dropdown as active
                        const parentDropdown = item.closest('.nav-dropdown').querySelector('.nav-button');
                        if (parentDropdown) {
                            parentDropdown.classList.add('active');
                        }
                    } else {
                        item.classList.remove('active');
                    }
                });
            }

            // Handle dropdown item clicks
            dropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    // Add loading state
                    const parentButton = this.closest('.nav-dropdown').querySelector('.nav-button');
                    if (parentButton) {
                        parentButton.classList.add('loading');
                    }

                    // Save active state
                    localStorage.setItem('activeDropdownItem', this.dataset.id);
                    
                    // Update active states
                    dropdownItems.forEach(i => i.classList.remove('active'));
                    navButtons.forEach(btn => btn.classList.remove('active'));
                    
                    this.classList.add('active');
                    if (parentButton) {
                        parentButton.classList.add('active');
                    }

                    // Remove loading state after navigation
                    setTimeout(() => {
                        if (parentButton) {
                            parentButton.classList.remove('loading');
                        }
                    }, 1000);
                });
            });
        });

        // Clear active tabs function (unchanged functionality)
        function clearActiveTabs() {
            localStorage.removeItem('activeTabRoute');
            localStorage.removeItem('activeTeacherTab');
            localStorage.removeItem('activeDropdownItem');
        }

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Add keyboard navigation support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close all dropdowns
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    const dropdown = bootstrap.Dropdown.getInstance(menu.previousElementSibling);
                    if (dropdown) {
                        dropdown.hide();
                    }
                });
            }
        });
    </script>
    
    @yield('js')
</body>

</html>