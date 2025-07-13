@extends('layouts.admin')

@section('title', 'Phân công giảng dạy - Admin Dashboard')

@section('styles')
    <style>
        .course-assignment-container {
            max-width: 100%;
            margin: 0;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .course-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e1e8ed;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .course-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f3f7;
        }

        .course-info h3 {
            color: #2c3e50;
            font-size: 1.4rem;
            margin-bottom: 5px;
        }


        .course-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            font-size: 0.875rem;
            color: #64748b;
            margin: 16px 0;
            padding: 16px 0;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .course-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            font-weight: 500;
            color: #475569;
        }

        .course-meta span:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .course-meta span i {
            color: #6366f1;
            font-size: 0.8rem;
            width: 14px;
            text-align: center;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .course-meta {
                gap: 12px;
                font-size: 0.8rem;
            }

            .course-meta span {
                padding: 6px 8px;
                font-size: 0.8rem;
            }
        }

        .course-code {
            background: #3498db;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .add-teacher-form {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px dashed #dee2e6;
        }

        .form-row {
            display: flex;
            gap: 15px;
            align-items: end;
            flex-wrap: wrap;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            min-width: 200px;
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .form-control {
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-custom {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-danger-custom {
            background: #e74c3c;
            color: white;
        }

        .btn-danger-custom:hover {
            background: #c0392b;
            transform: translateY(-2px);
            color: white;
        }

        .teachers-list {
            min-height: 60px;
        }

        .teacher-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border: 2px solid #f1f3f4;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .teacher-item:hover {
            border-color: #667eea;
            box-shadow: 0 3px 12px rgba(102, 126, 234, 0.15);
        }

        .teacher-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .teacher-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .teacher-details h4 {
            color: #2c3e50;
            margin-bottom: 3px;
            font-size: 1.1rem;
        }

        .teacher-email {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .teacher-specialty {
            margin-top: 5px;
            font-size: 0.85rem;
            color: #666;
        }

        .position-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-right: 10px;
        }

        .position-main {
            background: #e3f2fd;
            color: #1976d2;
        }

        .position-assistant {
            background: #e8f5e8;
            color: #388e3c;
        }

        .position-mentor {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .position-support {
            background: #fff3e0;
            color: #f57c00;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }

        .empty-state::before {
            content: "👨‍🏫";
            font-size: 3rem;
            display: block;
            margin-bottom: 15px;
        }

        .alert-custom {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: none;
            font-weight: 500;
        }

        .alert-success-custom {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger-custom {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .form-row {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group {
                min-width: auto;
            }

            .teacher-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .teacher-info {
                width: 100%;
            }

            .teacher-actions {
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }
    </style>
@endsection

@section('content')

    <div class="course-assignment-container">
        <div class="page-header">
            <h1><i class="fas fa-chalkboard-teacher me-3"></i>Phân công giảng dạy</h1>
        </div>
        @include('partials.alerts')
        <div id="courses-container">
            @forelse($unassignedCourses as $course)
                @php
                    // Lấy danh sách giảng viên đã phân công cho khóa học hiện tại
                    $courseTeachers = $courseAssignments[$course->course_id] ?? [];
                @endphp
                <div class="course-card" data-course-id="{{ $course->course_id }}">
                    <div class="course-header">
                        <div class="course-info">
                            <h3>{{ $course->course_name }}</h3>
                            <div class="course-meta">
                                <span><i class="fas fa-clock me-1"></i> {{ $course->description }}</span>
                                <span><i class="fas fa-calendar me-1"></i> {{ $course->starts_date }}</span>
                                <span><i class="fas fa-graduation-cap"></i> {{ $course->level }}</span>
                                <span><i class="fas fa-tasks me-1"></i>{{ $course->status }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Phân công giảng dạy --}}
                    <form action="{{ route('admin.course.assign-teacher') }}" method="POST" class="add-teacher-form">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->course_id }}">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="teacher_id_{{ $course->course_id }}">
                                    <i class="fas fa-user me-1"></i>Chọn giảng viên
                                </label>
                                <select name="teacher_id" id="teacher_id_{{ $course->course_id }}" class="form-control"
                                    required>
                                    <option value="">-- Chọn giảng viên --</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->teacher_id }}">
                                            {{ $teacher->fullname }} ({{ $teacher->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="position_{{ $course->course_id }}">
                                    <i class="fas fa-user-tag me-1"></i>Chức vụ
                                </label>
                                <select name="role" id="position_{{ $course->course_id }}" class="form-control"
                                    required>
                                    <option value="Main Teacher">Giảng viên</option>
                                    <option value="Assistant Teacher">Trợ giảng</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn-custom btn-primary-custom">
                                    <i class="fas fa-plus"></i>Thêm giảng viên
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Danh sách giảng viên đã phân công --}}
                    <div class="teachers-list">
                        @if (count($courseTeachers) > 0)
                            @foreach ($courseTeachers as $assignment)
                                <div class="teacher-item">
                                    <div class="teacher-info">
                                        <div class="teacher-avatar">
                                            {{ strtoupper(substr($assignment['teacher']->fullname ?? 'N/A', 0, 2)) }}
                                        </div>
                                        <div class="teacher-details">
                                            <h4>{{ $assignment['teacher']->fullname ?? 'N/A' }}</h4>
                                            <div class="teacher-email">
                                                <i
                                                    class="fas fa-envelope me-1"></i>{{ $assignment['teacher']->email ?? 'N/A' }}
                                            </div>
                                            @if ($assignment['teacher']->specialty ?? false)
                                                <div class="teacher-specialty">
                                                    <i class="fas fa-star me-1"></i>{{ $assignment['teacher']->specialty }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="teacher-actions">
                                        @php
                                            $positionMap = [
                                                'Main Teacher' => [
                                                    'name' => 'Giảng viên',
                                                    'class' => 'position-main',
                                                ],
                                                'Assistant Teacher' => [
                                                    'name' => 'Trợ giảng',
                                                    'class' => 'position-assistant',
                                                ],
                                            ];
                                            $position = $positionMap[$assignment['position']] ?? [
                                                'name' => 'Không xác định',
                                                'class' => '',
                                            ];
                                        @endphp
                                        <span class="position-badge {{ $position['class'] }}">
                                            {{ $position['name'] }}
                                        </span>

                                        <form action="{{ route('admin.course.remove-teacher') }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa giảng viên này khỏi khóa học?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="course_id" value="{{ $course->course_id }}">
                                            <input type="hidden" name="teacher_id"
                                                value="{{ $assignment['teacher']->teacher_id }}">
                                            <button type="submit" class="btn-custom btn-danger-custom">
                                                <i class="fas fa-trash"></i>Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                Chưa có giảng viên nào được phân công cho khóa học này
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="course-card">
                    <div class="empty-state">
                        <i class="fas fa-book-open" style="font-size: 3rem; margin-bottom: 15px; color: #ccc;"></i>
                        <p>Chưa có khóa học nào trong hệ thống</p>
                        <a href="{{ route('admin.courses') }}" class="btn-custom btn-primary-custom mt-3">
                            <i class="fas fa-plus"></i>Thêm khóa học mới
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert-custom');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });

            // Add loading state to forms
            const forms = document.querySelectorAll('.add-teacher-form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Đang thêm...';
                        submitBtn.disabled = true;
                    }
                });
            });

            // Enhanced form validation
            const teacherSelects = document.querySelectorAll('select[name="teacher_id"]');
            teacherSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const courseCard = this.closest('.course-card');
                    const courseId = courseCard.dataset.courseId;
                    const selectedTeacherId = this.value;

                    // Check if teacher is already assigned to this course
                    const assignedTeachers = courseCard.querySelectorAll(
                        '.teacher-item input[name="teacher_id"]');
                    let alreadyAssigned = false;

                    assignedTeachers.forEach(input => {
                        if (input.value === selectedTeacherId) {
                            alreadyAssigned = true;
                        }
                    });

                    if (alreadyAssigned && selectedTeacherId) {
                        alert('giảng viên này đã được phân công cho khóa học!');
                        this.value = '';
                    }
                });
            });
        });
    </script>
@endsection
