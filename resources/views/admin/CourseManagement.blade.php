@extends('layouts.admin')

@section('title', 'Quản lý khóa học - Cao Thắng College')

@section('styles')
    <style>
        .course-management {
            background: #f8fafc;
            min-height: calc(100vh - 140px);
            width: 80%;
            margin: 0 auto;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--bg-white) 0%, #f1f5f9 100%);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stats-icon.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        }

        .stats-icon.success {
            background: linear-gradient(135deg, var(--success-color), #10b981);
        }

        .stats-icon.warning {
            background: linear-gradient(135deg, var(--warning-color), #f59e0b);
        }

        .stats-icon.info {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.875rem;
        }

        .action-card {
            background: var(--bg-white);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-color);
        }

        .action-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
        }

        .action-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .action-description {
            color: var(--text-light);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .btn-action {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 32px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
        }

        .btn-action:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        .course-table {
            background: var(--bg-white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 1.5rem 2rem;
            margin: 0;
        }

        .table-header h3 {
            margin: 0;
            font-weight: 600;
        }

        .filter-section {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            background: #f8fafc;
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-item {
            flex: 1;
            min-width: 200px;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: var(--transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        .btn-filter {
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-filter:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-reset {
            background: #6b7280;
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-reset:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .table-custom {
            margin: 0;
        }

        .table-custom th {
            background: #f8fafc;
            border: none;
            padding: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            border-bottom: 2px solid var(--border-color);
        }

        .table-custom td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background: #f8fafc;
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-completed {
            background: #f3f4f6;
            color: #374151;
        }

        .status-paused {
            background: #fef3c7;
            color: #92400e;
        }

        .level-badge {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .instructor-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .instructor-badge {
            background: #e5e7eb;
            color: #374151;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm-action {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            transition: var(--transition);
            border: none;
        }

        .btn-view {
            background: #06b6d4;
            color: white;
        }

        .btn-view:hover {
            background: #0891b2;
            transform: translateY(-1px);
        }

        .btn-edit {
            background: var(--warning-color);
            color: white;
        }

        .btn-edit:hover {
            background: #d97706;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: var(--danger-color);
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .pagination-wrapper {
            padding: 1rem 2rem;
            background: #f8fafc;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper nav {
            width: auto;
        }

        .pagination {
            justify-content: center;
            margin: 0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            transform: scale(0.8);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal-overlay.show .modal-content {
            transform: scale(1);
            opacity: 1;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 25px 30px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .modal-footer {
            padding: 20px 30px 30px;
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
            transform: translateY(-2px);
        }

        .required {
            color: #e53e3e;
        }

        /* Modal form styles - updated to avoid conflicts */
        .modal-body .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            display: block;
            font-size: 16px;
        }

        .modal-body .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .modal-body .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modal-body .form-select {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            background: #f7fafc;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-body .form-select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Animation */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            animation: slideInUp 0.5s ease forwards;
        }

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .form-group:nth-child(3) {
            animation-delay: 0.3s;
        }

        .form-group:nth-child(4) {
            animation-delay: 0.4s;
        }

        .form-group:nth-child(5) {
            animation-delay: 0.5s;
        }

        @media (max-width: 768px) {
            .filter-group {
                flex-direction: column;
            }

            .filter-item {
                min-width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .stats-number {
                font-size: 1.5rem;
            }

            .action-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="course-management">
        <div class="content-container">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Quản lý khóa học</h2>
                </div>
            </div>
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon primary">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="stats-number">24</div>
                        <div class="stats-label">Tổng khóa học</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon success">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="stats-number">18</div>
                        <div class="stats-label">Đang mở lớp</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon warning">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <div class="stats-number">3</div>
                        <div class="stats-label">Tạm dừng</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon info">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-number">3</div>
                        <div class="stats-label">Đã hoàn thành</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-title">Tạo khóa học mới</div>
                        <div class="action-description">Thêm khóa học mới vào hệ thống</div>
                        <button class="btn-action" onclick="openModal()">Tạo mới</button>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="action-title">Quản lý giảng viên</div>
                        <div class="action-description">Phân công giảng viên cho khóa học</div>
                        <a href="#" class="btn-action">Quản lý</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-3">
                    <div class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="action-title">Quản lý trình độ</div>
                        <div class="action-description">Xem và quản lý các trình độ</div>
                        <a href="#" class="btn-action">Xem tất cả</a>
                    </div>
                </div>
            </div>

            <!-- Course Table -->
            <div class="course-table">
                <div class="table-header">
                    <h3><i class="fas fa-list me-2"></i>Danh sách khóa học</h3>
                </div>

                <!-- Filters -->
                <form id="courseFilterForm" method="GET" action="{{ route('admin.courses') }}">
                    <div class="filter-section">
                        <div class="filter-group">
                            <div class="filter-item">
                                <label class="form-label">Tìm kiếm</label>
                                <input type="text" name="search" class="form-control" id="searchInput"
                                    placeholder="Nhập tên khóa học..." value="{{ request('search') }}">
                            </div>

                            <div class="filter-item">
                                <label class="form-label">Trình độ</label>
                                <select class="form-select" name="level" id="levelFilter">
                                    <option value="">Tất cả trình độ</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level }}"
                                            {{ request('level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-item">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="status" id="statusFilter">
                                    <option value="">Tất cả trạng thái</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}"
                                            {{ request('status') === $status->value ? 'selected' : '' }}>
                                            {{ $status->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-item">
                                <label class="form-label">Năm</label>
                                <select class="form-select" name="year" id="yearFilter">
                                    <option value="">Tất cả</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-item" style="min-width: auto;">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.courses') }}" class="btn-reset"
                                        style="text-decoration: none;">
                                        <i class="fas fa-undo"></i> Đặt lại
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Tên khóa học</th>
                                <th>Trình độ</th>
                                <th>Năm</th>
                                <th>Trạng thái</th>
                                <th>Ngày bắt đầu</th>
                                <th>Giảng viên</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Khóa học trực tiếp {{ $course->course_name }}</strong>
                                            <br><small class="text-muted">{{ $course->description }}</small>
                                        </div>
                                    </td>
                                    <td><span class="level-badge">{{ $course->lesson->level ?? 'N/A' }}</span></td>
                                    <td>{{ $course->year }}</td>
                                    <td>
                                        <span class="badge {{ $course->status->BagdeClass() }}">
                                            {{ $course->status }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($course->starts_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="instructor-list">
                                            @foreach ($course->teachers as $teacher)
                                                <span class="instructor-badge">{{ $teacher->fullname }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-sm-action btn-view" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-sm-action btn-edit" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-sm-action btn-delete" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Modal Popup -->
                <div class="modal-overlay" id="courseModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">
                                <i class="fas fa-graduation-cap me-2"></i>
                                Tạo khóa học mới
                            </h2>
                            <button class="close-btn" onclick="closeModal()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form id="courseForm" action="" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">
                                        Tên khóa học <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="courseName"
                                        placeholder="Nhập tên khóa học" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Cấp độ <span class="required">*</span>
                                    </label>
                                    <select class="form-select" id="courseLevel" name="level" required>
                                        <option value="">Chọn cấp độ</option>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level }}"
                                                {{ old('level') == $level ? 'selected' : '' }}>
                                                {{ $level }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Năm <span class="required">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="courseYear" name="year"
                                        inputmode="numeric" min="2020">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Mô tả
                                    </label>
                                    <textarea class="form-control" id="courseDescription" placeholder="Nhập mô tả về khóa học"></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Ngày bắt đầu <span class="required">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="startDate" required>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal()">
                                <i class="fas fa-times me-2"></i>Hủy
                            </button>
                            <button type="button" class="btn btn-primary" onclick="saveCourse()">
                                <i class="fas fa-save me-2"></i>Lưu khóa học
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    <nav aria-label="Course pagination">
                        {{ $courses->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('courseFilterForm');
            const searchInput = document.getElementById('searchInput');
            const levelFilter = document.getElementById('levelFilter');
            const statusFilter = document.getElementById('statusFilter');
            const yearFilter = document.getElementById('yearFilter');

            let typingTimer;
            const doneTypingInterval = 500;

            [levelFilter, statusFilter, yearFilter].forEach(select => {
                select.addEventListener('change', () => form.submit());
            });

            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => form.submit(), doneTypingInterval);
            });

            searchInput.addEventListener('keydown', function() {
                clearTimeout(typingTimer);
            });
        });

        // Set default date to today
        document.getElementById('startDate').value = new Date().toISOString().split('T')[0];

        function openModal() {
            const modal = document.getElementById('courseModal');
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
            
            // Luôn gán năm hiện tại mỗi lần mở
            const yearInput = document.getElementById('courseYear');
            const currentYear = new Date().getFullYear();
            yearInput.value = currentYear;
        }

        function closeModal() {
            const modal = document.getElementById('courseModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
                resetForm();
            }, 300);
        }

        function resetForm() {
            document.getElementById('courseForm').reset();
            document.getElementById('startDate').value = new Date().toISOString().split('T')[0];
        }
        // Close modal when clicking outside
        document.getElementById('courseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

    </script>
@endsection
