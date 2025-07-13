@extends('layouts.teacher')
@section('styles')
    <style>
        /* Course Navigation Styles */
        .course-navigation-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            position: relative;
        }

        .course-navigation-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 20px 30px;
            position: relative;
            overflow: hidden;
        }

        .course-navigation-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .course-info-section {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 1;
        }

        .course-info-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .course-info-details {
            flex: 1;
        }

        .course-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 5px 0;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .course-code {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 12px;
            border-radius: 12px;
            display: inline-block;
        }

        .course-navigation-tabs {
            background: white;
            padding: 0;
            position: relative;
        }

        .nav-tabs-wrapper {
            display: flex;
            gap: 0;
            position: relative;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .nav-tabs-wrapper::-webkit-scrollbar {
            display: none;
        }

        .nav-tab {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 30px;
            text-decoration: none;
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            background: transparent;
            white-space: nowrap;
            flex-shrink: 0;
            min-width: 0;
        }

        .nav-tab:hover {
            color: var(--primary-color);
            background: rgba(14, 165, 233, 0.05);
            transform: translateY(-2px);
        }

        .nav-tab.active {
            color: var(--primary-color);
            background: rgba(14, 165, 233, 0.1);
            font-weight: 600;
        }

        .nav-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 2px 2px 0 0;
        }

        .nav-tab-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .nav-tab:hover .nav-tab-icon {
            transform: scale(1.1);
        }

        .nav-tab-text {
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .content-area {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            min-height: 500px;
            padding: 30px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .course-navigation-header {
                padding: 15px 20px;
            }

            .course-info-section {
                gap: 12px;
            }

            .course-info-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .course-name {
                font-size: 1.2rem;
            }

            .course-code {
                font-size: 0.8rem;
                padding: 3px 8px;
            }

            .nav-tab {
                padding: 15px 20px;
                font-size: 0.9rem;
            }

            .nav-tab-text {
                display: none;
            }

            .nav-tab-icon {
                font-size: 1.2rem;
            }

            .content-area {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .course-navigation-header {
                padding: 12px 15px;
            }

            .course-info-section {
                gap: 10px;
            }

            .course-info-icon {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }

            .course-name {
                font-size: 1.1rem;
            }

            .nav-tab {
                padding: 12px 15px;
            }

            .content-area {
                padding: 15px;
            }
        }

        /* Animation cho active tab */
        @keyframes tabActivate {
            0% {
                transform: translateY(2px);
                opacity: 0.8;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .nav-tab.active {
            animation: tabActivate 0.3s ease;
        }

        /* Hover effects */
        .nav-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .nav-tab:hover::before {
            opacity: 0.05;
        }

        .nav-tab.active::before {
            opacity: 0.1;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid py-4">
        <!-- Course Navigation Bar -->
        <div class="course-navigation-wrapper mb-4">
            <div class="course-navigation-header">
                <div class="course-info-section">
                    <div class="course-info-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="course-info-details">
                        <h4 class="course-name">{{ $course->course_name ?? 'Khóa học' }}</h4>
                        <span class="course-code">{{ $course->course_code ?? '' }}</span>
                    </div>
                </div>
            </div>

            <nav class="course-navigation-tabs">
                <div class="nav-tabs-wrapper">
                    <a href="{{ route('teacher.boards', $course->course_id) }}"
                        class="nav-tab {{ request()->routeIs('teacher.boards') ? 'active' : '' }}">
                        <div class="nav-tab-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <span class="nav-tab-text">Bảng tin</span>
                    </a>

                    <a href="{{ route('teacher.coursemembers', $course->course_id) }}"
                        class="nav-tab {{ request()->routeIs('teacher.coursemembers') ? 'active' : '' }}">
                        <div class="nav-tab-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-tab-text">Quản lý sinh viên</span>
                    </a>

                    <a href="{{ route('teacher.grade', $course->course_id) }}"
                        class="nav-tab {{ request()->routeIs('teacher.grade') ? 'active' : '' }}">
                        <div class="nav-tab-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="nav-tab-text">Quản lý điểm</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>
@endsection
