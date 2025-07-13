@extends('layouts.teacher')

@section('styles')
    <style>
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Course Navigation Styles - Giữ nguyên */
        .course-navigation-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            position: relative;
        }

        .course-navigation-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 20px 30px;
            position: relative;
            overflow: hidden;
        }

        .course-navigation-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .course-info-section {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 1;
        }

        .course-info-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .course-info-details {
            flex: 1;
        }

        .course-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 5px 0;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .course-code {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 12px;
            border-radius: 12px;
            display: inline-block;
        }

        .course-navigation-tabs {
            background: white;
            padding: 0;
            position: relative;
        }

        .nav-tabs-wrapper {
            display: flex;
            gap: 0;
            position: relative;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .nav-tabs-wrapper::-webkit-scrollbar {
            display: none;
        }

        .nav-tab {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 30px;
            text-decoration: none;
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            background: transparent;
            white-space: nowrap;
            flex-shrink: 0;
            min-width: 0;
        }

        .nav-tab:hover {
            color: var(--primary-color);
            background: rgba(14, 165, 233, 0.05);
            transform: translateY(-2px);
        }

        .nav-tab.active {
            color: var(--primary-color);
            background: rgba(14, 165, 233, 0.1);
            font-weight: 600;
        }

        .nav-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 2px 2px 0 0;
        }

        .nav-tab-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .nav-tab:hover .nav-tab-icon {
            transform: scale(1.1);
        }

        .nav-tab-text {
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .nav-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .nav-tab:hover::before {
            opacity: 0.05;
        }

        .nav-tab.active::before {
            opacity: 0.1;
        }

        @keyframes tabActivate {
            0% {
                transform: translateY(2px);
                opacity: 0.8;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .nav-tab.active {
            animation: tabActivate 0.3s ease;
        }

        /* Content Area */
        .content-wrapper {
            background: var(--bg-card);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            margin-top: 2rem;
        }

        /* Statistics Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            position: relative;
            overflow: hidden;
            transition: var(--transition);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(40px, -40px);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-info h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }

        .stat-info p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        /* Search and Filter Section */
        .search-section {
            background: var(--bg-card);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: var(--transition);
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
        }

        /* Table Section */
        .table-section {
            background: var(--bg-card);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .table-header {
            background: linear-gradient(135deg, var(--text-dark), #374151);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-header h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .members-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .members-table th {
            background: var(--bg-light);
            color: var(--text-dark);
            font-weight: 600;
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 2px solid var(--border-color);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .members-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            text-align: center;
        }

        .members-table tbody tr {
            transition: var(--transition);
        }

        .members-table tbody tr:hover {
            background: rgba(14, 165, 233, 0.02);
            transform: scale(1.001);
        }

        /* Student Info */
        .student-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .student-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border-color);
            transition: var(--transition);
        }

        .student-avatar:hover {
            transform: scale(1.1);
            border-color: var(--primary-color);
        }

        .student-details h6 {
            margin: 0;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1rem;
        }

        .student-id {
            color: var(--text-light);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        /* Status Badge */
        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-view {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-edit {
            background: linear-gradient(135deg, #d97706, #f59e0b);
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #b45309, #d97706);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 2rem;
            display: flex;
            justify-content: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 0 0.5rem;
            }

            .course-navigation-header {
                padding: 15px 20px;
            }

            .course-info-section {
                gap: 12px;
            }

            .course-info-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .course-name {
                font-size: 1.2rem;
            }

            .course-code {
                font-size: 0.8rem;
                padding: 3px 8px;
            }

            .nav-tab {
                padding: 15px 20px;
                font-size: 0.9rem;
            }

            .nav-tab-text {
                display: none;
            }

            .nav-tab-icon {
                font-size: 1.2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 1.5rem;
            }

            .search-form {
                flex-direction: column;
            }

            .form-group {
                min-width: 100%;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .members-table {
                min-width: 600px;
            }

            .student-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }
        }

        @media (max-width: 480px) {
            .course-navigation-header {
                padding: 12px 15px;
            }

            .course-info-section {
                gap: 10px;
            }

            .course-info-icon {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .course-name {
                font-size: 1.1rem;
            }

            .nav-tab {
                padding: 12px 15px;
            }

            .search-section {
                padding: 1rem;
            }

            .table-header {
                padding: 1rem 1.5rem;
            }

            .members-table th,
            .members-table td {
                padding: 0.75rem 1rem;
            }
        }

        /* Loading Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Hover Effects */
        .hover-lift {
            transition: var(--transition);
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* class giới tính */
        .gender-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .gender-male {
            background: #dbeafe;
            color: #1e40af;
        }

        .gender-female {
            background: #fce7f3;
            color: #be185d;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Course Navigation Bar - Giữ nguyên -->
        <div class="course-navigation-wrapper">
            <div class="course-navigation-header">
                <div class="course-info-section">
                    <div class="course-info-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="course-info-details">
                        <h4 class="course-name">{{ $course->course_name ?? 'Khóa học' }}</h4>
                    </div>
                </div>
            </div>

            <nav class="course-navigation-tabs">
                <div class="nav-tabs-wrapper">
                    <a href="{{ route('teacher.boards', $course->course_id) }}"
                        class="nav-tab {{ request()->routeIs('teacher.boards') ? 'active' : '' }}">
                        <div class="nav-tab-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <span class="nav-tab-text">Bảng tin</span>
                    </a>

                    <a href="{{ route('teacher.coursemembers', $course->course_id) }}"
                        class="nav-tab {{ request()->routeIs('teacher.coursemembers') ? 'active' : '' }}">
                        <div class="nav-tab-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-tab-text">Quản lý sinh viên</span>
                    </a>

                    <a href="{{ route('teacher.grade', $course->course_id) }}"
                        class="nav-tab {{ request()->routeIs('teacher.grade') ? 'active' : '' }}">
                        <div class="nav-tab-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="nav-tab-text">Quản lý điểm</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <div class="main-container">


        <!-- Statistics Section -->
        <div class="stats-grid fade-in-up">
            <div class="stat-card hover-lift">
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $countstudent }}</h3>
                        <p>Tổng sinh viên</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section fade-in-up">
            <form method="GET" action="{{ route('teacher.coursemembers', $course->course_id) }}" class="search-form">
                <div class="form-group">
                    <label for="search">Tìm kiếm sinh viên</label>
                    <input type="text" id="search" name="search" class="form-control"
                        placeholder="Nhập tên hoặc mã sinh viên..." value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label for="status">Trạng thái</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="gender">Giới tính</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="">Tất cả giới tính</option>
                        <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>Nam</option>
                        <option value="0" {{ request('gender') == '0' ? 'selected' : '' }}>Nữ</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>

        <!-- Members Table -->
        <div class="table-section fade-in-up">
            <div class="table-header">
                <h5><i class="fas fa-list"></i> Danh sách sinh viên</h5>
                <span class="table-badge">{{ $members->count() }} sinh viên</span>
            </div>

            <div class="table-responsive">
                <table class="members-table">
                    <thead>
                        <tr>
                            <th>Sinh viên</th>
                            <th>Email</th>
                            <th>Giới tính</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td>
                                    <div class="student-info">
                                        <img src="{{ asset('uploads/avatars/' . $member->avatar) }}" alt="Avatar"
                                            class="student-avatar"
                                            onerror="this.src='{{ asset('uploads/avatars/AvtMacDinh.jpg') }}'">
                                        <div class="student-details">
                                            <h6>{{ $member->student->fullname }}</h6>
                                            <div class="student-id">{{ $member->student->student_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $member->student->email }}</td>
                                <td>
                                    <span class="gender-badge {{ $member->student->gender->GetBadge() }}">
                                        {{ $member->student->gender->getLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $member->student->is_status->badgeClass() }}">
                                        {{ $member->student->is_status->getStatus() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('teacher.coursestudentdetails', ['courseId' => $course->course_id, 'studentId' => $member->student->student_id]) }}"
                                            class="btn-action btn-view" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="empty-state">
                                        @if (request('search') || request('status') !== null || request('gender') !== null)
                                            <i class="fas fa-search"></i>
                                            <h4>Không tìm thấy sinh viên</h4>
                                            <p>Không có sinh viên nào phù hợp với từ khóa tìm kiếm
                                                @if (request('search'))
                                                    "<strong>{{ request('search') }}</strong>"
                                                @endif
                                                trong lớp này.
                                            </p>
                                            <a href="{{ route('teacher.grade', $course->course_id) }}"
                                                class="btn-clear-search">
                                                <i class="fas fa-times"></i>
                                                Xóa bộ lọc
                                            </a>
                                        @else
                                            <i class="fas fa-users"></i>
                                            <h4>Chưa có sinh viên</h4>
                                            <p>Lớp học này chưa có sinh viên nào tham gia.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($members->hasPages())
                <div class="pagination-wrapper">
                    {{ $members->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation cho các elements
            const animateElements = document.querySelectorAll('.fade-in-up');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationDelay = '0.1s';
                        entry.target.classList.add('fade-in-up');
                    }
                });
            });

            animateElements.forEach(el => {
                observer.observe(el);
            });

            // Auto-submit form khi thay đổi select
            const selectElements = document.querySelectorAll('select[name="status"], select[name="gender"]');
            selectElements.forEach(select => {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            });

            // Smooth hover effects
            const hoverElements = document.querySelectorAll('.hover-lift');
            hoverElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                });

                element.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Table row hover effects
            const tableRows = document.querySelectorAll('.members-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = 'rgba(14, 165, 233, 0.02)';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'transparent';
                });
            });
        });
    </script>
@endsection
