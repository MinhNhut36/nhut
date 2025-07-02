<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin chi tiết sinh viên</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 24px;
            border-radius: 12px 12px 0 0;
            text-align: center;
            position: relative;
        }

        .close-btn {
            position: absolute;
            right: 16px;
            top: 16px;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .student-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            margin: 0 auto 12px;
        }

        .student-name {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .student-status {
            font-size: 14px;
            opacity: 0.9;
            padding: 4px 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: inline-block;
        }

        .modal-body {
            padding: 24px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
            flex: 1;
        }

        .info-value {
            font-size: 15px;
            color: #1e293b;
            font-weight: 600;
            flex: 2;
            text-align: right;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-inactive {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-active {
            background: #dcfce7;
            color: #16a34a;
        }

        .progress-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .progress-bar {
            width: 80px;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            border-radius: 3px;
        }

        .progress-text {
            font-size: 14px;
            font-weight: 600;
            color: #4f46e5;
        }

        @media (max-width: 600px) {
            .modal-content {
                margin: 10px;
                max-width: calc(100% - 20px);
            }

            .modal-body {
                padding: 20px;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .info-value {
                text-align: left;
            }
        }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            border-radius: 8px;
            transition: background 0.3s, transform 0.2s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="modal-content">
        <!-- Header -->
        <div class="modal-header">
            <div class="student-avatar">
                <img src="{{ asset('uploads/avatars/' . $student->avatar) }}" alt="Avatar" class="student-avatar me-3"
                    onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/AvtMacDinh.jpg') }}';">
            </div>
            <div class="student-name">{{ $student->fullname }}</div>
            <div class="student-status">{{ $student->is_status->getStatus() }}
            </div>
        </div>

        <!-- Body -->
        <div class="modal-body">
            <div class="info-row">
                <div class="info-label">Mã sinh viên</div>
                <div class="info-value">{{ $student->student_id }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $student->email }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Ngày sinh</div>
                <div class="info-value">{{ $student->date_of_birth }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Ngày đăng ký</div>
                <div class="info-value">{{ $course->registration_date }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Trạng thái</div>
                <div class="info-value">
                    <span
                        class="status-badge {{ $student->is_status->badgeClass() }} ">{{ $student->is_status->getStatus() }}</span>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('teacher.coursemembers', ['courseId' => $course->assigned_course_id]) }}"
                class="btn-back">
                ⬅ Quay lại danh sách sinh viên
            </a>
        </div>
    </div>

</body>

</html>
