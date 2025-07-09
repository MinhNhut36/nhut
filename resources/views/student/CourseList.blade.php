@extends('layouts.student')
@section('title', 'DANH SÁCH TRÌNH ĐỘ')
@section('styles')
    <style>
        :root {
            --primary-color: #667eea;
            --primary-dark: #764ba2;
            --success-color: #10b981;
            --success-light: #d1fae5;
            --success-dark: #065f46;
            --warning-color: #f59e0b;
            --warning-light: #fef3c7;
            --warning-dark: #92400e;
            --danger-color: #ef4444;
            --danger-light: #fee2e2;
            --danger-dark: #991b1b;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .page-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin: 0;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            padding: 0 1rem;
        }

        .course-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-color);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .course-card:hover::before {
            opacity: 1;
        }

        .card-body {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .course-info {
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-value {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .level-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .order-badge {
            background-color: var(--success-light);
            color: var(--success-dark);
            padding: 0.25rem 0.75rem;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .course-button {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: auto;
        }

        .course-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .course-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .course-button:hover::before {
            left: 100%;
        }

        .course-button i {
            transition: transform 0.3s ease;
        }

        .course-button:hover i {
            transform: translateX(4px);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .course-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 0 0.5rem;
            }

            .course-card {
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .page-header {
                padding: 2rem 0;
            }

            .page-title {
                font-size: 1.75rem;
            }
        }

        /* Animation cho cards khi load */
        .course-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .course-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .course-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .course-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .course-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .course-card:nth-child(n+5) {
            animation-delay: 0.5s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title">
                <i class="fas fa-graduation-cap me-3"></i>
                Danh sách trình độ
            </h1>
        </div>
    </div>

    <div class="container">
        <!-- Course Grid -->
        @if ($lessons && count($lessons) > 0)
            <div class="course-grid">
                @foreach ($lessons as $lesson)
                    <div class="course-card">
                        <div class="card-body">
                            <div class="course-info">
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="fas fa-layer-group"></i>
                                        Trình độ
                                    </span>
                                    <span class="level-badge">
                                        {{ $lesson->level }}
                                    </span>
                                </div>
                                @if ($lesson->description)
                                    <div class="info-row">
                                        <span class="info-label">
                                            <i class="fas fa-info-circle"></i>
                                            Mô tả
                                        </span>
                                        <span class="info-value">
                                            {!! $lesson->description !!}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('student.DetailCourse', $lesson->level) }}" class="course-button">
                                <i class="fas fa-book-open"></i>
                                <span>{{ $lesson->title }}</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <h3>Chưa có trình độ nào</h3>
                <p>Hiện tại chưa có trình độ học tập nào được thiết lập. Vui lòng liên hệ với giáo vụ để biết thêm thông
                    tin.</p>
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll cho các liên kết
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
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

            // Thêm hiệu ứng loading cho buttons
            document.querySelectorAll('.course-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    // Tạo hiệu ứng ripple
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;

                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // CSS animation cho ripple effect
            const style = document.createElement('style');
            style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .course-button {
            position: relative;
            overflow: hidden;
        }
    `;
            document.head.appendChild(style);
        });
    </script>
@endsection
