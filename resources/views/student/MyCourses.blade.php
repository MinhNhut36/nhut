@extends('layouts.student')
@section('title', 'CÁC KHÓA ĐANG HỌC')
@section('styles')
    <style>
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

        .filter-btn.active,
        .filter-btn:active {
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

        .course-card {
            transition: all 0.3s ease;
        }

        .course-card:hover {
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

        .course-btn {
            background: linear-gradient(135deg, #1DA9F5 0%, #4f46e5 100%);
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .course-btn:hover {
            background: linear-gradient(135deg, #0284c7 0%, #4338ca 100%);
            transform: translateY(-1px);
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
            margin-bottom: 0.5rem;
        }

        .empty-state-description {
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 1.5rem;
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
            <a href="{{ route('student.myCourses') }}"
                class="filter-btn {{ request()->routeIs('student.myCourses') ? 'active' : '' }}" id="btn-studying">Đang
                học</a>
            <a href="{{ route('student.MyCoursesCompleted') }}"
                class="filter-btn {{ request()->routeIs('student.MyCoursesCompleted') ? 'active' : '' }}"
                id="btn-completed">Đã hoàn thành</a>
        </div>

        <div class="row g-4">
            @forelse ($enrollment as $MyCourse)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card course-card h-100 border-0 shadow rounded-4">
                        <div class="card-body">
                            <h5 class="card-title course-title text-primary fw-bold mb-3">
                                {{ $MyCourse->course->course_name }}
                            </h5>
                            <ul class="list-unstyled small text-dark">
                                <li class="mb-2 d-flex justify-content-between">
                                    <span class="fw-semibold">
                                        <i class="fas fa-calendar-alt me-1"></i>Ngày bắt đầu:
                                    </span>
                                    <span class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($MyCourse->registration_date)->format('d/m/Y') }}
                                    </span>
                                </li>
                                <li class="mb-2 d-flex justify-content-between">
                                    <span class="fw-semibold">
                                        <i class="fas fa-clock me-1"></i>Buổi học:
                                    </span>
                                    <span class="fw-semibold text-dark">{{ $MyCourse->course->description }}</span>
                                </li>
                                <li class="mb-2 d-flex justify-content-between">
                                    <span class="fw-semibold">
                                        <i class="fas fa-layer-group me-1"></i>Trình độ:
                                    </span>
                                    <span class="fw-semibold text-dark">{{ $MyCourse->course->level }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="fw-semibold">
                                        <i class="fas fa-info-circle me-1"></i>Trạng thái:
                                    </span>
                                    <span class=" {{$MyCourse->status->getStatus() }}">
                                        {{ $MyCourse->status->getEnrollment() }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('student.lesson', $MyCourse->course->course_id) }}"
                                class="btn course-btn w-100 text-white d-flex justify-content-between align-items-center">
                                <span>
                                    @if (request()->routeIs('student.myCourses'))
                                        <i class="fas fa-play me-2"></i>Xem bài học
                                    @else
                                        <i class="fas fa-eye me-2"></i>Xem lại bài học
                                    @endif
                                </span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            @if (request()->routeIs('student.myCourses'))
                                <i class="fas fa-book-open"></i>
                            @else
                                <i class="fas fa-graduation-cap"></i>
                            @endif
                        </div>
                        <h3 class="empty-state-title">
                            @if (request()->routeIs('student.myCourses'))
                                Chưa có khóa học đang học
                            @else
                                Chưa có khóa học đã hoàn thành
                            @endif
                        </h3>
                        <p class="empty-state-description">
                            @if (request()->routeIs('student.myCourses'))
                                Hãy đăng ký một khóa học mới để bắt đầu hành trình học tập của bạn.
                            @else
                                Hoàn thành các khóa học đang học để xem chúng xuất hiện ở đây.
                            @endif
                        </p>
                        @if (request()->routeIs('student.myCourses'))
                            <a href="{{ route('student.courses') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Khám phá khóa học
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination if needed -->
        @if (method_exists($enrollment, 'links'))
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    {{ $enrollment->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        // Show success message if available
        @if (session('success'))
            // Create and show toast notification
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