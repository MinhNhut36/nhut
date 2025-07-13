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
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.1"/><circle cx="10" cy="90" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .student-avatar {
            position: relative;
            z-index: 1;
        }

        .student-avatar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .student-avatar img:hover {
            transform: scale(1.05);
        }

        .student-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .student-status {
            font-size: 16px;
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            display: inline-block;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        /* Info Section */
        .info-section {
            padding: 40px 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            position: relative;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #6366f1;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #6366f1, #8b5cf6);
            border-radius: 12px 0 0 12px;
        }

        .info-label {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 16px;
            color: #1e293b;
            font-weight: 600;
            line-height: 1.4;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .status-inactive {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-active {
            background: #dcfce7;
            color: #16a34a;
        }

        /* Scores Section */
        .scores-section {
            margin-top: 40px;
        }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '📊';
            font-size: 24px;
        }

        .lesson-part {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 25px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .lesson-part:hover {
            border-color: #6366f1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .part-header {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 18px 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .part-header:hover {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
        }

        .part-header h4 {
            font-size: 18px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-icon {
            font-size: 20px;
            transition: transform 0.3s ease;
        }

        .part-header.active .toggle-icon {
            transform: rotate(180deg);
        }

        .part-content {
            padding: 25px;
            background: white;
            display: none;
            animation: slideDown 0.3s ease-out;
        }

        .part-content.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .scores-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .scores-table th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            padding: 15px 12px;
            text-align: center;
            font-size: 14px;
            border-bottom: 2px solid #e2e8f0;
        }

        .scores-table td {
            padding: 15px 12px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .scores-table tr:hover {
            background: #f8fafc;
        }

        .scores-table tr:last-child td {
            border-bottom: none;
        }

        .no-data {
            text-align: center;
            color: #64748b;
            font-style: italic;
            padding: 40px;
            background: #f8fafc;
            border-radius: 8px;
        }

        /* Back Button */
        .back-section {
            text-align: center;
            padding: 30px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 12px;
            }

            .header {
                padding: 30px 20px;
            }

            .info-section {
                padding: 30px 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .student-name {
                font-size: 24px;
            }

            .section-title {
                font-size: 20px;
            }

            .scores-table {
                font-size: 13px;
            }

            .scores-table th,
            .scores-table td {
                padding: 10px 8px;
            }
        }

        .btn-icon-back {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 24px;
            text-decoration: none;
            z-index: 10;
            transition: transform 0.2s ease;
        }

        .btn-icon-back:hover {
            transform: scale(1.2);
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <a href="{{ route('teacher.coursemembers', ['courseId' => $course->assigned_course_id]) }}"
                class="btn-icon-back" title="Quay lại danh sách">
                ←
            </a>
            <div class="student-avatar">
                <img src="{{ asset('uploads/avatars/' . $student->avatar) }}" alt="Avatar"
                    onerror="this.onerror=null;this.src='{{ asset('uploads/avatars/AvtMacDinh.jpg') }}';">
            </div>
            <div class="student-name">{{ $student->fullname }}</div>
            <div class="student-status">{{ $student->is_status->getStatus() }}</div>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">Mã sinh viên</div>
                    <div class="info-value">{{ $student->student_id }}</div>
                </div>

                <div class="info-card">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $student->email }}</div>
                </div>

                <div class="info-card">
                    <div class="info-label">Ngày sinh</div>
                    <div class="info-value">{{ $student->date_of_birth }}</div>
                </div>

                <div class="info-card">
                    <div class="info-label">Ngày đăng ký</div>
                    <div class="info-value">{{ $course->registration_date }}</div>
                </div>

                <div class="info-card">
                    <div class="info-label">Trạng thái</div>
                    <div class="info-value">
                        <span class="status-badge {{ $student->is_status->badgeClass() }}">
                            {{ $student->is_status->getStatus() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Scores Section -->
            @if ($lessonParts->count())
                <div class="scores-section">
                    <div class="section-title">
                        Bảng điểm chi tiết từng phần bài học
                    </div>

                    @foreach ($lessonParts as $index => $part)
                        <div class="lesson-part">
                            <div class="part-header" onclick="toggleScoreTable({{ $index }})">
                                <h4>
                                    <span>🔹</span>
                                    {{ $part->part_type }}
                                </h4>
                                <span class="toggle-icon">▼</span>
                            </div>

                            <div id="score-table-{{ $index }}" class="part-content">
                                @if ($part->scores->count())
                                    <table class="scores-table">
                                        <thead>
                                            <tr>
                                                <th>Lần làm</th>
                                                <th>Điểm</th>
                                                <th>Số câu</th>
                                                <th>Câu đúng</th>
                                                <th>Thời gian nộp</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($part->scores as $score)
                                                <tr>
                                                    <td><strong>{{ $score->attempt_no }}</strong></td>
                                                    <td><strong style="color: #059669;">{{ $score->score }}</strong>
                                                    </td>
                                                    <td>{{ $score->total_questions }}</td>
                                                    <td>{{ $score->correct_answers }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($score->submit_time)->format('d/m/Y H:i') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="no-data">
                                        <p>📝 Sinh viên chưa làm bài</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-data">
                    <p>📚 Không có phần học nào để hiển thị</p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="back-section">
            <a href="{{ route('teacher.coursemembers', ['courseId' => $course->assigned_course_id]) }}"
                class="btn-back">
                <span>←</span>
                Quay lại danh sách sinh viên
            </a>
        </div>
    </div>

    <script>
        function toggleScoreTable(index) {
            const content = document.getElementById('score-table-' + index);
            const header = content.previousElementSibling;

            if (content.classList.contains('show')) {
                content.classList.remove('show');
                header.classList.remove('active');
            } else {
                content.classList.add('show');
                header.classList.add('active');
            }
        }
    </script>
</body>

</html>
