<div class="sidebar" id="sidebar">
    <div class="sidebar-header px-3 py-3">
        <h5 class="m-0">Quản lý khóa học</h5>
    </div>
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link sidebar-link" href="{{ route('teacher.boards', $course->course_id) }}">
                    <i class="fas fa-bullhorn me-2"></i> <span>Bảng tin</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-link" href="{{ route('teacher.coursemembers', $course->course_id) }}">
                    <i class="fas fa-users me-2"></i> <span>Quản lý sinh viên</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-link" href="{{ route('teacher.assignments', $course->course_id) }}">
                    <i class="fas fa-tasks me-2"></i> <span>Bài tập</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-link" href="{{ route('teacher.grade', $course->course_id) }}">
                    <i class="fas fa-chart-line me-2"></i> <span>Quản lý điểm</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<button class="sidebar-toggle" id="sidebarToggle" type="button">
    <i class="fas fa-chevron-left" id="toggleIcon"></i>
</button>
<style>
    :root {
        --sidebar-width: 260px;
        --header-height: 89px;
    }

    .sidebar {
        position: fixed;
        top: 89px;
        /* phù hợp chiều cao header */
        left: 0;
        width: 260px;
        /* hoặc 280px */
        height: calc(100vh - 89px);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow-lg);
        z-index: 999;
        transition: var(--transition);
        overflow-y: auto;
    }

    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar.hidden {
        display: none;
    }

    .sidebar-nav .nav-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        color: #333;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .sidebar-nav .nav-link:hover,
    .sidebar-nav .nav-link.active {
        background-color: #f3f4f6;
        color: #0ea5e9;
    }

    .main-content {
        flex: 1;
        position: relative;
        margin-left: 260px;
        /* bằng với sidebar */
        margin-top: 89px;
        /* bằng chiều cao header */
        padding: 1rem;
        transition: var(--transition);
    }

    .sidebar.collapsed~.main-content {
        margin-left: 70px;
    }

    .sidebar.hidden~.main-content {
        margin-left: 0;
    }

    .sidebar-toggle {
        position: fixed;
        top: 100px;
        left: calc(var(--sidebar-width) + 20px);
        z-index: 1100;
        background: #0ea5e9;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .sidebar.collapsed~.sidebar-toggle {
        left: calc(var(--sidebar-collapsed) + 20px);
    }

    .sidebar.hidden~.sidebar-toggle {
        left: 20px;
    }

    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        z-index: 900;
        display: none;
    }

    .sidebar-overlay.active {
        display: block;
    }

    @media (max-width: 768px) {
        .sidebar {
            top: 0;
            height: 100vh;
            z-index: 1000;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
        }

        .sidebar-toggle {
            top: 80px;
            left: 10px;
        }
    }
</style>

<!-- ✅ SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        const sidebarLinks = document.querySelectorAll('.sidebar-link');

        const isMobile = () => window.innerWidth <= 768;

        function toggleSidebar() {
            if (isMobile()) {
                sidebar.classList.toggle('hidden');
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
                toggleIcon.className = sidebar.classList.contains('hidden') ? 'fas fa-bars' : 'fas fa-times';
            } else {
                sidebar.classList.toggle('collapsed');
                toggleIcon.className = sidebar.classList.contains('collapsed') ? 'fas fa-chevron-right' :
                    'fas fa-chevron-left';
                localStorage.setItem('sidebarState', sidebar.classList.contains('collapsed') ? 'collapsed' :
                    'open');
            }
        }

        toggleBtn.addEventListener('click', toggleSidebar);

        overlay.addEventListener('click', () => {
            sidebar.classList.add('hidden');
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            toggleIcon.className = 'fas fa-bars';
        });

        // Init state
        const savedState = localStorage.getItem('sidebarState') || 'open';
        if (isMobile()) {
            sidebar.classList.add('hidden');
            toggleIcon.className = 'fas fa-bars';
        } else {
            if (savedState === 'collapsed') {
                sidebar.classList.add('collapsed');
                toggleIcon.className = 'fas fa-chevron-right';
            } else {
                toggleIcon.className = 'fas fa-chevron-left';
            }
        }

        // Highlight active link
        const currentPath = window.location.pathname;
        sidebarLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });

        // Hotkey: Ctrl+B to toggle
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                toggleSidebar();
            }

            if (e.key === 'Escape' && isMobile()) {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                toggleIcon.className = 'fas fa-bars';
            }
        });

        // Resize behavior
        window.addEventListener('resize', () => {
            if (isMobile()) {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('collapsed');
                overlay.classList.remove('active');
                toggleIcon.className = 'fas fa-bars';
            } else {
                const saved = localStorage.getItem('sidebarState') || 'open';
                sidebar.classList.remove('hidden');
                sidebar.classList.toggle('collapsed', saved === 'collapsed');
                toggleIcon.className = saved === 'collapsed' ? 'fas fa-chevron-right' :
                    'fas fa-chevron-left';
            }
        });
    });
</script>
