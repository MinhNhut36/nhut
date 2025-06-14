@extends('layouts.student')
@section('title', 'Danh sách khóa học')
@section('styles')
    .card-status {
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
@endsection
@section('content')

    <h2 class="mb-4 fw-bold text-center">Danh sách khóa học</h2>
    <div class="row g-4">
        @foreach ($courses as $course)
            <div class="col-md-3 col-sm-6">
                <div class="card border rounded shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-dark fw-bold">Năm học</span>
                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($course->year)->format('Y') }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-dark fw-bold">Trạng thái</span>
                            <span class="card-status">{{ $course->status }}</span>
                        </div>
                    </div>
                    <a href="{{ route('student.DetailCourse', $course->course_name) }}"
                        class="btn custom-btn w-100 py-2 d-flex align-items-center justify-content-center gap-2 border-0">
                        <span>{{ $course->course_name }} </span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
