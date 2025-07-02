<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Trắc nghiệm</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png"
        href="https://cdn.haitrieu.com/wp-content/uploads/2023/01/Logo-Truong-Cao-dang-Ky-thuat-Cao-Thang.png">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem;
            margin: 0;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .progress-container {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            height: 8px;
            margin-top: 1rem;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #ff6b6b, #ffd93d);
            border-radius: 50px;
            transition: width 0.3s ease;
            width: 0%;
        }

        .question-block {
            display: none;
            padding: 2.5rem;
            animation: fadeIn 0.5s ease-in-out;
        }

        .question-block.active {
            display: block;
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

        .question-counter {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .question-text {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            line-height: 1.6;
            color: #2c3e50;
            font-weight: 500;
        }

        .answers {
            list-style: none;
            padding: 0;
            margin-bottom: 2rem;
        }

        .answers li {
            margin-bottom: 1rem;
        }

        .answers input[type="radio"] {
            display: none;
        }

        .answer-btn {
            display: block;
            width: 100%;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 2px solid #dee2e6;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: left;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            position: relative;
            overflow: hidden;
        }

        .answer-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .answer-btn:hover {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border-color: #2196f3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
        }

        .answer-btn:hover::before {
            left: 100%;
        }

        .answers input[type="radio"]:checked+.answer-btn {
            background: linear-gradient(135deg, #4caf50, #45a049);
            color: white;
            border-color: #4caf50;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .answers input[type="radio"]:disabled+.answer-btn {
            cursor: not-allowed;
            opacity: 0.7;
        }

        .btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none;
            padding: 0.8rem 2rem;
            margin: 0 0.5rem;
            cursor: pointer;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn:disabled {
            background: linear-gradient(135deg, #bbb, #999);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        }

        .btn-submit:hover:not(:disabled) {
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        .btn-complete {
            background: linear-gradient(135deg, #4caf50, #45a049);
            display: none;
        }

        .btn-complete:hover {
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .btn-group {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e9ecef;
        }

        .feedback {
            margin-top: 1.5rem;
            padding: 1rem;
            border-radius: 10px;
            font-style: italic;
            border: 1px solid #ddd;
            background: #f8f9fa;
            animation: slideDown 0.3s ease;
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

        .feedback.correct {
            background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
            color: #2e7d32;
            border-color: #4caf50;
        }

        .feedback.incorrect {
            background: linear-gradient(135deg, #ffebee, #ffcdd2);
            color: #c62828;
            border-color: #f44336;
        }

        .answer-btn.correct {
            background: linear-gradient(135deg, #4caf50, #45a049) !important;
            color: white !important;
            border-color: #4caf50 !important;
        }

        .answer-btn.incorrect {
            background: linear-gradient(135deg, #f44336, #e53935) !important;
            color: white !important;
            border-color: #f44336 !important;
        }

        .loading {
            display: none;
            padding: 2.5rem;
            text-align: center;
        }

        .loading.active {
            display: block;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .question-block {
                padding: 1.5rem;
            }

            .btn {
                padding: 0.7rem 1.5rem;
                margin: 0.25rem;
                font-size: 0.9rem;
            }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Luyện tập thôi nào</h1>
            <div class="progress-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
        </div>

        @php $total = $questions->count(); @endphp

        @foreach ($questions as $i => $question)
            <div class="question-block" data-index="{{ $i }}">
                <div class="question-counter">Câu {{ $i + 1 }} / {{ $totalQuestions }}</div>
                <p class="question-text">{{ $question->question_text }}</p>
                <ul class="answers">
                    @foreach ($question->answers as $j => $answer)
                        <li>
                            <input type="radio" name="answer_{{ $question->questions_id }}"
                                value="{{ $answer->answers_id }}"
                                id="q{{ $question->questions_id }}_a{{ $j }}">
                            <label for="q{{ $question->questions_id }}_a{{ $j }}" class="answer-btn">
                                {{ chr(65 + $j) }}. {{ $answer->answer_text }}
                            </label>
                        </li>
                    @endforeach
                </ul>
                <div class="btn-group">
                    <button class="btn btn-prev" @if ($i === 0) disabled @endif>
                        ← Câu trước
                    </button>
                    @if ($i < $total - 1)
                        <button class="btn btn-next">
                            Câu sau →
                        </button>
                    @else
                        <button class="btn btn-submit" id="btn-submit">
                            📝 Nộp bài
                        </button>
                        <a href="{{ route('student.lesson', ['level' => $level]) }}" class="btn btn-complete"
                            id="btn-complete">
                            ✅ Hoàn thành
                        </a>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Đang xử lý bài làm của bạn...</p>
        </div>
    </div>

    <script>
        (function() {
            const blocks = document.querySelectorAll('.question-block');
            const progressBar = document.getElementById('progress-bar');
            const btnSubmit = document.getElementById('btn-submit');
            const btnComplete = document.getElementById('btn-complete');
            const loading = document.getElementById('loading');
            let idx = 0;
            const total = blocks.length;

            function updateProgress() {
                const progress = ((idx + 1) / total) * 100;
                progressBar.style.width = progress + '%';
            }

            function show(i) {
                // Chỉ sử dụng classList.toggle('active') - không dùng style.display
                blocks.forEach((b, j) => b.classList.toggle('active', j === i));
                loading.classList.remove('active');
                updateProgress();
            }

            function showLoading() {
                // Ẩn tất cả question blocks
                blocks.forEach(b => b.classList.remove('active'));
                loading.classList.add('active');
            }

            // Hiển thị câu hỏi đầu tiên
            show(0);

            // Navigation buttons
            document.querySelectorAll('.btn-next').forEach(btn =>
                btn.addEventListener('click', () => {
                    if (idx < total - 1) {
                        idx++;
                        show(idx);
                    }
                })
            );

            document.querySelectorAll('.btn-prev').forEach(btn =>
                btn.addEventListener('click', () => {
                    if (idx > 0) {
                        idx--;
                        show(idx);
                    }
                })
            );

            // Submit functionality
            if (btnSubmit) {
                btnSubmit.addEventListener('click', () => {
                    // Hiển thị loading
                    showLoading();

                    const answers = {};
                    const allQuestions = document.querySelectorAll('.question-block');

                    allQuestions.forEach(block => {
                        const qid = block.querySelector('input[type="radio"]')?.name?.split('_')[1];
                        const selected = block.querySelector('input[type="radio"]:checked');
                        if (qid) {
                            answers[qid] = selected ? selected.value : null;
                        }
                    });

                    fetch(`/student/exercise/{{ $lessonPartId }}/submit`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                score_id: {{ $scoreId }},
                                course_id: {{ $courseId }},
                                answers: answers
                            })
                        })
                        .then(r => r.json())
                        .then(json => {
                            if (!json.success) {
                                alert("Có lỗi khi nộp bài. Vui lòng thử lại!");
                                show(idx); // Quay lại câu hỏi hiện tại
                                return;
                            }

                            const results = json.results;

                            // Process results
                            Object.entries(results).forEach(([qid, data]) => {
                                const allOptions = document.querySelectorAll(
                                    `input[name="answer_${qid}"]`);
                                allOptions.forEach(opt => {
                                    const label = document.querySelector(
                                        `label[for="${opt.id}"]`);
                                    opt.disabled = true;

                                    // Reset styles
                                    label.classList.remove('correct', 'incorrect');

                                    // Mark correct answer
                                    if (parseInt(opt.value) == data.correct_answer) {
                                        label.classList.add('correct');
                                    }

                                    // Mark incorrect user answer
                                    if (data.your_answer && parseInt(opt.value) == data
                                        .your_answer && !data.is_correct) {
                                        label.classList.add('incorrect');
                                    }
                                });

                                // Add feedback
                                const block = document.querySelector(`input[name="answer_${qid}"]`)
                                    ?.closest('.question-block');
                                if (block) {
                                    let fb = block.querySelector('.feedback');
                                    if (!fb) {
                                        fb = document.createElement('div');
                                        fb.classList.add('feedback');
                                        block.appendChild(fb);
                                    }

                                    fb.classList.remove('correct', 'incorrect');
                                    fb.classList.add(data.is_correct ? 'correct' : 'incorrect');
                                    fb.innerHTML = data.feedback || (data.is_correct ?
                                        '✅ Chính xác!' : '❌ Câu trả lời chưa đúng!');
                                }
                            });

                            // Hide submit button and show complete button
                            btnSubmit.style.display = 'none';
                            btnComplete.style.display = 'inline-block';

                            // Reset to first question to review
                            idx = 0;
                            show(0);
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert("Có lỗi khi nộp bài. Vui lòng thử lại!");
                            show(idx); // Quay lại câu hỏi hiện tại
                        });
                });
            }

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft' && idx > 0) {
                    idx--;
                    show(idx);
                } else if (e.key === 'ArrowRight' && idx < total - 1) {
                    idx++;
                    show(idx);
                }
            });
        })();
    </script>
</body>

</html>