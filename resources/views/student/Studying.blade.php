@extends('layouts.student')
@section('titile', 'HỌC BÀI')
@section('styles')
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #e0e7ff;
            --text-color: #374151;
            --border-color: #d1d5db;
            --hover-color: #f3f4f6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
        }

        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .lesson-header {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .lesson-header:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .lesson-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .collapse-icon {
            transition: transform 0.3s ease;
            font-size: 0.9rem;
        }

        .collapse-icon.collapsed {
            transform: rotate(-90deg);
        }

        .lesson-content {
            padding: 0;
        }

        .lesson-item {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            color: var(--text-color);
            padding: 15px 25px;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .lesson-item:hover {
            background: rgba(255, 255, 255, 1);
            color: var(--primary-color);
            transform: translateX(5px);
            padding-left: 30px;
        }

        .lesson-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .lesson-item:hover:before {
            transform: scaleY(1);
        }

        .lesson-item.active {
            background: rgba(255, 255, 255, 1);
            color: var(--primary-color);
            font-weight: 600;
        }

        .lesson-item.active:before {
            transform: scaleY(1);
        }

        .main-content {
            padding: 30px;
            background: white;
            margin: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .content-header {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .content-title {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .content-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
        }

        .lesson-number {
            background: var(--primary-color);
            color: white;
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 12px;
            margin-right: 10px;
            font-weight: 600;
        }



        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 80%;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin: 10px;
                padding: 20px;
            }

            .mobile-toggle {
                display: block !important;
            }
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 12px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
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
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="overlay" onclick="toggleSidebar()"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar" id="sidebar">
                @foreach ($lesson as $lessonPart)
                    <div class="lesson-header" data-bs-toggle="collapse" data-bs-target="#lesson1Content"
                        aria-expanded="true">
                        <h5 class="lesson-title">
                            {{ $lessonPart->content }}
                            <i class="fas fa-chevron-down collapse-icon"></i>
                        </h5>
                    </div>
                    <div class="collapse show lesson-content" id="lesson1Content">
                        @foreach ($lessonPart->contents as $content)
                            <a href="#" class="lesson-item">
                                <span class="lesson-number">{{ $content->content_data }}</span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content fade-in" id="mainContent">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        function loadContent(contentKey) {
            const content = contentData[contentKey];
            if (!content) return;

            // Update active state
            document.querySelectorAll('.lesson-item').forEach(item => {
                item.classList.remove('active');
            });
            event.target.classList.add('active');

            // Update content
            document.querySelector('.content-title').textContent = content.title;
            document.querySelector('.content-subtitle').textContent = content.subtitle;
            document.getElementById('contentArea').innerHTML = content.content;

            // Add fade animation
            const mainContent = document.getElementById('mainContent');
            mainContent.classList.remove('fade-in');
            setTimeout(() => {
                mainContent.classList.add('fade-in');
            }, 50);

            // Close sidebar on mobile
            if (window.innerWidth <= 768) {
                toggleSidebar();
            }
        }

        function toggleSidebar() {
            const
                sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.overlay');
            if (sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                overlay.style.display = 'none';
            } else {
                sidebar.classList.add('show');
                overlay.style.display = 'block';
            }
        } // Handle collapse icon rotation
        document.addEventListener('DOMContentLoaded', function() {
            const
                collapseElements = document.querySelectorAll('[data-bs-toggle=" collapse" ]');
            collapseElements.forEach(element => {
                element.addEventListener('click', function() {
                    const icon = this.querySelector('.collapse-icon');
                    icon.classList.toggle('collapsed');
                });
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('sidebar').classList.remove('show');
                document.querySelector('.overlay').style.display = 'none';
            }
        });
    </script>
@endsection
