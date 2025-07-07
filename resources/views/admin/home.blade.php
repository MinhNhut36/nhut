@extends('layouts.admin')

@section('styles')
    <style>
        .notification-card {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .notification-card:hover {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .notification-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 0.35rem 0.35rem 0 0;
        }

        .notification-body {
            padding: 1.5rem;
        }

        .notification-footer {
            background-color: #f8f9fc;
            padding: 1rem;
            border-radius: 0 0 0.35rem 0.35rem;
            border-top: 1px solid #e3e6f0;
        }

        .add-notification-form {
            background: white;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #ee7de9 0%, #f3435a 100%);
        }

        .notification-date {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .notification-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .notification-message {
            color: #495057;
            line-height: 1.6;
        }

        .dashboard-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
        }

        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }

        .no-notifications {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 2rem;
            background: #f8f9fc;
            border-radius: 0.35rem;
            border: 1px solid #e3e6f0;
        }
    </style>
@endsection

@section('content')
    @include('partials.alerts')
    <div class="container-fluid">
        <h1 class="dashboard-title">Quản lý thông báo</h1>

        <!-- Form thêm thông báo -->
        <div class="add-notification-form">
            <h3 class="section-title"><i class="fas fa-plus-circle"></i> Thêm thông báo mới</h3>
            <form action="{{ route('admin.notifications.store') }}" method="POST" id="addNotificationForm">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề thông báo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" value="{{ old('title') }}" placeholder="Nhập tiêu đề..." required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Nội dung <span class="text-danger">*</span></label>
                    <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="5"
                        placeholder="Nhập nội dung..." required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="reset" id="resetFormBtn" class="btn btn-secondary me-2">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm
                    </button>
                </div>
            </form>
        </div>

        <!-- Danh sách thông báo -->
        <div class="notifications-list">
            <h3 class="section-title"><i class="fas fa-bell"></i> Danh sách thông báo
                <span class="badge bg-primary">{{ $notifications->total() }}</span>
            </h3>

            @if ($notifications->count())
                <div class="row">
                    @foreach ($notifications as $notification)
                        <div class="col-md-6 col-lg-4">
                            <div class="notification-card">
                                <div class="notification-header">
                                    <h5 class="notification-title">
                                        <i class="fas fa-bullhorn"></i> {{ $notification->title }}
                                    </h5>
                                </div>
                                <div class="notification-body">
                                    <p class="notification-message">{{ $notification->message }}</p>
                                    <small class="notification-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ \Carbon\Carbon::parse($notification->notification_date)->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <div class="notification-footer text-end">
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="deleteNotification({{ $notification->notification_id }}, '{{ $notification->title }}')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $notifications->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="no-notifications">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>Chưa có thông báo nào</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning"></i> Xác nhận xóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa thông báo: <strong id="notificationTitle"></strong>?
                        <p class="text-danger mt-2"><small>Hành động này không thể hoàn tác!</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('resetFormBtn').addEventListener('click', function(e) {
                e.preventDefault();

                // Reset form
                document.getElementById('addNotificationForm').reset();

                // Xóa class lỗi nếu có
                ['title', 'message'].forEach(id => {
                    document.getElementById(id)?.classList.remove('is-invalid');
                });

                // Xóa thông báo lỗi
                document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            });
        });

        function deleteNotification(id, title) {
            document.getElementById('notificationTitle').innerText = title;
            document.getElementById('deleteForm').setAttribute('action', `/admin/notifications/${id}`);
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
@endsection