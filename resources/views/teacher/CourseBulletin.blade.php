@extends('layouts.teacher')

@section('styles')
    <style>
        .container {
            max-width: 800px;
        }

        /* Page Header */
        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
            color: #1f2937;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Post Form */
        .post-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .post-form:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.15);
        }

        .compose-avatar img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .compose-avatar img:hover {
            transform: scale(1.1);
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px !important;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
            outline: none;
            background: #fff;
            transform: translateY(-1px);
        }

        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .btn-post {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-post:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
        }

        /* Post Cards */
        .post-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .post-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .post-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .post-content {
            padding: 1.5rem;
        }

        .post-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .post-text {
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .author-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .author-name {
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .post-time {
            color: #6b7280;
            font-size: 0.875rem;
            margin: 0;
        }

        /* Delete Button */
        .delete-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .delete-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .post-actions {
            display: flex;
            gap: 0.5rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .post-card:hover .post-actions {
            opacity: 1;
        }

        /* Comments Section */
        .comments-section {
            background: rgba(248, 250, 252, 0.8);
            border-top: 1px solid rgba(229, 231, 235, 0.5);
        }

        .comments-header {
            padding: 1rem 1.5rem 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .comment-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(229, 231, 235, 0.3);
        }

        .comment-item:last-child {
            border-bottom: none;
        }

        .comment-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .comment-author {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            margin: 0;
        }

        .comment-time {
            color: #9ca3af;
            font-size: 0.75rem;
            margin: 0;
        }

        .comment-content {
            color: #4b5563;
            margin-top: 0.5rem;
            line-height: 1.5;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comment-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        /* Reply Form */
        .reply-form {
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.5);
            border-top: 1px solid rgba(229, 231, 235, 0.5);
        }

        .reply-textarea {
            min-height: 40px;
            resize: none;
            font-size: 0.875rem;
        }

        .btn-reply {
            background: linear-gradient(135deg, #059669, #10b981);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: white;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .btn-reply:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        /* Course Navigation */
        .course-navigation-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
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
            margin: 0;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .course-navigation-tabs {
            background: white;
            padding: 0;
        }

        .nav-tabs-wrapper {
            display: flex;
            gap: 0;
            overflow-x: auto;
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

        /* Modal */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .modal-body {
            padding: 1.5rem;
            color: #4b5563;
            line-height: 1.6;
        }

        .modal-footer {
            border: none;
            padding: 1rem 1.5rem 1.5rem;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: #6b7280;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-1px);
        }

        .btn-close {
            filter: invert(1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .page-header,
            .post-form,
            .post-card {
                margin-left: 0;
                margin-right: 0;
            }

            .page-header {
                padding: 1.5rem;
            }

            .post-form {
                padding: 1rem;
            }

            .d-flex.align-items-start.gap-3 {
                flex-direction: column;
                gap: 1rem !important;
            }

            .compose-avatar {
                align-self: center;
            }

            .post-actions {
                opacity: 1;
            }

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
        }

        /* Animations */
        .post-card {
            animation: slideInUp 0.5s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

    <div class="container py-4">
        <div class="page-header">
            <h2><i class="fas fa-bullhorn me-2"></i>Bảng tin khóa học</h2>
        </div>
        @include('partials.alerts')
        {{-- Form đăng bài viết mới --}}
        <div class="post-form">
            <form action="{{ route('teacher.post', $course->course_id) }}" method="POST" class="w-100">
                @csrf
                <div class="d-flex align-items-start gap-3">
                    <div class="compose-avatar">
                        <img src="{{ asset('uploads/avatars/' . ($teacher->avatar ?? 'AvtMacDinh.jpg')) }}"
                            onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/AvtMacDinh.jpg') }}';"
                            alt="Avatar">
                    </div>
                    <div class="w-100">
                        <input type="text" name="title" class="form-control mb-3" placeholder="Tiêu đề bài viết..."
                            required>
                        <textarea name="content" class="form-control mb-3" rows="3" placeholder="Chia sẻ nội dung với học viên..."
                            required></textarea>
                    </div>
                    <button class="btn btn-post" type="submit">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Danh sách bài viết --}}
        @foreach ($posts as $post)
            <div class="post-card">
                {{-- Header --}}
                <div class="post-header">
                    <div class="author-info">
                        <img src="{{ asset('uploads/avatars/' . ($post->teacher->avatar ?? 'AvtMacDinh.jpg')) }}"
                            onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/AvtMacDinh.jpg') }}';"
                            alt="Avatar" class="author-avatar">
                        <div>
                            <div class="author-name">{{ $post->teacher->fullname }}</div>
                            <div class="post-time">{{ $post->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="post-actions">
                        <button class="delete-btn" onclick="confirmDeletePost({{ $post->post_id }})">
                            <i class="fas fa-trash-alt me-1"></i>
                            Xóa
                        </button>
                    </div>
                </div>

                {{-- Nội dung --}}
                <div class="post-content">
                    <h3 class="post-title">{{ $post->title }}</h3>
                    <p class="post-text">{!! nl2br(e($post->content)) !!}</p>
                </div>

                {{-- Danh sách phản hồi --}}
                @if ($post->comments->count() > 0)
                    <div class="comments-section">
                        <div class="comments-header">
                            <i class="fas fa-comments me-1"></i>
                            {{ $post->comments->count() }} phản hồi
                        </div>
                        @foreach ($post->comments as $comment)
                            <div class="comment-item">
                                <div class="d-flex gap-3">
                                    <img src="{{ asset('uploads/avatars/' . ($comment->author->avatar ?? 'AvtMacDinh.jpg')) }}"
                                        onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/AvtMacDinh.jpg') }}';"
                                        class="comment-avatar" alt="Avatar">
                                    <div class="flex-grow-1">
                                        <div class="comment-header">
                                            <div class="comment-info">
                                                <div class="comment-author">{{ $comment->author->fullname ?? 'Ẩn danh' }}
                                                </div>
                                                <div class="comment-time">{{ $comment->created_at->format('H:i - d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment-content">{{ $comment->content }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Form phản hồi --}}
                <div class="reply-form">
                    <form action="{{ route('teacher.comment', $post->post_id) }}" method="POST"
                        class="d-flex align-items-start gap-3">
                        @csrf
                        <img src="{{ asset('uploads/avatars/' . ($teacher->avatar ?? 'AvtMacDinh.jpg')) }}"
                            onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/AvtMacDinh.jpg') }}';"
                            class="comment-avatar" alt="Avatar">
                        <div class="flex-grow-1">
                            <textarea name="content" class="form-control reply-textarea" rows="1" placeholder="Viết phản hồi..." required></textarea>
                        </div>
                        <button class="btn btn-reply" type="submit">
                            <i class="fas fa-reply"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Delete Post Modal -->
    <div class="modal fade" id="deletePostModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Xác nhận xóa bài viết
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Bạn có chắc chắn muốn xóa bài viết này không? Hành động này không thể hoàn tác và sẽ
                        xóa luôn tất cả các phản hồi.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deletePostForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Auto-resize textarea
        document.querySelectorAll('.reply-textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });

        // Delete post confirmation
        function confirmDeletePost(postId) {
            const deleteForm = document.getElementById('deletePostForm');
            const courseId = "{{ $course->course_id }}";

            deleteForm.action = `/teacher/home/AssignedCoursesList/${courseId}/posts/delete/${postId}`;

            const deleteModal = new bootstrap.Modal(document.getElementById('deletePostModal'));
            deleteModal.show();
        }
    </script>
@endsection
