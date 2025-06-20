@if (session('success') || session('error') || session('warning'))
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tự động ẩn tất cả alert có class 'auto-dismiss'
            document.querySelectorAll('.auto-dismiss').forEach(function(alert) {
                setTimeout(function() {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }, 3000);
            });
        });
    </script>
@endif
