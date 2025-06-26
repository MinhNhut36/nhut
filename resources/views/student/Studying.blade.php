@extends('layouts.student')
@section('title', 'HỌC BÀI')

@section('styles')
    <style>
        .lesson-main {
            padding: 2rem 0;
            min-height: calc(100vh - 100px);
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

        .progress {
            width: 100%;
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin: 0.5rem 0;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 4px;
            transition: width 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            color: white;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
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
            .lesson-actions {
                flex-direction: column;
            }

            .btn-study {
                justify-content: center;
            }
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
    </style>
@endsection

@section('content')
    <div class="lesson-main">
        <div class="content-container">
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
                            $percent = $total > 0 ? round(($correct / $total) * 100) : 0;
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
                                    <p><strong>Tiến độ:</strong> {{ $correct }} / {{ $total }} câu đúng</p>
                                    <p class="score">
                                        <i class="fas fa-star" style="margin-right: 0.25rem;"></i>{{ $score }} điểm
                                    </p>
                                    @if ($total > 0)
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $percent }}%;" aria-valuenow="{{ $correct }}"
                                                aria-valuemin="0" aria-valuemax="{{ $total }}">
                                                {{ $percent }}%
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('student.quiz.start', ['lessonPartId' => $lesson->lesson_part_id]) }}"
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
