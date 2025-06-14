@extends('layouts.student')
@section('titile', 'CÁC KHÓA ĐANG HỌC')
@section('styles')
    .card-status-studying {
    background-color: #d1fae5;
    color: #065f46;
    padding: 4px 12px;
    font-weight: 600;
    font-size: 0.9rem;
    border-radius: 9999px;
    }
    .custom-btn {
    background-color: #4f46e5;
    color: white;
    font-weight: 800;
    text-transform: uppercase;
    font-size: 1.1rem;
    transition: background-color 0.3s;
    }
    .custom-btn:hover {
    background-color: #4338ca;
    }
    .filter-btn-group {
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
    }
    .filter-btn.active, .filter-btn:active {
    background: #1DA9F5;
    color: #fff;
    }
    .filter-btn:first-child {
    border-radius: 9999px 0 0 9999px;
    }
    .filter-btn:last-child {
    border-radius: 0 9999px 9999px 0;
    }
@endsection

@section('content')
    <div class="filter-btn-group">
        <a href="{{ route('student.myCourses') }}"
            class="filter-btn text-decoration-none {{ request()->routeIs('student.myCourses') ? 'active' : '' }}"
            id="btn-studying">Đang học</a>
        <a href="{{ route('student.MyCoursesCompleted') }}"
            class="filter-btn text-decoration-none {{ request()->routeIs('student.MyCoursesCompleted') ? 'active' : '' }}"
            id="btn-completed">Đã hoàn thành</a>
    </div>
    <div class="row g-4">
        @foreach ($enrollment as $MyCourse)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow rounded-4">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-bold text-truncate mb-3">
                            {{ $MyCourse->course->course_name }}
                        </h5>
                        <ul class="list-unstyled small text-dark">
                            <li class="mb-2 d-flex justify-content-between">
                                <span class="fw-semibold">Ngày bắt đầu:</span>
                                <span
                                    class="fw-semibold">{{ \Carbon\Carbon::parse($MyCourse->registration_date)->format('d/m/Y') }}</span>
                            </li>
                            <li class="mb-2 d-flex justify-content-between">
                                <span class="fw-semibold">Buổi học:</span>
                                <span class="fw-semibold text-dark">{{ $MyCourse->course->description }}</span>
                            </li>
                            <li class="mb-2 d-flex justify-content-between">
                                <span class="fw-semibold">Trình độ:</span>
                                <span class="fw-semibold text-dark">{{ $MyCourse->course->level }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="fw-semibold">Trạng thái:</span>
                                <span class="{{ $MyCourse->status->getStatus() }}">
                                    {{ $MyCourse->status->getEnrollment() }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="{{ route('student.lesson', $MyCourse->course->level) }}"
                            class="btn btn-primary w-100 d-flex justify-content-between align-items-center">
                            <span>Xem bài học</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

@section('js')

@endsection
