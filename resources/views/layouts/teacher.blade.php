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
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --header-height: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
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
            overflow-x: hidden;
            padding-top: var(--header-height);
        }

        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-md);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            width: 100%;
            height: var(--header-height);
            transition: var(--transition);
        }

        .header.scrolled {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            box-shadow: var(--shadow-lg);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            max-width: 100%;
            margin: 0 auto;
            gap: 8px;
            flex-wrap: wrap;
            height: 100%;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .logo {
            height: 40px;
            width: auto;
            object-fit: contain;
            border-radius: 6px;
            box-shadow: var(--shadow-sm);
        }

        .brand-text {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-dark);
            display: none;
        }

        /* Navigation */
        .nav-tabs-container {
            display: flex;
            gap: 4px;
            background: rgba(255, 255, 255, 0.1);
            padding: 4px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
            order: 3;
            width: 100%;
            justify-content: center;
        }

        .nav-tabs-container::-webkit-scrollbar {
            display: none;
        }

        .nav-button {
            display: flex !important;
            align-items: center;
            gap: 6px;
            padding: 8px 12px !important;
            text-decoration: none !important;
            color: var(--text-light) !important;
            font-weight: 500 !important;
            font-size: 0.8rem !important;
            border-radius: 20px !important;
            transition: var(--transition) !important;
            border: none !important;
            background: transparent !important;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .nav-button:hover {
            color: var(--primary-color) !important;
            transform: translateY(-1px) !important;
        }

        .nav-button.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
            color: white !important;
            box-shadow: var(--shadow-md) !important;
        }

        .nav-button i {
            font-size: 0.9rem;
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            order: 2;
            flex-shrink: 0;
        }

        .notification-btn {
            position: relative;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
            color: white !important;
            border: none !important;
            border-radius: 50% !important;
            width: 40px !important;
            height: 40px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 1.1rem !important;
            cursor: pointer !important;
            transition: var(--transition) !important;
            box-shadow: var(--shadow-md) !important;
            flex-shrink: 0;
        }

        .notification-btn:hover {
            transform: scale(1.1) !important;
            box-shadow: var(--shadow-lg) !important;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            font-size: 0.65rem;
            padding: 2px 5px;
            border-radius: 8px;
            font-weight: 600;
            min-width: 16px;
            text-align: center;
        }

        /* Logout Button */
        .btn-logout {
            display: flex !important;
            align-items: center;
            gap: 6px;
            padding: 8px 12px !important;
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: white !important;
            text-decoration: none !important;
            border-radius: 20px !important;
            font-weight: 500 !important;
            font-size: 0.8rem !important;
            transition: var(--transition) !important;
            box-shadow: var(--shadow-sm) !important;
            border: none !important;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn-logout:hover {
            transform: translateY(-1px) !important;
            box-shadow: var(--shadow-lg) !important;
            background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
            color: white !important;
        }

        .btn-logout i {
            font-size: 0.9rem;
        }

        /* Notification Sidebar */
        .notification-sidebar {
            position: fixed;
            top: var(--header-height);
            right: -100%;
            width: 350px;
            height: calc(100vh - var(--header-height));
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
            color: white;
            box-shadow: -5px 0 20px rgba(0, 0, 0, 0.3);
            transition: right 0.3s ease;
            z-index: 999;
            display: flex;
            flex-direction: column;
        }

        .notification-sidebar.active {
            right: 0;
        }

        .notification-overlay {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: 100%;
            height: calc(100vh - var(--header-height));
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .notification-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .notification-header-sidebar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .notification-header-sidebar h4 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .notification-close {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .notification-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .notifications-container {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
        }

        .notification-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .notification-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(-5px);
        }

        .notification-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(to bottom, #ff6b6b, #feca57);
            border-radius: 0 2px 2px 0;
        }

        .notification-wrapper {
            display: flex;
            align-items: flex-start;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            gap: 10px;
        }

        .notification-title {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            margin: 0;
            flex: 1;
            word-break: break-word;
        }

        .notification-date {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
        }

        .notification-message {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.4;
            margin: 0;
            font-size: 0.9rem;
            word-wrap: break-word;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-message.expanded {
            display: block;
            -webkit-line-clamp: unset;
        }

        .read-more-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
            cursor: pointer;
            margin-top: 6px;
            padding: 0;
            text-decoration: underline;
            transition: color 0.3s ease;
        }

        .read-more-btn:hover {
            color: white;
        }

        .no-notifications {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            padding: 30px 15px;
            font-style: italic;
        }

        .no-notifications i {
            font-size: 2.5rem;
            margin-bottom: 10px;
            opacity: 0.5;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            position: relative;
            width: 100%;
            min-height: calc(100vh - var(--header-height));
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            :root {
                --header-height: 90px;
            }

            .brand-text {
                display: block;
            }

            .header-content {
                padding: 1rem 2rem;
                flex-wrap: nowrap;
            }

            .nav-tabs-container {
                order: 2;
                width: auto;
                gap: 8px;
                padding: 6px;
            }

            .nav-button {
                padding: 10px 20px !important;
                font-size: 0.9rem !important;
                gap: 8px;
            }

            .btn-logout {
                padding: 10px 20px !important;
                font-size: 0.9rem !important;
                gap: 8px;
            }

            .header-actions {
                order: 3;
                gap: 12px;
            }

            .notification-btn {
                width: 45px !important;
                height: 45px !important;
                font-size: 1.2rem !important;
            }

            .notification-sidebar {
                width: 450px;
            }

            .logo {
                height: 48px;
            }
        }

        @media (max-width: 576px) {
            :root {
                --header-height: 70px;
            }

            .header-content {
                padding: 0.5rem 0.75rem;
            }

            .nav-tabs-container {
                margin: 0 -0.75rem;
                padding: 4px 0.75rem;
                border-radius: 0;
            }

            .nav-button span {
                display: none;
            }

            .btn-logout span {
                display: none;
            }

            .notification-sidebar {
                width: 100vw;
                right: -100vw;
            }

            .notification-header-sidebar {
                padding: 12px 15px;
            }

            .notification-header-sidebar h4 {
                font-size: 1.1rem;
            }

            .notifications-container {
                padding: 12px;
            }

            .notification-item {
                padding: 12px;
            }

            .notification-date {
                font-size: 0.75rem;
            }

            .notification-title {
                font-size: 0.95rem;
            }

            .notification-message {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            :root {
                --header-height: 65px;
            }

            .header-content {
                padding: 0.5rem;
            }

            .nav-tabs-container {
                margin: 0 -0.5rem;
                padding: 4px 0.5rem;
            }

            .nav-button {
                padding: 6px 8px !important;
                font-size: 0.75rem !important;
            }

            .btn-logout {
                padding: 6px 8px !important;
                font-size: 0.75rem !important;
            }

            .notification-btn {
                width: 36px !important;
                height: 36px !important;
                font-size: 1rem !important;
            }

            .logo {
                height: 36px;
            }
        }

        /* Scrollbar */
        .notifications-container::-webkit-scrollbar {
            width: 6px;
        }

        .notifications-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .notifications-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        /* Prevent horizontal scroll */
        html,
        body {
            overflow-x: hidden;
        }

        /* Smooth scrolling for anchor links */
        @supports (scroll-behavior: smooth) {
            html {
                scroll-behavior: smooth;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Notification Overlay -->
    <div class="notification-overlay" id="notificationOverlay"></div>

    <!-- Notification Sidebar -->
    <div class="notification-sidebar" id="notificationSidebar">
        <div class="notification-header-sidebar">
            <h4><i class="fas fa-bell me-2"></i>Thông báo</h4>
            <button class="notification-close" id="notificationClose">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="notifications-container">
            @if (isset($notifications) && count($notifications) > 0)
                <div class="notifications-list">
                    @foreach ($notifications as $notification)
                        <div class="notification-item">
                            <div class="notification-wrapper">
                                <div class="notification-icon">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-header">
                                        <h6 class="notification-title">{{ $notification->title }}</h6>
                                        <span class="notification-date">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ \Carbon\Carbon::parse($notification->notification_date)->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="notification-message" data-full-text="{{ $notification->message }}">
                                        {!! Str::limit($notification->message, 150) !!}
                                    </p>
                                    @if (strlen($notification->message) > 150)
                                        <button class="read-more-btn" onclick="toggleMessage(this)">Đọc thêm</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-notifications">
                    <i class="fas fa-bell-slash"></i>
                    <p class="mb-0">Chưa có thông báo nào từ Ban Quản Trị</p>
                </div>
            @endif
        </div>
    </div>
    @yield('sidebar')

    <!-- HEADER -->
    <header class="header" id="header">
        <nav class="header-content">
            <div class="logo-container">
                <img src="https://englishcenter.caothang.edu.vn/templates/img/logo.png" alt="Logo" class="logo">
                <span class="brand-text"></span>
            </div>

            <div class="header-actions">
                <!-- Notification Button -->
                <button class="notification-btn" id="notificationBtn">
                    <i class="fas fa-bell"></i>
                    @if (isset($notifications) && count($notifications) > 0)
                        <span class="notification-badge">{{ count($notifications) }}</span>
                    @endif
                </button>
                <!-- Logout Button -->
                <a href="{{ route('login') }}" class="btn btn-outline-dark d-flex align-items-center gap-2 btn-logout"
                    onclick="clearActiveTabs()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng xuất</span>
                </a>
            </div>

            <div class="nav-tabs-container" id="student-tabs">
                <a href="{{ route('teacher.home') }}" class="btn nav-button text-dark">
                    <i class="fas fa-user"></i>
                    <span>Thông tin giảng viên</span>
                </a>
                <a href="{{ route('teacher.coursesopening') }}" class="btn nav-button text-dark">
                    <i class="fas fa-book"></i>
                    <span>Khóa Học</span>
                </a>
            </div>
        </nav>
    </header>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Header scroll effect
            const header = document.getElementById('header');
            let lastScrollTop = 0;

            function handleScroll() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }

                lastScrollTop = scrollTop;
            }

            // Throttle scroll event for better performance
            let ticking = false;

            function updateScrollState() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        handleScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            }

            window.addEventListener('scroll', updateScrollState, {
                passive: true
            });

            // Tab management
            const buttons = document.querySelectorAll('#student-tabs a');
            const activeRoute = localStorage.getItem('activeTabRoute');
            let matched = false;

            buttons.forEach(btn => {
                if (btn.getAttribute('href') === activeRoute) {
                    btn.classList.add('active');
                    matched = true;
                } else {
                    btn.classList.remove('active');
                }
            });

            if (!matched && buttons.length > 0) {
                buttons[0].classList.add('active');
                localStorage.setItem('activeTabRoute', buttons[0].getAttribute('href'));
            }

            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    localStorage.setItem('activeTabRoute', this.getAttribute('href'));
                });
            });

            // Notification sidebar
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationSidebar = document.getElementById('notificationSidebar');
            const notificationOverlay = document.getElementById('notificationOverlay');
            const notificationClose = document.getElementById('notificationClose');

            function openNotificationSidebar() {
                notificationSidebar.classList.add('active');
                notificationOverlay.classList.add('active');
                notificationBtn.classList.add('active');
                notificationBtn.querySelector('i').className = 'fas fa-times';
                document.body.style.overflow = 'hidden';
            }

            function closeNotificationSidebar() {
                notificationSidebar.classList.remove('active');
                notificationOverlay.classList.remove('active');
                notificationBtn.classList.remove('active');
                notificationBtn.querySelector('i').className = 'fas fa-bell';
                document.body.style.overflow = 'auto';
            }

            notificationBtn.addEventListener('click', function() {
                if (notificationSidebar.classList.contains('active')) {
                    closeNotificationSidebar();
                } else {
                    openNotificationSidebar();
                }
            });

            notificationClose.addEventListener('click', closeNotificationSidebar);
            notificationOverlay.addEventListener('click', closeNotificationSidebar);

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && notificationSidebar.classList.contains('active')) {
                    closeNotificationSidebar();
                }
            });

            notificationSidebar.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            // Prevent double-tap zoom on mobile
            let lastTouchEnd = 0;
            document.addEventListener('touchend', function(event) {
                const now = (new Date()).getTime();
                if (now - lastTouchEnd <= 300) {
                    event.preventDefault();
                }
                lastTouchEnd = now;
            }, false);
        });

        function clearActiveTabs() {
            localStorage.removeItem('activeTabRoute');
            localStorage.removeItem('activeTeacherTab');
            localStorage.removeItem('activeDropdownItem');
        }

        function toggleMessage(button) {
            const message = button.previousElementSibling;
            const fullText = message.getAttribute('data-full-text');
            const isExpanded = message.classList.contains('expanded');

            if (isExpanded) {
                message.classList.remove('expanded');
                message.textContent = fullText.substring(0, 150) + '...';
                button.textContent = 'Đọc thêm';
            } else {
                message.classList.add('expanded');
                message.textContent = fullText;
                button.textContent = 'Thu gọn';
            }
        }
    </script>

</body>

</html>
