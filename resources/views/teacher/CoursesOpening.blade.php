@extends('layouts.teacher')
@section('title', 'CÁC KHÓA ĐANG MỞ LỚP')

@section('styles')
    <style>
        .page-container {
            padding: 2rem 1rem;
            min-height: calc(100vh - 120px);
            width: 80%;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .course-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            height: 100%;
        }

        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0ea5e9, #3b82f6, #8b5cf6);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 200% 0; }
            50% { background-position: -200% 0; }
        }

        .course-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .course-card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .course-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 3.2rem;
        }

        .course-details {
            flex: 1;
            margin-bottom: 1.5rem;
        }

        .course-detail-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .course-detail-item:last-child {
            border-bottom: none;
        }

        .course-detail-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #4b5563;
            font-size: 0.9rem;
        }

        .course-detail-value {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.9rem;
        }

        .course-detail-icon {
            width: 18px;
            height: 18px;
            color: #0ea5e9;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-opening {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 2px 10px rgba(16, 185, 129, 0.3);
        }

        .status-completed {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            box-shadow: 0 2px 10px rgba(107, 114, 128, 0.3);
        }

        .status-verifying {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 2px 10px rgba(245, 158, 11, 0.3);
        }

        .course-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .enter-class-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            text-align: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .enter-class-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .enter-class-btn:hover::before {
            left: 100%;
        }

        .enter-class-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.4);
            color: white;
            text-decoration: none;
        }

        .enter-class-btn:active {
            transform: translateY(0);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .empty-state-icon {
            font-size: 4rem;
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }

        .empty-state-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .empty-state-description {
            color: #6b7280;
            font-size: 1rem;
            line-height: 1.6;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
        }

        .pagination-container .pagination {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 50px;
            padding: 0.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .pagination-container .page-link {
            border: none;
            background: transparent;
            color: #6b7280;
            font-weight: 500;
            border-radius: 50px;
            margin: 0 0.25rem;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .pagination-container .page-link:hover {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: white;
            transform: translateY(-2px);
        }

        .pagination-container .page-item.active .page-link {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: white;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4);
        }

        .stats-counter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-left: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-container {
                padding: 1.5rem 0.75rem;
            }

            .page-title {
                font-size: 2rem;
            }

            .courses-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .course-card-body {
                padding: 1.25rem;
            }

            .course-title {
                font-size: 1.1rem;
            }

            .course-detail-item {
                padding: 0.5rem 0;
            }

            .course-detail-label,
            .course-detail-value {
                font-size: 0.85rem;
            }

            .enter-class-btn {
                padding: 0.875rem;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            .page-container {
                padding: 1rem 0.5rem;
            }

            .page-title {
                font-size: 1.75rem;
            }

            .page-subtitle {
                font-size: 1rem;
            }

            .empty-state {
                padding: 3rem 1.5rem;
            }

            .empty-state-icon {
                font-size: 3rem;
            }

            .empty-state-title {
                font-size: 1.25rem;
            }
        }

        /* Loading animation */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid page-container">
        <div class="page-header">
            <h1 class="page-title">Các Khóa Đang Giảng Dạy</h1>
        </div>

        @if(isset($enrollment) && count($enrollment) > 0)
            <div class="courses-grid">
                @foreach ($enrollment as $MyCourse)
                    <div class="course-card">
                        <div class="course-card-body">
                            <h3 class="course-title">{{ $MyCourse->course->course_name }}</h3>
                            
                            <div class="course-details">
                                <div class="course-detail-item">
                                    <div class="course-detail-label">
                                        <i class="fas fa-calendar-alt course-detail-icon"></i>
                                        <span>Năm học</span>
                                    </div>
                                    <div class="course-detail-value">
                                        {{ \Carbon\Carbon::parse($MyCourse->course->year)->format('d/m/Y') }}
                                    </div>
                                </div>

                                <div class="course-detail-item">
                                    <div class="course-detail-label">
                                        <i class="fas fa-users course-detail-icon"></i>
                                        <span>Sinh viên</span>
                                    </div>
                                    <div class="course-detail-value">
                                        <span class="stats-counter">{{ $countStudents[$MyCourse->course_id] ?? 0 }}</span>
                                    </div>
                                </div>

                                <div class="course-detail-item">
                                    <div class="course-detail-label">
                                        <i class="fas fa-layer-group course-detail-icon"></i>
                                        <span>Trình độ</span>
                                    </div>
                                    <div class="course-detail-value">{{ $MyCourse->course->level }}</div>
                                </div>

                                <div class="course-detail-item">
                                    <div class="course-detail-label">
                                        <i class="fas fa-info-circle course-detail-icon"></i>
                                        <span>Trạng thái</span>
                                    </div>
                                    <div class="course-detail-value">
                                        <span class="status-badge 
                                            {{ $MyCourse->course->status->value === 'Đang mở lớp' ? 'status-opening' : 
                                               ($MyCourse->course->status->value === 'Đã hoàn thành' ? 'status-completed' : 'status-verifying') }}">
                                            @if($MyCourse->course->status->value === 'Đang mở lớp')
                                                <i class="fas fa-play-circle"></i>
                                            @elseif($MyCourse->course->status === 'Đã hoàn thành')
                                                <i class="fas fa-check-circle"></i>
                                            @else
                                                <i class="fas fa-clock"></i>
                                            @endif
                                            {{ $MyCourse->course->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="course-footer">
                                <a href="{{ route('teacher.coursedetails', $MyCourse->course->course_id) }}" 
                                   class="enter-class-btn">
                                    <i class="fas fa-door-open me-2"></i>
                                    Vào lớp học
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="{{ request()->routeIs('teacher.coursesopening') ? 'fas fa-book-open' : 'fas fa-graduation-cap' }}"></i>
                </div>
                <h3 class="empty-state-title">
                    {{ request()->routeIs('teacher.coursesopening') ? 'Chưa có khóa học đang mở lớp' : 'Chưa có khóa học đã hoàn thành' }}
                </h3>
                <p class="empty-state-description">
                    {{ request()->routeIs('teacher.coursesopening') ? 
                       'Hiện tại bạn chưa có khóa học nào đang diễn ra. Hãy liên hệ với ban quản trị để được hỗ trợ.' : 
                       'Bạn chưa hoàn thành khóa học nào. Hãy tiếp tục cố gắng với các khóa học đang diễn ra.' }}
                </p>
            </div>
        @endif

        @if (isset($enrollment) && method_exists($enrollment, 'links'))
            <div class="pagination-container">
                {{ $enrollment->links() }}
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scroll behavior
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading state for course cards
            const courseCards = document.querySelectorAll('.course-card');
            courseCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Add hover effect for better UX
            courseCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.zIndex = '10';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.zIndex = '1';
                });
            });

            // Handle button clicks with feedback
            const buttons = document.querySelectorAll('.enter-class-btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Add loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tải...';
                    this.style.pointerEvents = 'none';
                    
                    // Reset after a short delay (in case navigation is slow)
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.style.pointerEvents = 'auto';
                    }, 2000);
                });
            });

            // Intersection Observer for animations
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe elements for scroll animations
            const animatedElements = document.querySelectorAll('.course-card, .empty-state');
            animatedElements.forEach(el => {
                observer.observe(el);
            });
        });
    </script>
@endsection