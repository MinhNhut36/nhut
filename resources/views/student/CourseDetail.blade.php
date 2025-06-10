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
    <div class="course-card">
        <h1 class="text-center mb-4 text-primary">{{ $course->course_name }}</h1>

        <div class="row mb-4">
            <div class="col-12 col-md-6 mb-3">
                <div class="info-label fw-bold">Trình độ</div>
                <div class="info-value">{{ $course->lesson->level }}</div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="info-label">Thời gian khai giảng</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($course->starts_date)->format('d/m/Y') }}</div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="info-label">Năm học</div>
                <div class="info-value">{{ $course->year }}</div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="info-label">Trạng thái</div>
                <div class="status-badge">{{ $course->status }}</div>
            </div>
        </div>

        <div class="mb-5">
            <h4 class="fw-bold mb-2">Mô tả chương trình học</h4>
            <p class="description">{{ $course->lesson->description }}</p>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('student.CourseRegister', ['id' => $course->course_id]) }}"
                class="btn btn-primary btn-lg shadow rounded-pill px-4 py-2">
                <i class="fas fa-pen-nib me-2"></i> Đăng ký khóa học
            </a>
        </div>

    </div>
@endsection
@section('js')
    document.querySelectorAll('#thongBaoLoi, #thongBaoThanhCong').forEach(tb => {
    setTimeout(() => {
    tb.classList.add('fade-out');
    setTimeout(() => tb.remove(), 1000);
    }, 3000);
    });
@endsection
