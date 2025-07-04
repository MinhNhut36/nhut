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

        input.correct {
            border: 2px solid green;
            background: #d4edda;
        }

        input.incorrect {
            border: 2px solid red;
            background: #f8d7da;
        }

        .fill-blank-input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 1rem;
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
            <div class="question-block" data-index="{{ $i }}" data-question-id="{{ $question->questions_id }}">
                <div class="question-counter">Câu {{ $i + 1 }} / {{ $totalQuestions }}</div>
                <p class="question-text">{{ $question->question_text }}</p>
                @if ($question->question_type == 'single_choice')
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
                @elseif ($question->question_type == 'fill_blank')
                    <input type="text" class="fill-blank-input" name="answer_{{ $question->questions_id }}"
                        placeholder="Nhập câu trả lời..." style="width:100%; padding:12px; font-size:16px;">
                @endif
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
                            Nộp bài
                        </button>
                        <a href="{{ route('student.lesson', ['course_id' => $courseId]) }}" class="btn btn-complete"
                            id="btn-complete">
                            Hoàn thành
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
                blocks.forEach((b, j) => b.classList.toggle('active', j === i));
                loading.classList.remove('active');
                updateProgress();
            }

            function showLoading() {
                blocks.forEach(b => b.classList.remove('active'));
                loading.classList.add('active');
            }

            // Hiển thị câu hỏi đầu tiên
            show(0);

            // Next / Previous
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

            // Submit bài làm
            if (btnSubmit) {
                btnSubmit.addEventListener('click', () => {
                    showLoading();

                    // 1. Thu thập câu trả lời
                    const answers = {};
                    blocks.forEach(block => {
                        const qid = block.getAttribute('data-question-id');
                        if (!qid) return;

                        // Kiểm tra có radio (single_choice) hay text input (fill_blank)
                        const radio = block.querySelector('input[type="radio"]:checked');
                        const text = block.querySelector('input[type="text"]');

                        if (radio) {
                            answers[qid] = radio.value;
                        } else if (text) {
                            answers[qid] = text.value.trim() || null;
                        }
                    });

                    // 2. Gửi lên server
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
                                show(idx);
                                return;
                            }

                            const results = json.results;

                            // 3. Hiển thị kết quả, highlight và feedback
                            Object.entries(results).forEach(([qid, data]) => {
                                // Tìm block theo data-question-id thay vì tìm theo input
                                const block = document.querySelector(`.question-block[data-question-id="${qid}"]`);
                                if (!block) {
                                    console.warn(`Không tìm thấy block cho câu hỏi ${qid}`);
                                    return;
                                }

                                // Nếu là single_choice
                                const radios = block.querySelectorAll('input[type="radio"]');
                                if (radios.length > 0) {
                                    radios.forEach(opt => {
                                        const label = block.querySelector(`label[for="${opt.id}"]`);
                                        if (!label) return;
                                        
                                        opt.disabled = true;
                                        label.classList.remove('correct', 'incorrect');
                                        
                                        // Highlight câu trả lời đúng
                                        if (parseInt(opt.value) === data.correct_answer) {
                                            label.classList.add('correct');
                                        }
                                        
                                        // Highlight câu trả lời sai của user
                                        if (data.your_answer && parseInt(opt.value) === data.your_answer && !data.is_correct) {
                                            label.classList.add('incorrect');
                                        }
                                    });
                                }
                                // Nếu là fill_blank
                                else {
                                    const input = block.querySelector('input[type="text"]');
                                    if (input) {
                                        input.disabled = true;
                                        input.classList.remove('correct', 'incorrect');
                                        input.classList.add(data.is_correct ? 'correct' : 'incorrect');
                                    }
                                }

                                // Thêm feedback
                                let fb = block.querySelector('.feedback');
                                if (!fb) {
                                    fb = document.createElement('div');
                                    fb.classList.add('feedback');
                                    block.appendChild(fb);
                                }
                                fb.classList.remove('correct', 'incorrect');
                                fb.classList.add(data.is_correct ? 'correct' : 'incorrect');
                                fb.innerHTML = data.feedback || (data.is_correct ? 'Chính xác!' : 'Sai rồi!');
                            });

                            // 4. Chuyển UI sang trạng thái đã nộp
                            btnSubmit.style.display = 'none';
                            btnComplete.style.display = 'inline-block';

                            // Quay lại câu đầu để review
                            idx = 0;
                            show(0);
                        })
                        .catch(err => {
                            console.error('Lỗi khi submit:', err);
                            alert("Lỗi mạng, vui lòng thử lại!");
                            show(idx);
                        });
                });
            }

            // Phím điều hướng
            document.addEventListener('keydown', e => {
                if (e.key === 'ArrowLeft' && idx > 0) {
                    idx--;
                    show(idx);
                }
                if (e.key === 'ArrowRight' && idx < total - 1) {
                    idx++;
                    show(idx);
                }
            });
        })();
    </script>
</body>

</html>