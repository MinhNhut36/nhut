@extends('layouts.student')
@section('title', 'CÁC KHÓA ĐÃ HOÀN THÀNH')
@section('styles')
    <style>
        .card-status-pass {
            background-color: #10B981;
            color: #FFFFFF;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 9999px;
        }

        .card-status-fail {
            background-color: #EF4444;
            color: #FFFFFF;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 9999px;
        }

        .filter-btn-group {
            margin-top: 24px;
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .filter-btn {
            padding: 8px 24px;
            font-weight: 600;
            border: none;
            outline: none;
            cursor: pointer;
            background: #e5e7eb;
            color: #374151;
            transition: background 0.2s, color 0.2s;
            text-decoration: none;
        }

        .filter-btn.active {
            background: #1DA9F5;
            color: #fff;
        }

        .filter-btn:hover:not(.active) {
            background: #d1d5db;
            color: #1f2937;
        }

        .filter-btn:first-child {
            border-radius: 9999px 0 0 9999px;
        }

        .filter-btn:last-child {
            border-radius: 0 9999px 9999px 0;
        }

        .completed-course-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .completed-course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10B981, #059669);
            z-index: 1;
        }

        .completed-course-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .course-title {
            min-height: 3rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
        }

        .completion-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .completion-badge.passed {
            background: linear-gradient(135deg, #10B981, #059669);
        }

        .completion-badge.failed {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }

        .course-stats {
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px;
            margin-top: 15px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #64748b;
            font-size: 0.875rem;
        }

        .stat-value {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.875rem;
        }

        .score-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 12px;
        }

        .score-item {
            background: white;
            padding: 8px 12px;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .score-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .score-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
        }

        .score-value.excellent {
            color: #10B981;
        }

        .score-value.good {
            color: #3B82F6;
        }

        .score-value.average {
            color: #F59E0B;
        }

        .score-value.poor {
            color: #EF4444;
        }

        .overall-score {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-top: 12px;
        }

        .overall-score-label {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 4px;
        }

        .overall-score-value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
        }

        .empty-state-icon {
            font-size: 5rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        .empty-state-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 0.75rem;
        }

        .empty-state-description {
            color: #6b7280;
            font-size: 1.125rem;
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 768px) {
            .filter-btn-group {
                margin-bottom: 16px;
            }

            .filter-btn {
                padding: 6px 16px;
                font-size: 0.9rem;
            }

            .completion-badge {
                position: static;
                display: inline-block;
                margin-bottom: 10px;
            }

            .score-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .filter-btn {
                flex: 1;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="container" style="max-width: 80%;">
        <div class="filter-btn-group">
            <a href="{{ route('student.myCourses') }}"
                class="filter-btn {{ request()->routeIs('student.myCourses') ? 'active' : '' }}" id="btn-studying">
                Đang học
            </a>
            <a href="{{ route('student.MyCoursesCompleted') }}"
                class="filter-btn {{ request()->routeIs('student.MyCoursesCompleted') ? 'active' : '' }}" id="btn-completed">
                Đã hoàn thành
            </a>
        </div>
        <div class="row g-4">
            @forelse ($examResults as $examResult)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card completed-course-card h-100 border-0 shadow rounded-4">
                        <div class="completion-badge {{ $examResult->overall_status == 1 ? 'passed' : 'failed' }}">
                            <i
                                class="fas fa-{{ $examResult->overall_status == 1 ? 'check-circle' : 'times-circle' }} me-1"></i>
                            {{ $examResult->overall_status == 1 ? 'Đã qua' : 'Chưa qua' }}
                        </div>

                        <div class="card-body position-relative">
                            <h6 class="course-title fw-bold text-primary mb-3">
                                {{ $examResult->course->course_name }}
                            </h6>

                            <div class="course-stats">
                                <div class="stat-item">
                                    <span class="stat-label">
                                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                        Ngày thi
                                    </span>
                                    <span class="stat-value">
                                        {{ \Carbon\Carbon::parse($examResult->exam_date)->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div class="stat-item">
                                    <span class="stat-label">
                                        <i class="fas fa-layer-group me-2 text-info"></i>
                                        Trình độ
                                    </span>
                                    <span class="stat-value">{{ $examResult->course->level }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">
                                        <i class="fas fa-calendar-alt me-2 text-primary"></i> {{-- icon lịch --}}
                                        Ngày bắt đầu
                                    </span>
                                    <span class="stat-value">
                                        {{ Carbon::parse($examResult->course->starts_date)->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div class="stat-item">
                                    <span class="stat-label">
                                        <i class="fas fa-book me-2 text-warning"></i>
                                        Khóa học
                                    </span>
                                    <span class="stat-value">{{ $examResult->course->year }}</span>
                                </div>

                                <div class="stat-item">
                                    <span class="stat-label">
                                        <i class="fas fa-medal me-2"></i>
                                        Kết quả
                                    </span>
                                    <span
                                        class="badge {{ $examResult->overall_status == 1 ? 'card-status-pass' : 'card-status-fail' }}">
                                        {{ $examResult->overall_status == 1 ? 'Đạt' : 'Không đạt' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Hiển thị điểm số chi tiết --}}
                            <div class="score-grid">
                                <div class="score-item">
                                    <div class="score-label">Listening</div>
                                    <div
                                        class="score-value {{ $examResult->listening_score >= 7 ? 'excellent' : ($examResult->listening_score >= 6 ? 'good' : ($examResult->listening_score >= 5 ? 'average' : 'poor')) }}">
                                        {{ $examResult->listening_score }}
                                    </div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label">Reading</div>
                                    <div
                                        class="score-value {{ $examResult->reading_score >= 7 ? 'excellent' : ($examResult->reading_score >= 6 ? 'good' : ($examResult->reading_score >= 5 ? 'average' : 'poor')) }}">
                                        {{ $examResult->reading_score }}
                                    </div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label">Speaking</div>
                                    <div
                                        class="score-value {{ $examResult->speaking_score >= 7 ? 'excellent' : ($examResult->speaking_score >= 6 ? 'good' : ($examResult->speaking_score >= 5 ? 'average' : 'poor')) }}">
                                        {{ $examResult->speaking_score }}
                                    </div>
                                </div>
                                <div class="score-item">
                                    <div class="score-label">Writing</div>
                                    <div
                                        class="score-value {{ $examResult->writing_score >= 7 ? 'excellent' : ($examResult->writing_score >= 6 ? 'good' : ($examResult->writing_score >= 5 ? 'average' : 'poor')) }}">
                                        {{ $examResult->writing_score }}
                                    </div>
                                </div>
                            </div>

                            {{-- Điểm tổng kết --}}
                            <div class="overall-score">
                                <div class="overall-score-label">Điểm trung bình</div>
                                <div class="overall-score-value">
                                    {{ number_format(($examResult->listening_score + $examResult->reading_score + $examResult->speaking_score + $examResult->writing_score) / 4, 1) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="empty-state-title">
                            Chưa có kết quả thi nào
                        </h3>
                        <p class="empty-state-description">
                            Bạn chưa có kết quả thi nào được ghi nhận.
                            Hãy tham gia các kỳ thi để xem kết quả ở đây.
                        </p>
                        <a href="{{ route('student.myCourses') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại khóa học đang học
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if (method_exists($examResults, 'links'))
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    {{ $examResults->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        // Show success message if available
        @if (session('success'))
            const toastHTML = `
            <div class="toast position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
                <div class="toast-header bg-success text-white">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong class="me-auto">Thành công</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        `;

            document.body.insertAdjacentHTML('beforeend', toastHTML);
            const toast = document.querySelector('.toast:last-child');
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            toast.addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        @endif
    </script>
@endsection
