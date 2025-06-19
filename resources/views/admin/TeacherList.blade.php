@extends('layouts.admin')

@section('title', 'QUẢN LÝ GIÁO VIÊN')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container-fluid {
            padding: 20px;
        }

        .main-container {
            max-width: 90%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.3;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.1;
            }
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 30px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #4f46e5;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }

        .controls {
            padding: 30px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .controls-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 20px 12px 50px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .search-box input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .filter-group {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 150px;
        }

        .filter-select:focus {
            outline: none;
            border-color: #4f46e5;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .btn-outline-secondary {
            background: white;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .btn-outline-secondary:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .btn-reset {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
            color: white;
        }

        .btn-reset:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        }

        .table-container {
            padding: 30px;
            overflow-x: auto;
        }

        .student-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .student-table th {
            background: linear-gradient(135deg, #1e293b, #334155);
            color: white;
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .student-table td {
            padding: 18px 15px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .student-table tbody tr {
            transition: all 0.3s ease;
        }

        .student-table tbody tr:hover {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            transform: scale(1.01);
        }

        .student-info {
            display: flex;
            align-items: center;
        }

        .student-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .student-details {
            flex: 1;
        }

        .student-name {
            font-weight: 600;
            font-size: 16px;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .student-id {
            color: #64748b;
            font-size: 14px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .status-active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .status-inactive {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

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

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
        }

        .form-check-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border-radius: 20px 20px 0 0;
            border-bottom: none;
        }

        .modal-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .pagination {
            justify-content: center;
            margin-top: 30px;
        }

        .page-link {
            border: 2px solid #e2e8f0;
            color: #4f46e5;
            padding: 10px 15px;
            margin: 0 3px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .page-link:hover,
        .page-item.active .page-link {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-color: #4f46e5;
            color: white;
            transform: translateY(-1px);
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }

        .toast {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .controls-row {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: auto;
                margin-bottom: 15px;
            }

            .filter-group {
                justify-content: center;
                flex-wrap: wrap;
            }

            .student-table {
                font-size: 14px;
            }

            .student-table th,
            .student-table td {
                padding: 12px 8px;
            }

            .student-avatar {
                width: 50px;
                height: 50px;
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="main-container">
            <!-- Header -->
            <div class="header">
                <h1><i class="fas fa-graduation-cap"></i> Quản lý Giáo viên</h1>
            </div>

            <!-- Statistics -->
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number">{{ $total }}</div>
                    <div class="stat-label">Tổng số giáo viên</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $active }}</div>
                    <div class="stat-label">Tài khoản hoạt động</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $inactive }}</div>
                    <div class="stat-label">Tài khoản bị khóa</div>
                </div>
            </div>

            <!-- Controls -->
            <div class="controls">
                <form method="GET" action="{{ route('admin.teacherlist') }}" id="studentFilterForm">
                    <div class="controls-row">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                placeholder="Tìm kiếm theo tên...">
                        </div>

                        <div class="filter-group">
                            <select class="filter-select" name="status" id="statusFilter">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã khóa
                                </option>
                            </select>

                            <select class="filter-select" name="gender" id="genderFilter">
                                <option value="">Tất cả giới tính</option>
                                <option value="1" {{ request('gender') === '1' ? 'selected' : '' }}>Nam</option>
                                <option value="0" {{ request('gender') === '0' ? 'selected' : '' }}>Nữ</option>
                            </select>

                            <a href="{{ route('admin.teacherlist') }}" class="btn btn-reset">
                                <i class="fas fa-undo"></i> Đặt lại
                            </a>
                        </div>
                    </div>

                    <div class="controls-row">
                        <div></div>
                        <div class="filter-group">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#addStudentModal">
                                <i class="fas fa-plus"></i> Thêm giáo viên
                            </button>
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-download"></i> Xuất Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="student-table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Thông tin giáo viên</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teachers as $teacher)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input student-checkbox"
                                        value="{{ $teacher->student_id }}">
                                </td>
                                <td>
                                    <div class="student-info">
                                        <img src="{{ asset('uploads/avatars/' . $teacher->avatar) }}" alt="Avatar"
                                            class="student-avatar me-3"
                                            onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/AvtMacDinh.jpg') }}';">
                                        <div>
                                            <div class="student-details">
                                                <div class="student-name">{{ $teacher->fullname }}</div>
                                                <div class="student-id">MSGV: {{ $teacher->teacher_id }}</div>
                                            </div>
                                        </div>
                                </td>
                                <td><strong style="color: #4f46e5;">{{ $teacher->username }}</strong></td>
                                <td>{{ $teacher->email }}</td>
                                <td>{{ \Carbon\Carbon::parse($teacher->date_of_birth)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="gender-badge {{ $teacher->gender->GetBadge() }}">
                                        <strong>{{ $teacher->gender->getLabel() }}</strong>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $teacher->is_status->badgeClass() }}"
                                        id="status-badge-{{ $teacher->teacher_id }}"
                                        onclick="prepareConfirmAction({{ $teacher->teacher_id }})">
                                        {{ $teacher->is_status->getStatus() }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="padding: 0 30px 30px;">
                {{ $teachers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Add teacher Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>Thêm giáo viên mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.teachers.add') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Họ tên -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" class="form-control" name="fullname" value="{{ old('fullname') }}">
                                @error('fullname')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Ngày sinh -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh *</label>
                                <input type="date" class="form-control" name="date_of_birth"
                                    value="{{ old('date_of_birth') }}" max="{{ now()->toDateString() }}">
                                @error('date_of_birth')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Giới tính -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính *</label>
                                <select class="form-select" name="gender">
                                    <option value="">Chọn giới tính</option>
                                    <option value="0" {{ old('gender') === '0' ? 'selected' : '' }}>Nữ</option>
                                    <option value="1" {{ old('gender') === '1' ? 'selected' : '' }}>Nam</option>
                                </select>
                                @error('gender')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Trạng thái -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái *</label>
                                <select class="form-select" name="is_status">
                                    <option value="0" {{ old('is_status') === '0' ? 'selected' : '' }}>Không hoạt
                                        động
                                    </option>
                                    <option value="1" {{ old('is_status') === '1' ? 'selected' : '' }}>Hoạt động
                                    </option>
                                </select>
                                @error('is_status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Avatar -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Ảnh đại diện: </label>
                                <input type="file" class="form-control" name="avatar" accept="image/*">
                                @error('avatar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Thêm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-question-circle me-2"></i>Xác nhận thay đổi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" style="color: #64748b;">Bạn có chắc chắn muốn thay đổi trạng thái không?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Hủy bỏ
                    </button>
                    <button type="button" class="btn btn-primary" onclick="confirmAction()">
                        <i class="fas fa-check me-1"></i>Xác nhận
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast thông báo -->
    <div class="toast-container">
        <div id="successToast" class="toast" role="alert">
            <div class="toast-header bg-success text-white border-0">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Thành công</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Trạng thái đã được thay đổi thành công!
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Khai báo biến toàn cục
        let pendingAction = null;

        // Hiển thị modal thêm sinh viên nếu có lỗi validation
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('addStudentModal'));
                myModal.show();
            });
        @endif

        // Xử lý form tìm kiếm và lọc dữ liệu
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('studentFilterForm');
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const genderFilter = document.getElementById('genderFilter');
            let typingTimer;
            const doneTypingInterval = 500;

            // Lọc theo trạng thái và giới tính
            statusFilter.addEventListener('change', () => form.submit());
            genderFilter.addEventListener('change', () => form.submit());

            // Tìm kiếm với debounce
            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => form.submit(), doneTypingInterval);
            });

            searchInput.addEventListener('keydown', function() {
                clearTimeout(typingTimer);
            });
        });

        // Chọn tất cả checkbox
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Xử lý xác nhận thay đổi trạng thái
        function confirmAction() {
            if (pendingAction) {
                pendingAction();
                pendingAction = null;
            }
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            modal.hide();
        }

        function prepareConfirmAction(teacher_id) {
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();

            pendingAction = () => {
                fetch(`/admin/teachers/${teacher_id}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const badge = document.getElementById(`status-badge-${teacher_id}`);
                            badge.textContent = data.new_status_text;
                            badge.className = `status-badge ${data.badge_class}`;
                            showToast('Trạng thái đã được thay đổi thành công!', 'success');
                        } else {
                            showToast('Đã xảy ra lỗi khi cập nhật trạng thái.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('Không thể kết nối đến máy chủ.', 'error');
                    });
            };
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('successToast');
            const toastHeader = toast.querySelector('.toast-header');
            const toastMessage = document.getElementById('toastMessage');

            if (type === 'success') {
                toastHeader.className = 'toast-header bg-success text-white border-0';
                toastHeader.innerHTML =
                    '<i class="fas fa-check-circle me-2"></i><strong class="me-auto">Thành công</strong><button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>';
            } else {
                toastHeader.className = 'toast-header bg-danger text-white border-0';
                toastHeader.innerHTML =
                    '<i class="fas fa-exclamation-circle me-2"></i><strong class="me-auto">Thất bại</strong><button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>';
            }

            toastMessage.textContent = message;

            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
    </script>
@endsection
