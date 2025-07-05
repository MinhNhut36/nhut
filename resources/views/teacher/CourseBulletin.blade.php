@extends('layouts.teacher')

@section('sidebar')
    @include('layouts.SidebarTeacher')
@endsection

@section('content')
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #818cf8;
            --secondary-color: #f8fafc;
            --border-radius: 16px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

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
        }

        /* Animations */
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

        .post-card {
            animation: slideInUp 0.5s ease-out;
        }
    </style>

    <div class="container py-4">
        <div class="page-header">
            <h2><i class="fas fa-bullhorn me-2"></i>Bảng tin khóa học</h2>
        </div>

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
                                        <div class="comment-author">{{ $comment->author->fullname ?? 'Ẩn danh' }}</div>
                                        <div class="comment-time">{{ $comment->created_at->format('H:i - d/m/Y') }}</div>
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

    <script>
        // Auto-resize textarea
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });

        // Form submission animation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalHTML = submitBtn.innerHTML;

                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                submitBtn.disabled = true;

                // Re-enable after a short delay (remove this in actual implementation)
                setTimeout(() => {
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }, 2000);
            });
        });

        // Smooth hover effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
@endsection
