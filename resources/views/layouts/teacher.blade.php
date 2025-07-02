<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Trang chủ')</title>

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
            --primary-color: #0ea5e9;
            --primary-light: #38bdf8;
            --primary-dark: #0284c7;
            --accent-color: #10b981;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(-45deg, #f5f5f5, #f8f9fa, #f0f0f0, #f3f4f6);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Modern Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            max-width: 100%;
            margin: 0 auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            height: 48px;
            width: 100%;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
        }

        .brand-text {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-dark);
            display: none;
        }

        /* Navigation Tabs */
        .nav-tabs-container {
            display: flex;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            padding: 6px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }

        .nav-button {
            display: flex !important;
            align-items: center;
            gap: 8px;
            padding: 10px 20px !important;
            text-decoration: none !important;
            color: var(--text-light) !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
            border-radius: 25px !important;
            transition: var(--transition) !important;
            position: relative;
            overflow: hidden;
            border: none !important;
            background: transparent !important;
        }

        .nav-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .nav-button:hover::before {
            left: 100%;
        }

        .nav-button:hover {
            color: var(--primary-color) !important;
            transform: translateY(-1px) !important;
            background: transparent !important;
            border: none !important;
        }

        .nav-button.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
            color: white !important;
            box-shadow: var(--shadow-md) !important;
            transform: translateY(-1px) !important;
            border: none !important;
        }

        .nav-button i {
            font-size: 1rem;
        }

        /* Logout Button */
        .btn-logout {
            display: flex !important;
            align-items: center;
            gap: 8px;
            padding: 10px 20px !important;
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: white !important;
            text-decoration: none !important;
            border-radius: 25px !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
            transition: var(--transition) !important;
            box-shadow: var(--shadow-sm) !important;
            border: none !important;
        }

        .btn-logout:hover {
            transform: translateY(-2px) !important;
            box-shadow: var(--shadow-lg) !important;
            background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
            color: white !important;
            border: none !important;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            position: relative;
            margin-top: var(--header-height);
        }

        .content-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Content Cards */
        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition);
        }

        .content-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            .brand-text {
                display: block;
            }

            .header-content {
                padding-left: 2rem;
                padding-right: 2rem;
            }

            .content-container {
                padding: 0 2rem;
            }
        }

        @media (max-width: 767px) {
            .nav-tabs-container {
                flex-wrap: wrap;
                gap: 4px;
            }

            .nav-button {
                padding: 8px 12px !important;
                font-size: 0.8rem !important;
            }

            .nav-button span {
                display: none;
            }

            .btn-logout {
                padding: 8px 12px !important;
                font-size: 0.8rem !important;
            }

            .btn-logout span {
                display: none;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Sidebar */

        /* Sidebar Container */
        .sidebar {
            position: fixed;
            top: 89px;
            /* Điều chỉnh theo chiều cao header */
            left: 0;
            width: 280px;
            height: calc(100vh - 89px);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-lg);
            transform: translateX(0);
            transition: var(--transition);
            z-index: 999;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 70px;
            transform: translateX(0);
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            position: relative;
            overflow: hidden;
        }

        .sidebar-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .sidebar-header:hover::before {
            left: 100%;
        }

        .sidebar-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .sidebar.collapsed .sidebar-header h5 {
            opacity: 0;
            transform: translateX(-20px);
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav .nav {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar-nav .nav-item {
            margin-bottom: 0.5rem;
        }

        .sidebar-link {
            display: flex !important;
            align-items: center;
            padding: 1rem 1.5rem !important;
            color: var(--text-dark) !important;
            text-decoration: none !important;
            font-weight: 500 !important;
            font-size: 0.95rem !important;
            transition: var(--transition) !important;
            position: relative;
            overflow: hidden;
            border-radius: 0 !important;
            border-left: 3px solid transparent;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            transition: width 0.3s ease;
            z-index: -1;
        }

        .sidebar-link:hover::before {
            width: 100%;
        }

        .sidebar-link:hover {
            color: white !important;
            transform: translateX(5px);
            border-left-color: var(--accent-color);
            background: transparent !important;
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light)) !important;
            color: white !important;
            border-left-color: var(--accent-color);
            transform: translateX(5px);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .sidebar-link i {
            font-size: 1.2rem;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
            transition: var(--transition);
        }

        .sidebar-link span {
            transition: var(--transition);
            white-space: nowrap;
        }

        /* Collapsed Sidebar Styles */
        .sidebar.collapsed .sidebar-link {
            padding: 1rem 0.75rem !important;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-link i {
            margin-right: 0;
            font-size: 1.4rem;
        }

        .sidebar.collapsed .sidebar-link span {
            display: none;
        }

        .sidebar.collapsed .sidebar-link:hover span {
            display: block;
            position: absolute;
            left: 70px;
            background: var(--text-dark);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            white-space: nowrap;
            z-index: 1001;
            font-size: 0.9rem;
        }

        .sidebar.collapsed .sidebar-link:hover span::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--text-dark);
        }

        /* Main Content Adjustment */
        .main-content {
            transition: var(--transition);
        }

        .sidebar.collapsed~.main-content {
            margin-left: 70px;
        }

        .sidebar.hidden~.main-content {
            margin-left: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .sidebar-toggle {
                display: block;
            }

            /* Overlay for mobile */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
                opacity: 0;
                visibility: hidden;
                transition: var(--transition);
            }

            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }
        }

        @media (min-width: 769px) {
            .sidebar-toggle {
                display: block;
            }
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <header class="header sticky-top">
        <nav class="header-content">
            <div class="logo-container">
                <img src="https://englishcenter.caothang.edu.vn/templates/img/logo.png" alt="Logo" class="logo">
                <span class="brand-text"></span>
            </div>

            <div class="nav-tabs-container" id="teacher-tabs">
                <a href="{{ route('teacher.home') }}"
                    class="btn nav-button text-dark {{ request()->routeIs('teacher.home') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Thông tin giáo viên</span>
                </a>
                <a href="{{ route('teacher.assignedcourseslist') }}"
                    class="btn nav-button text-dark {{ request()->routeIs('teacher.assignedcourseslist') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Khóa học</span>
                </a>
            </div>

            <div>
                <a href="{{ route('login') }}" class="btn btn-outline-dark d-flex align-items-center gap-2 btn-logout"
                    onclick="clearActiveTabs()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng xuất</span>
                </a>
            </div>
        </nav>
    </header>

    @yield('sidebar')


    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('#teacher-tabs a');

            // Lấy route đã lưu
            const activeRoute = localStorage.getItem('activeTeacherTab');
            let matched = false;

            buttons.forEach(btn => {
                if (btn.getAttribute('href') === activeRoute) {
                    btn.classList.add('active');
                    matched = true;
                } else {
                    btn.classList.remove('active');
                }
            });

            // Nếu không có tab nào được lưu (lần đầu vào) thì active tab đầu tiên
            if (!matched && buttons.length > 0) {
                buttons[0].classList.add('active');
                localStorage.setItem('activeTeacherTab', buttons[0].getAttribute('href'));
            }

            // Gán sự kiện click để cập nhật active và lưu vào localStorage
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    buttons.forEach(b => b.classList.remove('active'));

                    // Add active class to clicked button
                    this.classList.add('active');

                    // Save to localStorage
                    localStorage.setItem('activeTeacherTab', this.getAttribute('href'));
                });
            });

            // Add smooth scrolling
            document.documentElement.style.scrollBehavior = 'smooth';
        });

        function clearActiveTabs() {
            localStorage.removeItem('activeTabRoute'); // student
            localStorage.removeItem('activeTeacherTab'); // teacher
            localStorage.removeItem('activeDropdownItem'); // Dropdown

            // Add logout animation
            document.body.style.opacity = '0.8';
            document.body.style.transform = 'scale(0.98)';

            setTimeout(() => {
                document.body.style.opacity = '1';
                document.body.style.transform = 'scale(1)';
            }, 200);
        }




        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarLinks = document.querySelectorAll('.sidebar-link');


            // Lấy trạng thái sidebar từ localStorage
            const sidebarState = localStorage.getItem('sidebarState') || 'open';
            const isMobile = window.innerWidth <= 768;

            // Khởi tạo trạng thái sidebar
            if (isMobile) {
                sidebar.classList.add('hidden');
                toggleIcon.className = 'fas fa-bars';
            } else {
                if (sidebarState === 'collapsed') {
                    sidebar.classList.add('collapsed');
                    toggleIcon.className = 'fas fa-chevron-right';
                } else {
                    sidebar.classList.remove('collapsed', 'hidden');
                    toggleIcon.className = 'fas fa-chevron-left';
                }
            }



            // Đóng sidebar khi click overlay (mobile)
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('active');
                toggleIcon.className = 'fas fa-bars';
            });

            // Xử lý active link
            const currentPath = window.location.pathname;
            sidebarLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Lưu active link khi click
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Nếu là link thật (có href), không prevent default
                    if (this.getAttribute('href') !== '#') {
                        // Remove active from all links
                        sidebarLinks.forEach(l => l.classList.remove('active'));
                        // Add active to clicked link
                        this.classList.add('active');

                        // Đóng sidebar trên mobile sau khi click
                        if (window.innerWidth <= 768) {
                            setTimeout(() => {
                                sidebar.classList.add('hidden');
                                sidebar.classList.remove('open');
                                sidebarOverlay.classList.remove('active');
                                toggleIcon.className = 'fas fa-bars';
                            }, 200);
                        }
                    }
                });
            });

            // Xử lý responsive
            window.addEventListener('resize', function() {
                const isMobile = window.innerWidth <= 768;

                if (isMobile) {
                    // Chuyển sang mobile
                    if (!sidebar.classList.contains('hidden')) {
                        sidebar.classList.add('hidden');
                        sidebar.classList.remove('collapsed', 'open');
                        sidebarOverlay.classList.remove('active');
                        toggleIcon.className = 'fas fa-bars';
                    }
                } else {
                    // Chuyển sang desktop
                    sidebar.classList.remove('hidden', 'open');
                    sidebarOverlay.classList.remove('active');

                    const savedState = localStorage.getItem('sidebarState') || 'open';
                    if (savedState === 'collapsed') {
                        sidebar.classList.add('collapsed');
                        toggleIcon.className = 'fas fa-chevron-right';
                    } else {
                        sidebar.classList.remove('collapsed');
                        toggleIcon.className = 'fas fa-chevron-left';
                    }
                }
            });

            // Smooth hover effects
            sidebarLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'translateX(5px)';
                    }
                });

                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'translateX(0)';
                    }
                });
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                // ESC để đóng sidebar trên mobile
                if (e.key === 'Escape' && window.innerWidth <= 768) {
                    if (!sidebar.classList.contains('hidden')) {
                        sidebar.classList.add('hidden');
                        sidebar.classList.remove('open');
                        sidebarOverlay.classList.remove('active');
                        toggleIcon.className = 'fas fa-bars';
                    }
                }
            });
        });
    </script>

</body>

</html>
