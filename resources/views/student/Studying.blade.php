@extends('layouts.student')
@section('title', 'HỌC BÀI')

@section('styles')
    <style>
        .lesson-main {
            padding: 2rem 0;
            min-height: calc(100vh - 100px);
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .content-container {
            width: 80%;
            margin: 0 auto;
        }

        /* Navigation Bar Styles */
        .nav-bar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            padding: 1.5rem 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .nav-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color), var(--primary-light));
            background-size: 200% 200%;
            animation: gradientMove 3s ease infinite;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .nav-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .nav-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .nav-btn:hover::before {
            left: 100%;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(14, 165, 233, 0.4);
            color: white;
            text-decoration: none;
        }

        .nav-btn i {
            font-size: 1rem;
        }

        .lesson-header {
            text-align: center;
            margin-bottom: 3rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .lesson-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .lesson-header p {
            font-size: 1.1rem;
            color: var(--text-light);
            margin: 0;
        }

        .lessons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .lesson-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .lesson-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color), var(--primary-light));
            background-size: 200% 200%;
            animation: gradientMove 3s ease infinite;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .lesson-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .lesson-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .lesson-content {
            color: var(--text-dark);
            line-height: 1.6;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .lesson-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .btn-study {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 20px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .btn-study:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(14, 165, 233, 0.4);
            color: white;
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .empty-description {
            color: var(--text-light);
            font-size: 1rem;
        }

        .score {
            margin: 0;
            padding: 0.25rem 0.75rem;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .progress-status {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0.5rem 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
        }

        .status-pass {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .status-fail {
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: white;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .status-pending {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .status-badge i {
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .content-container {
                width: 90%;
            }
        }

        @media (max-width: 768px) {
            .content-container {
                width: 95%;
            }
            
            .nav-content {
                flex-direction: column;
                text-align: center;
            }
            
            .nav-title {
                font-size: 1.3rem;
            }
            
            .nav-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .lesson-header h1 {
                font-size: 2rem;
            }

            .lessons-grid {
                grid-template-columns: 1fr;
            }

            .lesson-actions {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .content-container {
                width: 100%;
                padding: 0 1rem;
            }
            
            .lesson-main {
                padding: 1rem 0;
            }
            
            .nav-bar {
                padding: 1rem 1.5rem;
            }
            
            .nav-buttons {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .nav-btn {
                width: 100%;
                justify-content: center;
            }
            
            .lesson-actions {
                flex-direction: column;
            }

            .btn-study {
                justify-content: center;
            }

            .progress-status {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .status-badge {
                align-self: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    <div class="lesson-main">
        <div class="content-container">
            <!-- Navigation Bar -->
            <div class="nav-bar">
                <div class="nav-content">
                    <div class="nav-title">
                        <i class="fas fa-graduation-cap"></i>
                        Học tập
                    </div>
                    <div class="nav-buttons">
                        <a href="{{ route('student.lesson',$courseId) }}" class="nav-btn">
                            <i class="fas fa-book-open"></i>
                            Làm bài
                        </a>
                        <a href="{{ route('student.lesson.board',$courseId) }}" class="nav-btn">
                            <i class="fas fa-bullhorn"></i>
                            Bảng tin
                        </a>
                    </div>
                </div>
            </div>

            <!-- Header Section -->
            <div class="lesson-header">
                <h1><i class="fas fa-book-open"></i> Danh Sách Bài Học</h1>
                <p>Khám phá và học tập các bài học thú vị để nâng cao kiến thức của bạn</p>
            </div>

            <!-- Lessons Grid -->
            @if (count($lessonParts) > 0)
                <div class="lessons-grid">
                    @foreach ($lessonParts as $lesson)
                        @php
                            $score = $lesson->myScore->score ?? null;
                            $total = $lesson->myScore->total_questions ?? null;
                            $correct = $lesson->myScore->correct_answers ?? 0;
                            $status = $lesson->myScore->StudentProgcess ?? null;
                            
                        @endphp

                        <div class="lesson-card">
                            <!-- Lesson Type Badge -->
                            <div class="lesson-type-badge">
                                <i class="fas fa-book"></i> {{ $lesson->part_type ?? 'Bài Học' }}
                            </div>

                            <!-- Lesson Content -->
                            <div class="lesson-content">
                                {{ $lesson->content ?? 'Đây là một bài học thú vị giúp bạn nâng cao kiến thức và kỹ năng. Hãy bắt đầu học ngay để khám phá những điều mới mẻ và phát triển bản thân!' }}
                            </div>

                            <!-- Action Buttons -->
                            <div class="lesson-actions">
                                <div style="width: 100%; margin-bottom: 1rem;">
                                    <p><strong>Kết quả lần trước:</strong> {{ $correct }} / {{ $total }} câu đúng</p>
                                    <p class="score">
                                        <i class="fas fa-star" style="margin-right: 0.25rem;"></i>{{ $score }} điểm
                                    </p>
                                    
                                    @if ($status?->completion_status !== null)
                                        <div class="progress-status">
                                            <strong>Kết quả:</strong>
                                            <span class="status-badge {{ $status->completion_status ? 'status-pass' : 'status-fail' }}">
                                                <i class="fas {{ $status->completion_status ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                {{ $status->completion_status ? 'Đạt' : 'Không đạt' }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="progress-status">
                                            <strong>Kết quả:</strong>
                                            <span class="status-badge status-pending">
                                                <i class="fas fa-clock"></i>
                                                Chưa làm bài
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('student.exercise.start', ['lessonPartId' => $lesson->lesson_part_id]) }}"
                                    class="btn-study">
                                    <i class="fas fa-play"></i> Bắt đầu học
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3 class="empty-title">Chưa có bài học nào</h3>
                    <p class="empty-description">
                        Hiện tại chưa có bài học nào được thêm vào. Vui lòng quay lại sau hoặc liên hệ với giáo viên!
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection