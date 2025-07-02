@extends('layouts.teacher')
@section('title', 'Danh sách khóa học được phân công')
@section('styles')
    <style>
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .page-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2.2rem;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .page-title i {
            color: #667eea;
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        .course-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .course-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .course-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .course-card:hover .course-header::before {
            top: -30%;
            right: -30%;
        }

        .course-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .course-body {
            padding: 25px;
        }

        .course-info {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px 0;
        }

        .info-label {
            color: #6c757d;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            font-weight: 700;
            color: #2c3e50;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
        }

        .status-completed {
            background: linear-gradient(135deg, #6f42c1, #e83e8c);
            color: white;
        }

        .course-action {
            margin-top: 20px;
        }

        .btn-enter-course {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-enter-course:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46c1);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .empty-state i {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .course-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 1.8rem;
            }
        }
    </style>
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-chalkboard-teacher"></i>
            Khóa học được phân công
        </h1>
    </div>

    <!-- Course List -->
    @if ($assignedCourses->count() > 0)
        <div class="course-grid">
            @foreach ($assignedCourses as $assignedCourse)
                <div class="course-card">
                    <div class="course-header">
                        <h3 class="course-name">{{ $assignedCourse->course->course_name }}</h3>
                    </div>
                    <div class="course-body">
                        <div class="course-info">
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Năm học
                                </span>
                                <span
                                    class="info-value">{{ \Carbon\Carbon::parse($assignedCourse->course->year)->format('d/m/Y') }}</span>
                            </div>
                            @if ($assignedCourse->course && $assignedCourse->course->students && $assignedCourse->course->students->count() > 0)
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="fas fa-users"></i>
                                        Sinh viên
                                    </span>
                                    <span class="info-value">
                                        {{ $assignedCourse->course->students->count() }}
                                    </span>
                                </div>
                            @elseif ($assignedCourse->course && $assignedCourse->course->students && $assignedCourse->course->students->count() == 0)
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="fas fa-users"></i>
                                        Sinh viên
                                    </span>
                                    <span class="info-value">
                                        0
                                    </span>
                                </div>
                            @endif
                            <div class="info-row">
                                <span class="info-label">
                                    <i class="fas fa-info-circle"></i>
                                    Trạng thái
                                </span>
                                <span
                                    class="status-badge
                                    @if ($assignedCourse->course->status == 'Đang mở lớp' || $assignedCourse->course->status == 'active') status-active
                                    @elseif($assignedCourse->course->status == 'Đã hoàn thành' || $assignedCourse->course->status == 'completed') status-completed
                                    @else status-pending @endif">
                                    {{ $assignedCourse->course->status }}
                                </span>
                            </div>
                        </div>
                        <div class="course-action">
                            <a href="{{ route('teacher.coursedetails', $assignedCourse->course->course_id) }}"
                                class="btn-enter-course">
                                <span>Vào lớp học</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>Không có khóa học nào được phân công.</p>
        </div>
    @endif
@endsection
