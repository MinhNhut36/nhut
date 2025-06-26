@extends('layouts.student')
@section('title', 'Chi tiết khóa học')

@section('styles')
    <style>
        body {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            background-attachment: fixed;
        }

        .content-wrapper {
            width: 80%;
            max-width: 1200px;
            margin: 0 auto 2rem auto;
            padding: 0 1rem;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            text-align: center;
            width: 100%;
            margin-top: 0;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .page-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 400;
        }

        .course-card {
            background: linear-gradient(135deg, rgba(51, 65, 85, 0.9) 0%, rgba(30, 41, 59, 0.95) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            color: white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .course-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .course-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .course-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .info-item {
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .info-icon {
            color: #38bdf8;
            font-size: 1rem;
            width: 16px;
            text-align: center;
        }

        .info-label {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .info-value {
            color: white;
            font-weight: 600;
            margin-left: auto;
        }

        .description {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1rem;
            line-height: 1.5;
            border-left: 3px solid #38bdf8;
        }

        .btn-register {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            border: none;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
            width: fit-content;
            margin-left: auto;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            color: white;
        }

        .course-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .course-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .alert-notification {
            position: fixed;
            top: 100px;
            right: 2rem;
            z-index: 1050;
            min-width: 300px;
            border-radius: 10px;
            border: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.9), rgba(5, 150, 105, 0.95));
            color: white;
            border-left: 3px solid #10b981;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9), rgba(220, 38, 38, 0.95));
            color: white;
            border-left: 3px solid #ef4444;
        }

        .fade-out {
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.5s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-wrapper {
                width: 95%;
                padding: 0 0.5rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .page-header {
                padding: 1.5rem 0;
            }

            .course-card {
                padding: 1.2rem;
            }

            .course-info {
                grid-template-columns: 1fr;
            }

            .course-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .course-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-register {
                margin-left: 0;
                align-self: center;
            }

            .alert-notification {
                right: 1rem;
                left: 1rem;
                min-width: auto;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .course-card {
            animation: fadeInUp 0.4s ease-out;
        }
    </style>
@endsection

@section('content')
    <!-- Alert Notifications -->
    @if (session('LoiDangKy'))
        <div id="thongBaoLoi" class="alert alert-danger alert-dismissible fade show alert-notification">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('LoiDangKy') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('DangKyThanhCong'))
        <div id="thongBaoThanhCong" class="alert alert-success alert-dismissible fade show alert-notification">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('DangKyThanhCong') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">{{ $CourseName->course_name }}</h1>
        <p class="page-subtitle">Thông tin chi tiết khóa học</p>
    </div>

    <div class="content-wrapper">
        <!-- Course Cards -->
        @foreach ($courses as $course)
            <div class="course-card">
                <div class="course-header">
                    <div class="status-badge">
                        <i class="fas fa-{{ $course->status === 'Đang mở lớp' ? 'check-circle' : 'clock' }}"></i>
                        {{ $course->status }}
                    </div>
                </div>

                <div class="course-info">
                    <div class="info-item">
                        <i class="fas fa-layer-group info-icon"></i>
                        <span class="info-label">Trình độ:</span>
                        <span class="info-value">{{ $course->lesson->level ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-calendar-alt info-icon"></i>
                        <span class="info-label">Khai giảng:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($course->starts_date)->format('d/m/Y') }}</span>
                    </div>
                </div>

                @if ($course->description)
                    <div class="description">
                        <strong>Buổi học:</strong> {{ $course->description }}
                    </div>
                @endif

                <div class="course-footer">
                    <a href="{{ route('student.CourseRegister', ['id' => $course->course_id]) }}" 
                       class="btn-register">
                        <i class="fas fa-pen-nib"></i>
                        Đăng ký
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide notifications
            document.querySelectorAll('#thongBaoLoi, #thongBaoThanhCong').forEach(notification => {
                setTimeout(() => {
                    notification.classList.add('fade-out');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 500);
                }, 4000);
            });

            // Add smooth scrolling
            document.documentElement.style.scrollBehavior = 'smooth';
        });
    </script>
@endsection