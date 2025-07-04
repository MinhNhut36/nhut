@extends('layouts.teacher')
@section('title', 'THÔNG TIN GIẢNG VIÊN')
@section('styles')
    <style>
        .profile-card {
            background: linear-gradient(135deg, rgba(51, 65, 85, 0.8) 0%, rgba(30, 41, 59, 0.9) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            /* Thêm shadow */
            transition: transform 0.3s ease;
            /* Smooth animation */
        }

        .profile-card:hover {
            transform: translateY(-5px);
        }

        .info-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
            transition: transform 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
        }

        .score-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin: 2px;
        }

        .score-excellent {
            background: #d4edda;
            color: #155724;
        }

        .score-good {
            background: #d1ecf1;
            color: #0c5460;
        }

        .score-average {
            background: #fff3cd;
            color: #856404;
        }

        .score-poor {
            background: #f8d7da;
            color: #721c24;
        }

        .progress-bar-custom {
            height: 8px;
            border-radius: 10px;
        }

        .section-title {
            color: #495057;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background: #f8f9fa;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
        }

        .status-indicator {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
        }

        .status-active {
            background-color: #28a745;
        }

        .status-inactive {
            background-color: #dc3545;
        }

        .container-fluid {
            width: 80%;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- Profile Header -->
        <div class="profile-card">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <div class="avatar-container">
                        <img src="{{ asset('uploads/avatars/' . ($teacher->avatar ?? 'AvtMacDinh.jpg')) }}"
                            class="rounded-circle border border-light" width="120" height="120" style="object-fit: cover;"
                            onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/AvtMacDinh.jpg') }}';">
                        <div
                            class="status-indicator {{ $teacher->is_status ?? true ? 'status-active' : 'status-inactive' }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <h2 class="mb-2">{{ $teacher->fullname ?? 'Nguyễn Văn A' }}</h2>
                    <p class="mb-1 fs-5">Mã sinh viên: <strong>{{ $teacher->teacher_id ?? 'SV001234' }}</strong></p>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-envelope me-2"></i>{{ $teacher->email ?? 'nguyenvana@student.edu.vn' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Personal Information -->
            <div class="col-lg-4">
                <div class="card info-card mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="section-title mb-0">
                            <i class="fas fa-user me-2 text-primary"></i>Thông tin cá nhân
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Giới tính</small>
                            <div class="fw-semibold">{{ $teacher->gender->getLabel() ?? 'Nam' }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Ngày sinh</small>
                            <div class="fw-semibold">
                                {{ isset($teacher->date_of_birth) ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d/m/Y') : '15/05/2002' }}
                            </div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Trạng thái</small>
                            <div>
                                <span class="badge {{ $teacher->is_status->badgeClass() }}">
                                    {{ $teacher->is_status->getStatus() }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Ngày tạo</small>
                            <div class="fw-semibold">
                                {{ isset($teacher->created_at) ? \Carbon\Carbon::parse($teacher->created_at)->format('d/m/Y H:i') : '01/09/2023 08:00' }}
                            </div>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted">Cập nhật lần cuối</small>
                            <div class="fw-semibold">
                                {{ isset($teacher->updated_at) ? \Carbon\Carbon::parse($teacher->updated_at)->format('d/m/Y H:i') : '15/06/2025 14:30' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Performance -->
            <div class="col-lg-8">
                <div class="card info-card mb-4">
                    <div class="card-header bg-transparent">
                        <h5 class="section-title mb-0">
                            <i class="fas fa-chart-line me-2 text-success"></i>Thông báo
                        </h5>
                        @forelse ($notifications as $notification)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <strong>{{ $notification->title }}</strong>
                                    <span
                                        class="float-end">{{ \Carbon\Carbon::parse($notification->notification_date)->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="card-body">
                                    <p>{{ $notification->message }}</p>
                                </div>
                            </div>
                        @empty
                            <p>Không có thông báo nào.</p>
                        @endforelse
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress bars
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.transition = 'width 1s ease-in-out';
                    bar.style.width = width;
                }, 500);
            });

            // Add hover effects to score badges
            const scoreBadges = document.querySelectorAll('.score-badge');
            scoreBadges.forEach(badge => {
                badge.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                    this.style.transition = 'transform 0.2s';
                });

                badge.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
@endsection
