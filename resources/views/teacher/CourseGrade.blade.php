@extends('layouts.teacher')

@section('styles')
    <style>
        .members-container {
            width: 90%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .search-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem;
            background: rgba(248, 250, 252, 0.8);
            border-radius: 15px;
            margin-bottom: 1rem;
        }

        .search-input-group {
            display: flex;
            gap: 1rem;
            flex: 1;
            min-width: 300px;
        }

        .search-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-search {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
            min-width: 150px;
        }

        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .members-table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            margin: 1rem 0;
        }

        .table-header {
            background: linear-gradient(135deg, #374151, #4b5563);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .members-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .members-table th {
            background: #f8fafc;
            color: #374151;
            font-weight: 600;
            padding: 1rem 1.5rem;
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
        }

        .members-table td {
            padding: 1rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .members-table tbody tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .members-table tbody tr:nth-child(even) {
            background: rgba(248, 250, 252, 0.5);
        }

        .input-grade {
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
            text-align: center;
            width: 80px;
        }

        .input-grade:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .input-grade:disabled {
            background: #f3f4f6;
            border-color: #d1d5db;
            cursor: not-allowed;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .badge-male {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .badge-female {
            background: rgba(236, 72, 153, 0.1);
            color: #be185d;
        }

        .btn-warning,
        .btn-success {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
        }

        .btn-warning:hover,
        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .alert {
            border-radius: 8px;
            padding: 1rem 1.5rem;
            margin: 1rem 0;
            border: none;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border-left: 4px solid #10b981;
        }

        /* Course Navigation */
        .course-navigation-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .course-navigation-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 1.5rem 2rem;
            position: relative;
        }

        .course-info-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .course-info-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .course-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
        }

        .course-code {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            display: inline-block;
            margin-top: 0.25rem;
        }

        .nav-tabs-wrapper {
            display: flex;
            background: white;
        }

        .nav-tab {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            text-decoration: none;
            color: #6b7280;
            font-weight: 500;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .nav-tab:hover {
            color: #3b82f6;
            background: rgba(59, 130, 246, 0.05);
        }

        .nav-tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
            background: rgba(59, 130, 246, 0.05);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .members-container {
                width: 95%;
                border-radius: 15px;
            }

            .search-controls {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .search-input-group {
                min-width: 100%;
                flex-direction: column;
            }

            .members-table-container {
                overflow-x: auto;
            }

            .members-table {
                min-width: 700px;
            }

            .course-navigation-header {
                padding: 1rem 1.5rem;
            }

            .nav-tab {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .nav-tab-text {
                display: none;
            }
        }

        .d-flex.gap-2>* {
            margin-left: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Course Navigation Bar -->
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
                        <i class="fas fa-bullhorn"></i>
                        <span class="nav-tab-text">Bảng tin</span>
                    </a>

                    <a href="{{ route('teacher.coursemembers', $course->course_id) }}"
                        class="nav-tab {{ request()->routeIs('teacher.coursemembers') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="nav-tab-text">Quản lý sinh viên</span>
                    </a>

                    <a href="{{ route('teacher.grade', $course->course_id) }}"
                        class="nav-tab {{ request()->routeIs('teacher.grade') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span class="nav-tab-text">Quản lý điểm</span>
                    </a>
                </div>
            </nav>
        </div>
        @include('partials.alerts')
        <!-- Main Content -->
        <div class="members-container">
            <!-- Search Form -->
            <form method="GET" action="{{ route('teacher.grade', $course->course_id) }}">
                <div class="search-controls">
                    <div class="search-input-group">
                        <input type="text" name="search" class="search-input" placeholder="Tìm kiếm sinh viên..."
                            value="{{ request('search') }}">
                        <button class="btn-search" type="submit">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <select class="filter-select" name="gender" onchange="this.form.submit()">
                            <option value="">Tất cả giới tính</option>
                            <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>Nam</option>
                            <option value="0" {{ request('gender') == '0' ? 'selected' : '' }}>Nữ</option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Grade Table -->
            <div class="members-table-container">
                <div class="table-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Quản lý điểm số
                    </h5>
                    <span class="badge bg-primary">{{ $studentgrades->count() }} sinh viên</span>
                </div>


                <div class="p-3 text-end d-flex justify-content-end align-items-center gap-2">
                    <form method="GET" action="{{ route('teacher.exportCourseGrade', $course->course_id) }}">
                        <button type="submit" class="btn btn-success">
                            💾 Xuất bảng điểm
                        </button>
                    </form>

                    <button type="button" id="enable-edit" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa điểm
                    </button>
                </div>


                <form method="POST" action="{{ route('teacher.updategrade', $course->course_id) }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table members-table">
                            <thead>
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Giới tính</th>
                                    <th>Nghe</th>
                                    <th>Nói</th>
                                    <th>Viết</th>
                                    <th>Đọc</th>
                                    <th>Ngày cập nhật</th>
                                    <th>Kết quả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($studentgrades as $studentgrade)
                                    @php
                                        $studentId = $studentgrade->student->student_id;
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">
                                            <div>{{ $studentgrade->student->fullname }}</div>
                                            <div class="text-muted small">MSSV: {{ $studentId }}</div>
                                        </td>
                                        <td>{{ $studentgrade->student->email }}</td>
                                        <td>
                                            <span
                                                class="status-badge {{ $studentgrade->student->gender->value == 1 ? 'badge-male' : 'badge-female' }}">
                                                {{ $studentgrade->student->gender->value == 1 ? 'Nam' : 'Nữ' }}
                                            </span>
                                        </td>

                                        <input type="hidden" name="grades[{{ $studentId }}][student_id]"
                                            value="{{ $studentId }}">

                                        {{-- Nghe --}}
                                        <td>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="input-grade @error("grades.$studentId.listening_score") is-invalid @enderror"
                                                name="grades[{{ $studentId }}][listening_score]"
                                                value="{{ old("grades.$studentId.listening_score", $studentgrade->examResult->listening_score ?? '0') }}"
                                                disabled>
                                            @error("grades.$studentId.listening_score")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </td>

                                        {{-- Nói --}}
                                        <td>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="input-grade @error("grades.$studentId.speaking_score") is-invalid @enderror"
                                                name="grades[{{ $studentId }}][speaking_score]"
                                                value="{{ old("grades.$studentId.speaking_score", $studentgrade->examResult->speaking_score ?? '0') }}"
                                                disabled>
                                            @error("grades.$studentId.speaking_score")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </td>

                                        {{-- Viết --}}
                                        <td>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="input-grade @error("grades.$studentId.writing_score") is-invalid @enderror"
                                                name="grades[{{ $studentId }}][writing_score]"
                                                value="{{ old("grades.$studentId.writing_score", $studentgrade->examResult->writing_score ?? '0') }}"
                                                disabled>
                                            @error("grades.$studentId.writing_score")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </td>

                                        {{-- Đọc --}}
                                        <td>
                                            <input type="number" step="0.1" min="0" max="10"
                                                class="input-grade @error("grades.$studentId.reading_score") is-invalid @enderror"
                                                name="grades[{{ $studentId }}][reading_score]"
                                                value="{{ old("grades.$studentId.reading_score", $studentgrade->examResult->reading_score ?? '0') }}"
                                                disabled>
                                            @error("grades.$studentId.reading_score")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </td>

                                        {{-- Ngày thi --}}
                                        <td>
                                            <input type="text"
                                                class="input-grade @error("grades.$studentId.exam_date") is-invalid @enderror"
                                                style="width: 130px;" name="grades[{{ $studentId }}][exam_date]"
                                                value="{{ old("grades.$studentId.exam_date", optional($studentgrade->examResult)->exam_date ? \Carbon\Carbon::parse($studentgrade->examResult->exam_date)->format('d/m/Y') : '') }}"
                                                readonly>
                                            @error("grades.$studentId.exam_date")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </td>

                                        {{-- Kết quả --}}
                                        <td>
                                            @if ($studentgrade->examResult)
                                                <span
                                                    class="badge {{ $studentgrade->examResult->overall_status == 1 ? 'text-bg-success' : 'text-bg-danger' }}">
                                                    {{ $studentgrade->examResult->overall_status == 1 ? 'Đạt' : 'Không đạt' }}
                                                </span>
                                            @else
                                                <span class="badge text-bg-secondary">Chưa thi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="empty-state">
                                                @if (request('search') ||  request('gender') !== null)
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

                    <div class="p-3 text-end">
                        <button type="submit" id="save-all" class="btn btn-success" style="display:none">
                            <i class="fas fa-save me-1"></i> Lưu tất cả
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const enableBtn = document.getElementById('enable-edit');
            const saveBtn = document.getElementById('save-all');
            const inputs = document.querySelectorAll('.input-grade');

            enableBtn.addEventListener('click', function() {
                // Enable all inputs
                inputs.forEach(input => {
                    input.disabled = false;
                });

                // Update button states
                enableBtn.disabled = true;
                enableBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang chỉnh sửa...';
                enableBtn.classList.remove('btn-warning');
                enableBtn.classList.add('btn-secondary');

                // Show save button
                saveBtn.style.display = 'inline-block';
            });

            // Re-enable inputs before form submission
            document.querySelector('form[method="POST"]').addEventListener('submit', function() {
                inputs.forEach(input => {
                    input.disabled = false;
                });
            });
        });
    </script>
@endsection
