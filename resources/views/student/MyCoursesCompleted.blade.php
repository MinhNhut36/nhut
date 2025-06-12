@extends('layouts.student')
@section('titile', 'CÁC KHÓA ĐANG HỌC')
@section('styles')
    .card-status-completed {
    background-color: #007BFF;
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
            <div class="col-md-3 col-sm-6">
                <div class="card border rounded shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-dark small fw-bold">Ngày bắt đầu học</span>
                            <span
                                class="fw-semibold">{{ \Carbon\Carbon::parse($MyCourse->registration_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-dark small fw-bold">Trình độ</span>
                            <span class="fw-semibold">{{ $MyCourse->course->level }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-dark small fw-bold">Trạng thái</span>
                            <span
                                class="{{ $MyCourse->status->getStatus() }}">{{ $MyCourse->status->getEnrollment() }}</span>
                        </div>
                    </div>
                    <button
                        class="btn custom-btn w-100 py-2 d-flex align-items-center justify-content-center gap-2 border-0">
                        <span>{{ $MyCourse->course->course_name }} </span>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('js')

@endsection
