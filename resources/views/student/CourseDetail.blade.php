@extends('layouts.student')
@section('title', 'Chi tiết khóa học')

@section('styles')
    .course-card {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
    max-width: 800px;
    width: 100%;
    padding: 2rem 2.5rem;
    margin: 2rem auto;
    }
    .status-badge {
    font-size: 0.9rem;
    font-weight: 600;
    color: #fff;
    background-color: #198754;
    border-radius: 999px;
    padding: 0.4rem 1rem;
    display: inline-block;
    }
    .btn-register {
    background-color: #0d6efd;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    transition: background-color 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #fff;
    }
    .btn-register:hover {
    background-color: #0b5ed7;
    }
    .info-label {
    color: black;
    font-weight: 600;
    font-size: 0.9rem;
    }
    .info-value {
    font-size: 1.1rem;
    font-weight: 500;
    color: #212529;
    }
    .description {
    line-height: 1.6;
    font-size: 1rem;
    margin-top: 0.5rem;
    }
    #thongBaoLoi.fade-out,#thongBaoThanhCong.fade-out
    {
    opacity: 0;
    transition: opacity 1s ease-out;
    }
@endsection

@section('content')
    <div style="position: relative;">
        @if (session('LoiDangKy'))
            <div id="thongBaoLoi" class="alert alert-danger alert-dismissible fade show position-absolute"
                style="top: 0; left: 0; z-index: 10; min-width: 250px;">
                {{ session('LoiDangKy') }}
            </div>
        @endif

        @if (session('DangKyThanhCong'))
            <div id="thongBaoThanhCong" class="alert alert-success alert-dismissible fade show position-absolute"
                style="top: 0; left: 0; z-index: 10; min-width: 250px;">
                {{ session('DangKyThanhCong') }}
            </div>
        @endif
    </div>
    <h1 class="text-center mb-4 text-primary">{{ $CourseName->course_name }}</h1>
    @foreach ($course as $course)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <p class="mb-1"><strong>Trình độ:</strong> {{ $course->lesson->level ?? 'Không có' }}</p>
                        <p class="mb-1"><strong>Khai giảng:</strong>
                            {{ \Carbon\Carbon::parse($course->starts_date)->format('d/m/Y') }}</p>
                        <p class="mb-2">
                            <strong>Trạng thái:</strong>
                            <span class="badge bg-{{ $course->status === 'Đang mở lớp' ? 'success' : 'secondary' }}">
                                {{ $course->status }}
                            </span>
                        </p>
                        <p class="text-dark mb-2">
                            <strong>Buổi học:</strong>
                            {{ $course->description ?? 'Chưa có mô tả cho khóa học này.' }}</p>
                    </div>
                    <div class="col-md-3 text-md-end text-start mt-3 mt-md-0">
                        <a href="{{ route('student.CourseRegister', ['id' => $course->course_id]) }}"
                            class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-pen-nib me-2"></i> Đăng ký
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('js')
    document.querySelectorAll('#thongBaoLoi, #thongBaoThanhCong').forEach(tb => {
    setTimeout(() => {
    tb.classList.add('fade-out');
    setTimeout(() => tb.remove(), 1000);
    }, 3000);
    });
@endsection
