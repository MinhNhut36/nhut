@extends('layouts.student')
@section('title', 'CÁC KHÓA ĐÃ HOÀN THÀNH')
@section('styles')
    <style>
        .card-status-pass {
            background-color: #10B981;
            color: #FFFFFF;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 9999px;
        }

        .card-status-fail {
            background-color: #EF4444;
            color: #FFFFFF;
            padding: 4px 12px;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 9999px;
        }

        .card-status-completed {
            background-color: #3B82F6;
            color: #FFFFFF;
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

        .filter-btn-group {
            margin-top: 24px;
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .filter-btn {
            padding: 8px 24px;
            font-weight: 600;
            border: none;
            outline: none;
            cursor: pointer;
            background: #e5e7eb;
            color: #374151;
            transition: background 0.2s, color 0.2s;
            text-decoration: none;
        }

        .filter-btn.active,
        .filter-btn:active {
            background: #1DA9F5;
            color: #fff;
        }

        .filter-btn:hover:not(.active) {
            background: #d1d5db;
            color: #1f2937;
        }

        .filter-btn:first-child {
            border-radius: 9999px 0 0 9999px;
        }

        .filter-btn:last-child {
            border-radius: 0 9999px 9999px 0;
        }

        .completed-course-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .completed-course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10B981, #059669);
            z-index: 1;
        }

        .completed-course-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .course-title {
            min-height: 3rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
        }

        .completion-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .course-stats {
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px;
            margin-top: 15px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #64748b;
            font-size: 0.875rem;
        }

        .stat-value {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.875rem;
        }

        .review-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .review-btn:hover {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            transform: translateY(-1px);
            color: white;
        }

        .certificate-btn {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: none;
            color: white;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 8px;
        }

        .certificate-btn:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            transform: translateY(-1px);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
        }

        .empty-state-icon {
            font-size: 5rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        .empty-state-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 0.75rem;
        }

        .empty-state-description {
            color: #6b7280;
            font-size: 1.125rem;
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .progress-ring {
            width: 60px;
            height: 60px;
            position: absolute;
            top: -10px;
            right: -10px;
        }

        .progress-ring circle {
            fill: none;
            stroke: #e2e8f0;
            stroke-width: 4;
        }

        .progress-ring .progress {
            stroke: #10B981;
            stroke-linecap: round;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }

        @media (max-width: 768px) {
            .filter-btn-group {
                margin-bottom: 16px;
            }
            
            .filter-btn {
                padding: 6px 16px;
                font-size: 0.9rem;
            }

            .completion-badge {
                position: static;
                display: inline-block;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 576px) {
            .filter-btn {
                flex: 1;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container" style="max-width: 80%;">
        <div class="filter-btn-group">
            <a href="{{ route('student.myCourses') }}"
                class="filter-btn {{ request()->routeIs('student.myCourses') ? 'active' : '' }}"
                id="btn-studying">
                </i>Đang học
            </a>
            <a href="{{ route('student.MyCoursesCompleted') }}"
                class="filter-btn {{ request()->routeIs('student.MyCoursesCompleted') ? 'active' : '' }}"
                id="btn-completed">
                </i>Đã hoàn thành
            </a>
        </div>
        <div class="row g-4">
            @forelse ($enrollment as $MyCourse)
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card completed-course-card h-100 border-0 shadow rounded-4">
                        <div class="completion-badge">
                            <i class="fas fa-check-circle me-1"></i>Hoàn thành
                        </div>
                        
                        <div class="card-body position-relative">
                            <h6 class="course-title fw-bold text-primary mb-3">
                                {{ $MyCourse->course->course_name }}
                            </h6>

                            <div class="course-stats">
                                <div class="stat-item">
                                    <span class="stat-label">
                                        <i class="fas fa-calendar-start me-2 text-primary"></i>
                                        Ngày bắt đầu
                                    </span>
                                    <span class="stat-value">
                                        {{ \Carbon\Carbon::parse($MyCourse->registration_date)->format('d/m/Y') }}
                                    </span>
                                </div>
                                
                                <div class="stat-item">
                                    <span class="stat-label">
                                        <i class="fas fa-calendar-check me-2 text-success"></i>
                                        Ngày hoàn thành
                                    </span>
                                    <span class="stat-value">
                                        {{ \Carbon\Carbon::parse($MyCourse->updated_at)->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div class="stat-item">
                                    <span class="stat-label">
                                        <i class="fas fa-layer-group me-2 text-info"></i>
                                        Trình độ
                                    </span>
                                    <span class="stat-value">{{ $MyCourse->course->level }}</span>
                                </div>

                                <div class="stat-item">
                                    <span class="stat-label">
                                        <i class="fas fa-clock me-2 text-warning"></i>
                                        Buổi học
                                    </span>
                                    <span class="stat-value">{{ $MyCourse->course->description }}</span>
                                </div>

                                <div class="stat-item">
                                    <span class="stat-label">
                                        <i class="fas fa-medal me-2"></i>
                                        Kết quả
                                    </span>
                                    <span class="badge {{ $MyCourse->status->getStatus() }} px-3 py-1">
                                        {{ $MyCourse->status->getEnrollment() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="empty-state-title">
                            Chưa có khóa học nào đã hoàn thành
                        </h3>
                        <p class="empty-state-description">
                            Hãy hoàn thành các khóa học đang học để xem chúng xuất hiện ở đây. 
                            Bạn sẽ có thể xem lại nội dung và tải chứng chỉ khi hoàn thành khóa học.
                        </p>
                        <a href="{{ route('student.myCourses') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại khóa học đang học
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination if needed -->
        @if(method_exists($enrollment, 'links'))
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    {{ $enrollment->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to completed course cards
        const courseCards = document.querySelectorAll('.completed-course-card');
        courseCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-6px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Add click effects to buttons
        const buttons = document.querySelectorAll('.review-btn, .certificate-btn');
        buttons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Add ripple effect
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
                    background: rgba(255,255,255,0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Certificate download handling
        const certificateBtns = document.querySelectorAll('.certificate-btn');
        certificateBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tải...';
                this.disabled = true;
                
                // Simulate download process
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-check me-2"></i>Đã tải!';
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                }, 1500);
            });
        });

        // Review lesson handling
        const reviewBtns = document.querySelectorAll('.review-btn');
        reviewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Add clicked effect
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
                
                // Here you would typically redirect to the lesson review page
                console.log('Reviewing lesson...');
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    });

    // Show success message if available
    @if(session('success'))
        const toastHTML = `
            <div class="toast position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
                <div class="toast-header bg-success text-white">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong class="me-auto">Thành công</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', toastHTML);
        const toast = document.querySelector('.toast:last-child');
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    @endif
</script>
@endsection