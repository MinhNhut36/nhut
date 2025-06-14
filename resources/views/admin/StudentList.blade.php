@extends('layouts.admin')

@section('title', 'Quản lý sinh viên')

@section('styles')
    .student-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    }

    .student-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .student-avatar {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border: 4px solid #fff;
    border-radius: 50%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    }

    .status-active {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    }

    .status-inactive {
    background: linear-gradient(135deg, #6c757d, #adb5bd);
    color: white;
    border: none;
    }

    .gender-badge {
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
    border-radius: 12px;
    font-weight: 500;
    }

    .gender-female {
    background: linear-gradient(135deg, #e83e8c, #fd7e14);
    color: white;
    }

    .gender-male {
    background: linear-gradient(135deg, #007bff, #6f42c1);
    color: white;
    }

    .btn-action {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    transition: all 0.3s ease;
    margin: 0 2px;
    }

    .btn-action:hover {
    transform: scale(1.1);
    }

    .btn-view {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
    }

    .btn-edit {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    color: white;
    }

    .btn-delete {
    background: linear-gradient(135deg, #dc3545, #e83e8c);
    color: white;
    }

    .search-box {
    position: relative;
    max-width: 400px;
    }

    .search-box input {
    border-radius: 25px;
    padding-left: 45px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    }

    .search-box input:focus {
    border-color: #6f42c1;
    box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
    }

    .search-box .search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    }

    .filter-card {
    background: linear-gradient(135deg, #6f42c1, #007bff);
    color: white;
    border-radius: 15px;
    border: none;
    }

    .stats-card {
    border-radius: 15px;
    border: none;
    background: linear-gradient(135deg, #fff, #f8f9fa);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    }

    .icon-students {
    background: linear-gradient(135deg, #007bff, #6f42c1);
    color: white;
    }

    .icon-active {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    }

    .icon-inactive {
    background: linear-gradient(135deg, #dc3545, #e83e8c);
    color: white;
    }

    .page-header {
    background: linear-gradient(135deg, #6f42c1, #007bff);
    color: white;
    border-radius: 15px;
    margin-bottom: 2rem;
    }

    .table-responsive {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table th {
    background: linear-gradient(135deg, #6f42c1, #007bff);
    color: white;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    }

    .table td {
    vertical-align: middle;
    border-color: #f1f3f4;
    }

    .table tbody tr:hover {
    background-color: rgba(111, 66, 193, 0.05);
    }

    .pagination .page-link {
    border-radius: 10px;
    margin: 0 2px;
    border-color: #6f42c1;
    color: #6f42c1;
    }

    .pagination .page-link:hover,
    .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #6f42c1, #007bff);
    border-color: #6f42c1;
    color: white;
    }

    @media (max-width: 768px) {
    .student-card {
    margin-bottom: 1rem;
    }

    .btn-action {
    width: 30px;
    height: 30px;
    font-size: 0.8rem;
    }

    .student-avatar {
    width: 60px;
    height: 60px;
    }

@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header p-4 text-center">
        <h2 class="mb-2"><i class="fas fa-graduation-cap me-2"></i>Quản lý Sinh viên</h2>
    </div>
    <!-- Controls -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card filter-card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.studentlist') }}" id="studentFilterForm">
                        <div class="row align-items-center">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <div class="search-box">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" name="search" class="form-control" id="searchInput"
                                        value="{{ request('search') }}" placeholder="Tìm kiếm sinh viên...">
                                </div>
                            </div>
                            <div class="col-md-2 mb-2 mb-md-0">
                                <select class="form-select" name="status" id="statusFilter">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động
                                    </option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <select class="form-select" name="gender" id="genderFilter">
                                    <option value="">Tất cả giới tính</option>
                                    <option value="1" {{ request('gender') === '1' ? 'selected' : '' }}>Nam</option>
                                    <option value="0" {{ request('gender') === '0' ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.studentlist') }}" class="btn w-100"
                                    style="background-color: white; color: #212529; border: none;">
                                    <i class="fas fa-undo me-1"></i>
                                    Đặt lại
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="fas fa-plus me-2"></i>Thêm sinh viên
                </button>
                <button class="btn btn-outline-secondary btn-lg" onclick="exportData()">
                    <i class="fas fa-download me-2"></i>Xuất Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th scope="col">Thông tin sinh viên</th>
                            <th scope="col">Tên đăng nhập</th>
                            <th scope="col">Email</th>
                            <th scope="col">Ngày sinh</th>
                            <th scope="col">Giới tính</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        @foreach ($students as $student)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input student-checkbox"
                                        value="{{ $student->student_id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('images/' . $student->avatar) }}" alt="Avatar"
                                            class="student-avatar me-3">
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $student->fullname }}</h6>
                                            <small class="text-muted">ID: {{ $student->student_id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><strong class="text-primary">{{ $student->username }}</strong></td>
                                <td>{{ $student->email }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</td>
                                <td>
                                    <span
                                        class="gender-badge {{ $student->gender->getLabelStyles() }}">{{ $student->gender->getLabel() }}</span>
                                </td>
                                <td>
                                    <span
                                        class="status-badge {{ $student->is_status->badgeClass() }}">{{ $student->is_status->getStatus() }}</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-action btn-view" onclick="" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-action btn-edit" onclick="" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-action btn-delete" onclick="" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <nav aria-label="Student pagination" class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item ">
                {{ $students->links('pagination::bootstrap-5') }}
            </li>
        </ul>
    </nav>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #6f42c1, #007bff); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>Thêm sinh viên mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fullname" class="form-label">Họ và tên *</label>
                                <input type="text" class="form-control" id="fullname" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Tên đăng nhập *</label>
                                <input type="text" class="form-control" id="username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Ngày sinh *</label>
                                <input type="date" class="form-control" id="date_of_birth" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Giới tính *</label>
                                <select class="form-select" id="gender" required>
                                    <option value="">Chọn giới tính</option>
                                    <option value="0">Nữ</option>
                                    <option value="1">Nam</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Trạng thái *</label>
                                <select class="form-select" id="status" required>
                                    <option value="1">Hoạt động</option>
                                    <option value="0">Không hoạt động</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="avatar" class="form-label">Avatar</label>
                                <input type="file" class="form-control" id="avatar" accept="image/*">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="saveStudent()">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('studentFilterForm');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const genderFilter = document.getElementById('genderFilter');
    let typingTimer;
    const doneTypingInterval = 500; // milliseconds

    // Gửi form khi chọn filter
    statusFilter.addEventListener('change', () => form.submit());
    genderFilter.addEventListener('change', () => form.submit());

    // Gửi form khi người dùng ngừng gõ tìm kiếm
    searchInput.addEventListener('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => form.submit(), doneTypingInterval);
    });

    // Hủy gửi nếu đang gõ tiếp
    searchInput.addEventListener('keydown', function () {
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

    // Các hàm thao tác
    function viewStudent(id) {
    // Chuyển hướng đến trang xem chi tiết
    window.location.href = `/admin/students/${id}`;
    }

    function editStudent(id) {
    // Chuyển hướng đến trang chỉnh sửa
    window.location.href = `/admin/students/${id}/edit`;
    }

    function deleteStudent(id) {
    if (confirm('Bạn có chắc chắn muốn xóa sinh viên này?')) {
    // Gửi yêu cầu xóa
    fetch(`/admin/students/${id}`, {
    method: 'DELETE',
    headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'Content-Type': 'application/json',
    },
    })
    .then(response => response.json())
    .then(data => {
    if (data.success) {
    location.reload();
    }
    });
    }
    }

    function saveStudent() {
    const formData = new FormData();
    formData.append('fullname', document.getElementById('fullname').value);
    formData.append('username', document.getElementById('username').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('date_of_birth', document.getElementById('date_of_birth').value);
    formData.append('gender', document.getElementById('gender').value);
    formData.append('is_status', document.getElementById('status').value);

    const avatarFile = document.getElementById('avatar').files[0];
    if (avatarFile) {
    formData.append('avatar', avatarFile);
    }

    fetch('/admin/students', {
    method: 'POST',
    headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
    body: formData
    })
    .then(response => response.json())
    .then(data => {
    if (data.success) {
    location.reload();
    }
    });
    }

    function exportData() {
    window.location.href = '/admin/students/export';
    }

    // Hiệu ứng smooth cho các nút
    document.querySelectorAll('.btn-action').forEach(btn => {
    btn.addEventListener('click', function() {
    this.style.transform = 'scale(0.9)';
    setTimeout(() => {
    this.style.transform = '';
    }, 150);
    });
    });
@endsection
