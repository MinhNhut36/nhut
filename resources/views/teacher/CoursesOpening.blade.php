@extends('layouts.teacher')
@section('title', 'CÁC KHÓA ĐANG MỞ LỚP')

@section('styles')
    <style>
        .card-status-badge {
            display: inline-block;
            padding: 4px 14px;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 9999px;
            background-color: #f3f4f6;
            color: #111827;
        }

        .badge-opening {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-completed {
            background-color: #e5e7eb;
            color: #374151;
        }

        .badge-verifying {
            background-color: #fef3c7;
            color: #92400e;
        }

        .custom-btn {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 1rem;
            padding: 10px 20px;
            border: none;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .custom-btn:hover {
            background: linear-gradient(135deg, #4338ca, #3730a3);
            transform: translateY(-1px);
        }

        .filter-btn-group {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 24px 0;
        }

        .filter-btn {
            padding: 10px 20px;
            background: #f3f4f6;
            color: #374151;
            border: none;
            font-weight: 600;
            border-radius: 9999px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .filter-btn.active,
        .filter-btn:active {
            background: #1DA9F5;
            color: #fff;
        }

        .filter-btn:hover:not(.active) {
            background: #e5e7eb;
            color: #1f2937;
        }

        .course-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .course-title {
            min-height: 3rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #9ca3af;
            margin-bottom: 1rem;
        }

        .empty-state-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #374151;
        }

        .empty-state-description {
            color: #6b7280;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .filter-btn-group {
                margin-bottom: 16px;
            }

            .filter-btn {
                padding: 6px 16px;
                font-size: 0.9rem;
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
    <div class="container" style="max-width: 80%;">
        <div class="filter-btn-group">
            <a href="{{ route('teacher.coursesopening') }}"
                class="filter-btn {{ request()->routeIs('teacher.coursesopening') ? 'active' : '' }}">Đang mở lớp</a>
            <a href="{{ route('teacher.coursescompleted') }}"
                class="filter-btn {{ request()->routeIs('teacher.coursescompleted') ? 'active' : '' }}">Đã hoàn thành</a>
        </div>

        <div class="row g-4">
            @forelse ($enrollment as $MyCourse)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card course-card h-100">
                        <div class="card-body">
                            <h5 class="course-title mb-3">
                                {{ $MyCourse->course->course_name }}
                            </h5>
                            <ul class="list-unstyled small text-dark">
                                <li class="mb-2 d-flex justify-content-between">
                                    <span class="fw-semibold">
                                        <i class="fas fa-calendar-alt me-1"></i>Năm học:
                                    </span>
                                    <span class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($MyCourse->course->year)->format('d/m/Y') }}
                                    </span>
                                </li>
                                <li class="mb-2 d-flex justify-content-between">
                                    <span class="fw-semibold">
                                        <i class="fas fa-users me-1"></i>Sinh viên:
                                    </span>
                                    <span class="fw-semibold">{{ $countStudents[$MyCourse->course_id] ?? 0 }}</span>
                                </li>
                                <li class="mb-2 d-flex justify-content-between">
                                    <span class="fw-semibold">
                                        <i class="fas fa-layer-group me-1"></i>Trình độ:
                                    </span>
                                    <span class="fw-semibold">{{ $MyCourse->course->level }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="fw-semibold">
                                        <i class="fas fa-info-circle me-1"></i>Trạng thái:
                                    </span>
                                    <span
                                        class="card-status-badge 
                                        {{ $MyCourse->course->status === 'Đang mở lớp'
                                            ? 'badge-opening'
                                            : ($MyCourse->course->status === 'Đã hoàn thành'
                                                ? 'badge-completed'
                                                : 'badge-verifying') }}">
                                        {{ $MyCourse->course->status }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('teacher.coursedetails', $MyCourse->course->course_id) }}"
                                class="text-decoration-none fw-semibold text-primary d-flex justify-content-between align-items-center">
                                <span>Vào lớp học</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i
                                class="{{ request()->routeIs('teacher.coursesopening') ? 'fas fa-book-open' : 'fas fa-graduation-cap' }}"></i>
                        </div>
                        <h3 class="empty-state-title">
                            {{ request()->routeIs('teacher.coursesopening') ? 'Chưa có khóa học đang mở lớp' : 'Chưa có khóa học đã hoàn thành' }}
                        </h3>
                    </div>
                </div>
            @endforelse
        </div>

        @if (method_exists($enrollment, 'links'))
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    {{ $enrollment->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
