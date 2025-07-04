@extends('layouts.teacher')

@section('sidebar')
    @include('layouts.SidebarTeacher')
@endsection

@section('styles')
    <style>
        .members-container {
            padding: 0;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }

        .page-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
            position: relative;
            z-index: 1;
        }

        .page-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .members-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 8px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .stat-card:hover::before {
            left: 100%;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .search-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .search-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
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
            border: 2px solid var(--border-color);
            border-radius: 25px;
            font-size: 0.95rem;
            transition: var(--transition);
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        .btn-search {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .btn-search:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 25px;
            background: white;
            font-size: 0.95rem;
            transition: var(--transition);
            min-width: 150px;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }

        .members-table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table-header {
            background: linear-gradient(135deg, var(--text-dark), #374151);
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .members-table {
            width: 100%;
            margin: 0;
        }

        .members-table th {
            background: rgba(248, 250, 252, 0.8);
            color: var(--text-dark);
            font-weight: 600;
            padding: 1rem 1.5rem;
            border: none;
            font-size: 0.9rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .members-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            transition: var(--transition);
        }

        .members-table tbody tr:hover {
            background: rgba(14, 165, 233, 0.05);
            transform: scale(1.001);
        }

        .student-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            margin-right: 1rem;
            box-shadow: var(--shadow-sm);
            flex-shrink: 0;
        }

        .student-info {
            display: flex;
            align-items: center;
        }

        .student-details h6 {
            margin: 0;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1rem;
        }

        .student-details .student-id {
            color: var(--text-light);
            font-size: 0.85rem;
            margin-top: 0.2rem;
        }

        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-view,
        .btn-edit {
            padding: 0.6rem 1rem;
            border-radius: 0.5rem;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
        }

        /* View */
        .btn-view {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border-color: rgba(59, 130, 246, 0.8);
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #1e40af, #2563eb);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        /* Edit */
        .btn-edit {
            background: linear-gradient(135deg, #d97706, #f59e0b);
            border-color: rgba(249, 115, 22, 0.8);
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #b45309, #d97706);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .btn-action:hover {
            transform: translateY(-1px) scale(1.05);
            box-shadow: var(--shadow-md);
        }

        @media (max-width: 768px) {
            .search-controls {
                flex-direction: column;
                align-items: stretch;
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

            .student-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <div class="members-container">
        <div class="page-header">
            <h2><i class="fas fa-users me-2"></i>Quản lý điểm sinh viên</h2>
        </div>

        <form method="GET" action="{{ route('teacher.grade', $course->course_id) }}">
            <div class="search-controls">
                <div class="search-input-group">
                    <input type="text" name="search" class="search-input" placeholder="Tìm kiếm..."
                        value="{{ request('search') }}">
                    <button class="btn-search" type="submit">
                        <i class="fas fa-search me-1"></i> Tìm kiếm
                    </button>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <select class="filter-select" name="status" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}> Đang hoạt động</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                    <select class="filter-select" name="gender" onchange="this.form.submit()">
                        <option value="">Tất cả giới tính</option>
                        <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>Nam</option>
                        <option value="0" {{ request('gender') == '0' ? 'selected' : '' }}>Nữ</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="members-table-container mt-2">
            <div class="table-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách sinh viên</h5>
                <span class="badge bg-primary">{{ $studentgrades->count() }} sinh viên</span>
            </div>

            <div class="text-end mb-3">
                <button type="button" id="enable-edit" class="btn btn-warning">Sửa điểm</button>
            </div>

            <form method="POST" action="{{ route('teacher.updategrade', $course->course_id) }}">
                @csrf
                <div class="table-responsive">
                    <table class="table members-table">
                        <thead>
                            <tr>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Nghe</th>
                                <th>Nói</th>
                                <th>Viết</th>
                                <th>Đọc</th>
                                <th>Ngày cập nhật điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studentgrades as $studentgrade)
                                <tr>
                                    <td>{{ $studentgrade->student->fullname }}</td>
                                    <td>{{ $studentgrade->student->email }}</td>
                                    <input type="hidden"
                                        name="grades[{{ $studentgrade->student->student_id }}][student_id]"
                                        value="{{ $studentgrade->student->student_id }}">

                                    <td><input type="number" step="0.1" min="0" max="10"
                                            class="form-control input-grade"
                                            name="grades[{{ $studentgrade->student->student_id }}][listening_score]"
                                            value="{{ $studentgrade->examResult->listening_score ?? '' }}" disabled></td>
                                    <td><input type="number" step="0.1" min="0" max="10"
                                            class="form-control input-grade"
                                            name="grades[{{ $studentgrade->student->student_id }}][speaking_score]"
                                            value="{{ $studentgrade->examResult->speaking_score ?? '' }}" disabled></td>
                                    <td><input type="number" step="0.1" min="0" max="10"
                                            class="form-control input-grade"
                                            name="grades[{{ $studentgrade->student->student_id }}][writing_score]"
                                            value="{{ $studentgrade->examResult->writing_score ?? '' }}" disabled></td>
                                    <td><input type="number" step="0.1" min="0" max="10"
                                            class="form-control input-grade"
                                            name="grades[{{ $studentgrade->student->student_id }}][reading_score]"
                                            value="{{ $studentgrade->examResult->reading_score ?? '' }}" disabled></td>
                                    <td><input type="date" class="form-control input-grade"
                                            name="grades[{{ $studentgrade->student->student_id }}][exam_date]"
                                            value="{{ $studentgrade->examResult->exam_date ?? '' }}" disabled></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <button type="submit" id="save-all" class="btn btn-success mt-3" style="display:none">Lưu tất
                        cả</button>
                </div>
            </form>
        </div>

        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const enableBtn = document.getElementById('enable-edit');
            const saveBtn = document.getElementById('save-all');
            const inputs = document.querySelectorAll('.input-grade');

            enableBtn.addEventListener('click', function() {
                inputs.forEach(input => input.disabled = false);
                enableBtn.disabled = true;
                enableBtn.textContent = 'Đang chỉnh sửa';
                enableBtn.classList.replace('btn-warning', 'btn-secondary');
                saveBtn.style.display = 'inline-block';
            });

            form.addEventListener('submit', function() {
                inputs.forEach(input => input.disabled = false);
            });
        });
    </script>
@endsection
