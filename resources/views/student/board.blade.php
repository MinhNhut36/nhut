@extends('layouts.student')
@section('title', 'BẢNG TIN')

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

        /* Delete Comment Button */
        .comment-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-delete-comment {
            background: none;
            border: none;
            color: #dc2626;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0.7;
        }

        .btn-delete-comment:hover {
            opacity: 1;
            background: rgba(220, 38, 38, 0.1);
            transform: scale(1.05);
        }

        .btn-delete-comment i {
            font-size: 0.875rem;
        }

        /* Reply Form */
        .reply-form {
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.5);
            border-top: 1px solid rgba(229, 231, 235, 0.5);
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

        /* Empty State */
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

            .container {
                padding: 1rem;
            }

            .page-header,
            .post-card {
                margin-left: 0;
                margin-right: 0;
            }

            .page-header {
                padding: 1.5rem;
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
    <div class="lesson-main">
        <div class="content-container">
            <!-- Navigation Bar -->
            <div class="nav-bar">
                <div class="nav-content">
                    <div class="nav-title">
                        <i class="fas fa-graduation-cap"></i>
                        {{ request()->routeIs('student.lesson') ? 'Học tập' : 'Bảng tin' }}
                    </div>
                    <div class="nav-buttons">
                        <a href="{{ route('student.lesson', $courseId) }}" class="nav-btn">
                            <i class="fas fa-book-open"></i>
                            Làm bài
                        </a>
                        <a href="{{ route('student.lesson.board', $courseId) }}" class="nav-btn">
                            <i class="fas fa-bullhorn"></i>
                            Bảng tin
                        </a>
                    </div>
                </div>
            </div>
            <div class="container py-4">

                @include('partials.alerts')

                {{-- Danh sách bài viết --}}
                @if ($posts && count($posts) > 0)
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
                            @if ($post->comments && $post->comments->count() > 0)
                                <div class="comments-section">
                                    <div class="comments-header">
                                        <i class="fas fa-comments me-1"></i>
                                        {{ $post->comments->count() }} phản hồi
                                    </div>
                                    @foreach ($post->comments as $comment)
                                        <div class="comment-item">
                                            <div class="d-flex gap-3">
                                                <img src="{{ asset('uploads/avatars/' . ($comment->student->avatar ?? 'AvtMacDinh.jpg')) }}"
                                                    onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/AvtMacDinh.jpg') }}';"
                                                    class="comment-avatar" alt="Avatar">
                                                <div class="flex-grow-1">
                                                    <div class="comment-header">
                                                        <div class="comment-info">
                                                            <div class="comment-author">
                                                                {{ $comment->student->fullname ?? $comment->teacher->fullname }}
                                                            </div>
                                                            <div class="comment-time">
                                                                {{ $comment->created_at->format('H:i - d/m/Y') }}
                                                            </div>
                                                        </div>
                                                        {{-- Nút xóa comment - chỉ hiển thị nếu là comment của sinh viên đó --}}
                                                        @if ($comment->student_id === $student_id)
                                                            <div class="comment-actions">
                                                                <form action="{{ route('student.lesson.board.comment.delete', $comment->comment_id) }}" 
                                                                      method="POST" 
                                                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')"
                                                                      style="display: inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn-delete-comment" title="Xóa comment">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
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
                                <form action="{{ route('student.lesson.board.comment', ['postId' => $post->post_id]) }}" method="POST"
                                    class="d-flex align-items-start gap-3">
                                    @csrf
                                    <img src="{{ asset('uploads/avatars/' . ($student->avatar ?? 'AvtMacDinh.jpg')) }}"
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
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 class="empty-title">Chưa có bài viết nào</h3>
                        <p class="empty-description">
                            Hiện tại chưa có bài viết nào trong bảng tin. Vui lòng quay lại sau để xem các thông báo và bài
                            viết mới từ giáo viên!
                        </p>
                    </div>
                @endif
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
    </script>
@endsection