@extends('layouts.student')
@section('titile', 'CÁC KHÓA ĐANG HỌC')
@section('styles')
    .card-status-pass {
    background-color: #007BFF;
    color: #FFFFFF;
    padding: 4px 12px;
    font-weight: 600;
    font-size: 0.9rem;
    border-radius: 9999px;
    }
    .card-status-fail {
    background-color: #F44336;
    color: #FFFFFF;
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
                        <h6 class="fw-bold text-primary mb-3 text-truncate">
                            {{ $MyCourse->course->course_name }}
                        </h6>

                        <ul class="list-unstyled small text-dark mb-3">
                            <li class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold text-dark">Ngày bắt đầu:</span>
                                <span
                                    class="fw-semibold">{{ \Carbon\Carbon::parse($MyCourse->registration_date)->format('d/m/Y') }}</span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold text-dark">Trình độ:</span>
                                <span class="fw-semibold text-dark">{{ $MyCourse->course->level }}</span>
                            </li>
                            <li class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold text-dark">Buổi học:</span>
                                <span class="fw-semibold text-dark">{{ $MyCourse->course->description }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="fw-semibold text-dark">Trạng thái:</span>
                                <span
                                    class="badge 
                            {{ $MyCourse->status->getStatus() }} 
                            rounded-pill px-3 py-1 fw-semibold">
                                    {{ $MyCourse->status->getEnrollment() }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

@section('js')

@endsection
