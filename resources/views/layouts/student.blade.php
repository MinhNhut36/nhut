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

        /* Header Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Notification Button */
        .notification-btn {
            position: relative;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
            color: white !important;
            border: none !important;
            border-radius: 50% !important;
            width: 45px !important;
            height: 45px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 1.2rem !important;
            cursor: pointer !important;
            transition: var(--transition) !important;
            box-shadow: var(--shadow-md) !important;
        }

        .notification-btn:hover {
            transform: scale(1.1) !important;
            box-shadow: var(--shadow-lg) !important;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color)) !important;
            color: white !important;
        }

        .notification-btn.active {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color)) !important;
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
            min-width: 18px;
            text-align: center;
        }

        /* Notification Sidebar */
        .notification-sidebar {
            position: fixed;
            top: 0;
            right: -450px;
            width: 450px;
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
            color: white;
            box-shadow: -5px 0 20px rgba(0, 0, 0, 0.3);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
            padding: 20px;
        }

        .notification-sidebar.active {
            right: 0;
        }

        .notification-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
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
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .notification-header-sidebar h4 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .notification-close {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 8px;
            width: 40px;
            height: 40px;
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

        .notification-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .notification-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(-10px);
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
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
            color: #fff;
        }

        .notification-content {
            flex: 1;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .notification-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            margin: 0;
            flex: 1;
            padding-right: 15px;
        }

        .notification-date {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .notification-message {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
            margin: 0;
            font-size: 0.95rem;
        }

        .no-notifications {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            padding: 40px 20px;
            font-style: italic;
        }

        .no-notifications i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
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

        /* Animation */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .notification-item {
            animation: slideInRight 0.5s ease-out;
        }

        .notification-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .notification-item:nth-child(3) {
            animation-delay: 0.2s;
        }

        .notification-item:nth-child(4) {
            animation-delay: 0.3s;
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

            .notification-sidebar {
                width: 100%;
                right: -100%;
            }

            .notification-btn {
                width: 40px !important;
                height: 40px !important;
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
        
        @if(isset($notifications) && count($notifications) > 0)
            <div class="notifications-list">
                @foreach($notifications as $notification)
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
                                <p class="notification-message">{{ $notification->message }}</p>
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

    <!-- HEADER -->
    <header class="header sticky-top">
        <nav class="header-content">
            <div class="logo-container">
                <img src="https://englishcenter.caothang.edu.vn/templates/img/logo.png"
                    alt="Logo" class="logo">
                <span class="brand-text"></span>
            </div>

            <div class="nav-tabs-container" id="student-tabs">
                <a href="{{ route('student.home') }}" class="btn nav-button text-dark">
                    <i class="fas fa-user"></i>
                    <span>Thông tin sinh viên</span>
                </a>
                <a href="{{ route('student.courses') }}" class="btn nav-button text-dark">
                    <i class="fas fa-book"></i>
                    <span>Khóa Học</span>
                </a>
                <a href="{{ route('student.myCourses') }}" class="btn nav-button text-dark">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Đang học</span>
                </a>
            </div>

            <div class="header-actions">
                <!-- Notification Button -->
                <button class="notification-btn" id="notificationBtn">
                    <i class="fas fa-bell"></i>
                    @if(isset($notifications) && count($notifications) > 0)
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
            const buttons = document.querySelectorAll('#student-tabs a');

            // Lấy route đã lưu
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

            // Nếu không có tab nào được lưu (lần đầu vào) thì active tab đầu tiên
            if (!matched && buttons.length > 0) {
                buttons[0].classList.add('active');
                localStorage.setItem('activeTabRoute', buttons[0].getAttribute('href'));
            }

            // Gán sự kiện click để cập nhật active và lưu vào localStorage
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    localStorage.setItem('activeTabRoute', this.getAttribute('href'));
                });
            });

            // Notification Sidebar functionality
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationSidebar = document.getElementById('notificationSidebar');
            const notificationOverlay = document.getElementById('notificationOverlay');
            const notificationClose = document.getElementById('notificationClose');

            // Function to open notification sidebar
            function openNotificationSidebar() {
                notificationSidebar.classList.add('active');
                notificationOverlay.classList.add('active');
                notificationBtn.classList.add('active');
                notificationBtn.querySelector('i').className = 'fas fa-times';
                document.body.style.overflow = 'hidden';
            }

            // Function to close notification sidebar
            function closeNotificationSidebar() {
                notificationSidebar.classList.remove('active');
                notificationOverlay.classList.remove('active');
                notificationBtn.classList.remove('active');
                notificationBtn.querySelector('i').className = 'fas fa-bell';
                document.body.style.overflow = 'auto';
            }

            // Toggle notification sidebar
            notificationBtn.addEventListener('click', function() {
                if (notificationSidebar.classList.contains('active')) {
                    closeNotificationSidebar();
                } else {
                    openNotificationSidebar();
                }
            });

            // Close notification sidebar
            notificationClose.addEventListener('click', closeNotificationSidebar);
            notificationOverlay.addEventListener('click', closeNotificationSidebar);

            // Close sidebar with ESC key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && notificationSidebar.classList.contains('active')) {
                    closeNotificationSidebar();
                }
            });

            // Prevent sidebar from closing when clicking inside
            notificationSidebar.addEventListener('click', function(event) {
                event.stopPropagation();
            });

            // Add staggered animation to notification items
            const notificationItems = document.querySelectorAll('.notification-item');
            notificationItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
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
    </script>

</body>

</html>