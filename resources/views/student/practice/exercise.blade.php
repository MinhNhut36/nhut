<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Trắc nghiệm</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png"
        href="https://cdn.haitrieu.com/wp-content/uploads/2023/01/Logo-Truong-Cao-dang-Ky-thuat-Cao-Thang.png">
    <link rel="stylesheet" href="{{ asset('css/exercise.css') }}">
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

        @php $totalQuest = $questions->count(); @endphp

        @foreach ($questions as $i => $question)
            <div class="question-block" data-index="{{ $i }}"
                data-question-id="{{ $question->questions_id }}">
                <div class="question-counter">Câu {{ $i + 1 }} / {{ $totalQuest }}</div>
                <p class="question-text">{{ $question->question_text }}</p>
                @if ($question->question_type == 'single_choice')
                    <ul class="answers">
                        @foreach ($question->shuffled_answers as $j => $answer)
                            <li>
                                <input type="radio" name="answer_{{ $question->questions_id }}"
                                    value="{{ $answer->answers_id }}"
                                    id="q{{ $question->questions_id }}_a{{ $j }}"
                                    data-is-correct="{{ $answer->is_correct ? 'true' : 'false' }}">
                                <label for="q{{ $question->questions_id }}_a{{ $j }}" class="answer-btn">
                                    {{ chr(65 + $j) }}. {{ $answer->answer_text }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                @elseif ($question->question_type == 'fill_blank')
                    <input type="text" class="fill-blank-input" name="answer_{{ $question->questions_id }}"
                        placeholder="Nhập câu trả lời..."
                        data-correct-answer="{{ $question->answers->where('is_correct', true)->first()->answer_text ?? '' }}">
                @elseif ($question->question_type == 'matching')
                    <div class="matching-container" data-question-id="{{ $question->questions_id }}">
                        {{-- Row 1: Text Items --}}
                        <div class="matching-row">
                            <div class="text-items-column">
                                @foreach ($question->shuffled_texts as $textAnswer)
                                    <div class="matching-item" draggable="true"
                                        data-match-key="{{ $textAnswer->match_key }}"
                                        data-answer-id="{{ $textAnswer->answers_id }}"
                                        data-animal="{{ strtolower($textAnswer->answer_text) }}">
                                        {{ $textAnswer->answer_text }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Row 2: Image Items --}}
                        <div class="matching-row">
                            <div class="image-items-column">
                                @foreach ($question->shuffled_images as $imageAnswer)
                                    <div class="image-drop-zone" data-match-key="{{ $imageAnswer->match_key }}"
                                        data-answer-id="{{ $imageAnswer->answers_id }}">
                                        <img src="{{ $imageAnswer->media_url }}" alt="Matching image">
                                        <div class="drop-text">Thả tên vào đây</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="control-buttons">
                        <button type="button" class="btn reset-btn reset-matching"
                            onclick="resetMatching({{ $question->questions_id }})">
                            🔄 Đặt lại
                        </button>
                    </div>
                @elseif ($question->question_type == 'arrangement')
                    <div class="arrangement-container" data-question-id="{{ $question->questions_id }}">
                        {{-- Hình ảnh câu hỏi nếu có --}}
                        @if ($question->media_url)
                            <div class="question-image">
                                <img src="{{ $question->media_url }}" alt="Hình ảnh câu hỏi" />
                            </div>
                        @endif

                        <div class="arrangement-instruction">
                            📝 Kéo thả hoặc click vào các từ để tạo thành câu hoàn chỉnh.
                        </div>

                        {{-- Khu vực tạo câu --}}
                        <div class="sentence-builder" data-question-id="{{ $question->questions_id }}">
                            <div class="sentence-builder-label">Câu của bạn:</div>
                        </div>

                        {{-- Khu vực chứa từ --}}
                        <div class="words-pool" data-question-id="{{ $question->questions_id }}">
                            <div class="words-pool-label">Click hoặc kéo các từ từ đây:</div>
                            @foreach ($question->answers as $word)
                                <div class="word-item clickable" draggable="true" data-word="{{ $word->answer_text }}"
                                    data-match-key="{{ $word->match_key }}" data-order="{{ $word->order_index }}"
                                    data-answer-id="{{ $word->answers_id }}">
                                    {{ $word->answer_text }}
                                </div>
                            @endforeach
                        </div>

                        <div class="arrangement-controls">
                            <button type="button" class="btn-reset-arrangement"
                                onclick="resetArrangement({{ $question->questions_id }})">
                                🔄 Đặt lại
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Thông báo validation -->
                <div class="validation-message" id="validation-{{ $question->questions_id }}">
                    <strong>⚠️ Vui lòng chọn một đáp án trước khi tiếp tục!</strong>
                </div>

                <div class="btn-group">
                    <button class="btn btn-prev" @if ($i === 0) disabled @endif>
                        ← Câu trước
                    </button>
                    @if ($i < $totalQuest - 1)
                        <button class="btn btn-next">
                            Câu sau →
                        </button>
                    @else
                        <button class="btn btn-submit" id="btn-submit" style="display: block;">
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



</body>
<script>
    // Hàm đặt lại matching
    function resetMatching(questionId) {
        const container = document.querySelector(`\[data-question-id="${questionId}"]`);
        if (!container) return;

        const matchingContainer = container.querySelector('.matching-container');
        if (!matchingContainer) return;

        // Xử lý image-drop-zone format (từ file đầu tiên)
        const imageDropZones = matchingContainer.querySelectorAll('.image-drop-zone');
        if (imageDropZones.length > 0) {
            const textItems = matchingContainer.querySelectorAll('.matching-item');

            // Reset text items
            textItems.forEach(item => {
                item.classList.remove('used', 'correct', 'incorrect');
                item.draggable = true;
            });

            // Reset drop zones
            imageDropZones.forEach(zone => {
                zone.classList.remove('filled', 'correct', 'incorrect');
                const matchedText = zone.querySelector('.matched-text');
                if (matchedText) {
                    matchedText.remove();
                }
                const dropText = zone.querySelector('.drop-text');
                if (dropText) {
                    dropText.style.display = 'block';
                }
            });
        }

        // Xử lý drop-zone format (từ file thứ hai)
        const dropZones = matchingContainer.querySelectorAll('.drop-zone');
        if (dropZones.length > 0) {
            dropZones.forEach(zone => {
                const droppedItem = zone.querySelector('.matching-item');
                if (droppedItem) {
                    // Trả item về cột text
                    const textColumn = matchingContainer.querySelector('.text-items');
                    if (textColumn) {
                        textColumn.appendChild(droppedItem);
                    }

                    // Reset styles
                    droppedItem.classList.remove('connected', 'correct', 'incorrect');
                    zone.classList.remove('filled');
                    const dropText = zone.querySelector('.drop-text');
                    if (dropText) {
                        dropText.style.display = 'block';
                    }
                }
            });
        }

        // Xóa feedback khác
        const otherFeedback = container.querySelector('.matching-feedback');
        if (otherFeedback) {
            otherFeedback.remove();
        }
    }

    // Hàm đặt lại arrangement
    function resetArrangement(questionId) {
        const container = document.querySelector(`\[data-question-id="${questionId}"]`);
        if (!container) return;

        const arrangementContainer = container.querySelector('.arrangement-container');
        if (!arrangementContainer) return;

        const sentenceBuilder = arrangementContainer.querySelector('.sentence-builder');
        const wordsPool = arrangementContainer.querySelector('.words-pool');

        // Trả tất cả từ từ sentence builder về words pool
        const wordsInBuilder = sentenceBuilder.querySelectorAll('.word-item');
        wordsInBuilder.forEach(word => {
            word.classList.remove('in-sentence', 'correct', 'incorrect');
            word.classList.add('clickable');
            word.draggable = true;
            wordsPool.appendChild(word);
        });

        // Xóa feedback
        const feedback = container.querySelector('.arrangement-feedback');
        if (feedback) {
            feedback.remove();
        }
    }

    // Khởi tạo arrangement drag and drop và click
    function initArrangement() {
        const arrangementContainers = document.querySelectorAll('.arrangement-container');

        arrangementContainers.forEach(container => {
            const sentenceBuilder = container.querySelector('.sentence-builder');
            const wordsPool = container.querySelector('.words-pool');

            // Xử lý click vào từ trong words pool
            wordsPool.addEventListener('click', function(e) {
                if (e.target.classList.contains('word-item') && e.target.classList.contains(
                        'clickable')) {
                    const word = e.target;

                    // Chuyển từ sang sentence builder
                    word.classList.remove('clickable');
                    word.classList.add('in-sentence');
                    sentenceBuilder.appendChild(word);
                }
            });

            // Xử lý click vào từ trong sentence builder để trả về
            sentenceBuilder.addEventListener('click', function(e) {
                if (e.target.classList.contains('word-item') && e.target.classList.contains(
                        'in-sentence')) {
                    const word = e.target;

                    // Trả từ về words pool
                    word.classList.remove('in-sentence', 'correct', 'incorrect');
                    word.classList.add('clickable');
                    word.draggable = true;
                    wordsPool.appendChild(word);
                }
            });

            // Xử lý drag and drop cho words pool
            const wordItems = container.querySelectorAll('.word-item');
            wordItems.forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    if (!this.draggable) return;

                    const data = {
                        word: this.dataset.word,
                        matchKey: this.dataset.matchKey,
                        order: this.dataset.order,
                        answerId: this.dataset.answerId
                    };

                    e.dataTransfer.setData('text/plain', JSON.stringify(data));
                    this.classList.add('dragging');
                });

                item.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                });
            });

            // Xử lý drop vào sentence builder
            sentenceBuilder.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('drag-over');
            });

            sentenceBuilder.addEventListener('dragleave', function() {
                this.classList.remove('drag-over');
            });

            sentenceBuilder.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');

                try {
                    const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                    const draggedItem = container.querySelector(`\[data-answer-id="${data.answerId}"]`);

                    if (draggedItem && draggedItem.classList.contains('clickable')) {
                        // Chuyển từ sang sentence builder
                        draggedItem.classList.remove('clickable');
                        draggedItem.classList.add('in-sentence');
                        this.appendChild(draggedItem);
                    }
                } catch (error) {
                    console.error('Error parsing drag data:', error);
                }
            });

            // Xử lý drop vào words pool để trả về
            wordsPool.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('drag-over');
            });

            wordsPool.addEventListener('dragleave', function() {
                this.classList.remove('drag-over');
            });

            wordsPool.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('drag-over');

                try {
                    const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                    const draggedItem = container.querySelector(`\[data-answer-id="${data.answerId}"]`);

                    if (draggedItem && draggedItem.classList.contains('in-sentence')) {
                        // Trả từ về words pool
                        draggedItem.classList.remove('in-sentence', 'correct', 'incorrect');
                        draggedItem.classList.add('clickable');
                        draggedItem.draggable = true;
                        this.appendChild(draggedItem);
                    }
                } catch (error) {
                    console.error('Error parsing drag data:', error);
                }
            });
        });
    }

    // Khởi tạo Drag and Drop - Phiên bản gộp
    function initDragAndDrop() {
        const containers = document.querySelectorAll('.matching-container');

        containers.forEach(container => {
            // Xử lý text items drag events
            const textItems = container.querySelectorAll('.matching-item');
            textItems.forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    const data = {
                        matchKey: this.dataset.matchKey,
                        answerId: this.dataset.answerId,
                        text: this.textContent.trim()
                    };

                    e.dataTransfer.setData('text/plain', JSON.stringify(data));
                    e.dataTransfer.setData('answer-id', this.dataset.answerId);
                    this.classList.add('dragging');
                });

                item.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                });
            });

            // Xử lý image-drop-zone format
            const imageDropZones = container.querySelectorAll('.image-drop-zone');
            imageDropZones.forEach(zone => {
                zone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('drag-over');
                });

                zone.addEventListener('dragleave', function() {
                    this.classList.remove('drag-over');
                });

                zone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');

                    const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                    const draggedItem = container.querySelector(
                        `\[data-answer-id="${data.answerId}"]`);

                    if (!draggedItem) return;

                    // Xóa matched text cũ nếu có
                    const existingText = this.querySelector('.matched-text');
                    if (existingText) {
                        const oldMatchKey = existingText.dataset.matchKey;
                        const oldItem = container.querySelector(
                            `\[data-match-key="${oldMatchKey}"]`);
                        if (oldItem) {
                            oldItem.classList.remove('used');
                            oldItem.draggable = true;
                        }
                        existingText.remove();
                    }

                    // Thêm matched text mới
                    const matchedText = document.createElement('div');
                    matchedText.className = 'matched-text';
                    matchedText.textContent = data.text;
                    matchedText.dataset.matchKey = data.matchKey;
                    this.appendChild(matchedText);

                    // Ẩn drop text
                    const dropText = this.querySelector('.drop-text');
                    if (dropText) {
                        dropText.style.display = 'none';
                    }

                    // Đánh dấu trạng thái
                    this.classList.add('filled');
                    draggedItem.classList.add('used');
                    draggedItem.draggable = false;
                });
            });

            // Xử lý drop-zone format
            const dropZones = container.querySelectorAll('.drop-zone');
            dropZones.forEach(zone => {
                zone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('drag-over');
                });

                zone.addEventListener('dragleave', function() {
                    this.classList.remove('drag-over');
                });

                zone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');

                    const draggedMatchKey = e.dataTransfer.getData('text/plain');
                    const draggedAnswerId = e.dataTransfer.getData('answer-id');

                    let draggedItem;
                    try {
                        const data = JSON.parse(draggedMatchKey);
                        draggedItem = container.querySelector(
                            `\[data-answer-id="${data.answerId}"]`);
                    } catch {
                        draggedItem = container.querySelector(
                            `\[data-match-key="${draggedMatchKey}"]\[data-answer-id="${draggedAnswerId}"]`
                        );
                    }

                    if (!draggedItem) return;

                    // Kiểm tra xem drop zone đã có item chưa
                    const existingItem = this.querySelector('.matching-item');
                    if (existingItem) {
                        // Trả item cũ về cột text
                        const textColumn = container.querySelector('.text-items');
                        if (textColumn) {
                            textColumn.appendChild(existingItem);
                        }
                        existingItem.classList.remove('connected', 'correct', 'incorrect');
                    }

                    // Thêm item mới vào drop zone
                    this.appendChild(draggedItem);
                    this.classList.add('filled');

                    const dropText = this.querySelector('.drop-text');
                    if (dropText) {
                        dropText.style.display = 'none';
                    }

                    // Kiểm tra đúng/sai
                    const isCorrect = draggedItem.dataset.matchKey === this.dataset.matchKey;
                    draggedItem.classList.add(isCorrect ? 'correct' : 'connected');
                });
            });
        });
    }

    // Main application logic
    (function() {
        const blocks = document.querySelectorAll('.question-block');
        const progressBar = document.getElementById('progress-bar');
        const btnSubmit = document.getElementById('btn-submit');
        const btnComplete = document.getElementById('btn-complete');
        const loading = document.getElementById('loading');
        let idx = 0;
        let isSubmitted = false;
        const total = blocks.length;

        function updateProgress() {
            const progress = ((idx + 1) / total) * 100;
            if (progressBar) {
                progressBar.style.width = progress + '%';
            }
        }

        function toggleSubmitCompleteButtons() {
            const btnSubmit = document.getElementById('btn-submit');
            const btnComplete = document.getElementById('btn-complete');

            if (btnSubmit && btnComplete) {
                if (isSubmitted) {
                    btnSubmit.style.display = 'none';
                    btnComplete.style.display = 'inline-block';
                } else {
                    btnSubmit.style.display = 'inline-block';
                    btnComplete.style.display = 'none';
                }
            }
        }

        function show(i) {
            blocks.forEach((b, j) => b.classList.toggle('active', j === i));
            if (loading) loading.classList.remove('active');
            const statisticsView = document.getElementById('statistics-view');
            if (statisticsView) {
                statisticsView.classList.remove('active');
            }
            updateProgress();
            toggleSubmitCompleteButtons();
        }

        function showLoading() {
            blocks.forEach(b => b.classList.remove('active'));
            const statisticsView = document.getElementById('statistics-view');
            if (statisticsView) {
                statisticsView.classList.remove('active');
            }
            if (loading) loading.classList.add('active');
        }

        // Kiểm tra câu hỏi hiện tại đã được trả lời chưa
        function isCurrentQuestionAnswered() {
            const currentBlock = blocks[idx];
            return isQuestionAnswered(currentBlock);
        }

        // Kiểm tra câu hỏi cụ thể đã được trả lời chưa
        function isQuestionAnswered(block) {
            // Kiểm tra radio button (single_choice)
            const radio = block.querySelector('input[type="radio"]:checked');
            if (radio) return true;

            // Kiểm tra text input (fill_blank)
            const textInput = block.querySelector('input[type="text"]');
            if (textInput && textInput.value.trim() !== '') return true;

            // Kiểm tra matching
            const matchingContainer = block.querySelector('.matching-container');
            if (matchingContainer) {
                const imageDropZones = matchingContainer.querySelectorAll('.image-drop-zone');
                const dropZones = matchingContainer.querySelectorAll('.drop-zone');

                if (imageDropZones.length > 0) {
                    const filledZones = matchingContainer.querySelectorAll('.image-drop-zone.filled');
                    return imageDropZones.length === filledZones.length;
                }

                if (dropZones.length > 0) {
                    const filledZones = matchingContainer.querySelectorAll('.drop-zone.filled');
                    return dropZones.length === filledZones.length;
                }
            }

            // Kiểm tra arrangement - chỉ cần có ít nhất 1 từ trong sentence builder
            const arrangementContainer = block.querySelector('.arrangement-container');
            if (arrangementContainer) {
                const sentenceBuilder = arrangementContainer.querySelector('.sentence-builder');
                const wordsInSentence = sentenceBuilder.querySelectorAll('.word-item.in-sentence');
                return wordsInSentence.length > 0;
            }

            return false;
        }

        // Hiển thị thông báo validation
        function showValidationMessage(questionId) {
            const validationMsg = document.getElementById(`validation-${questionId}`);
            if (validationMsg) {
                validationMsg.classList.add('show');
                setTimeout(() => {
                    validationMsg.classList.remove('show');
                }, 3000);
            }
        }

        // Tìm câu hỏi đầu tiên chưa được trả lời
        function findFirstUnansweredQuestion() {
            for (let i = 0; i < blocks.length; i++) {
                if (!isQuestionAnswered(blocks[i])) {
                    return i;
                }
            }
            return -1;
        }

        // Function tạo view thống kê
        function createStatisticsView(correctCount, totalQuestions) {
            const statisticsHTML = `
            <div class="question-block statistics-view" id="statistics-view">
                <div class="statistics-header">
                    <h2>🎉 Kết quả bài làm</h2>
                    <div class="score-summary">
                        <div class="score-circle">
                            <div class="score-number">${correctCount}/${totalQuestions}</div>
                            <div class="score-label">Câu đúng</div>
                        </div>
                        <div class="score-percentage">
                            <span class="percentage-number">${Math.round((correctCount / totalQuestions) * 100)}%</span>
                            <span class="percentage-label">Điểm số</span>
                        </div>
                    </div>
                </div>
 
                <div class="statistics-details">
                    <div class="stat-item correct-stat">
                        <div class="stat-icon">✅</div>
                        <div class="stat-content">
                            <div class="stat-number">${correctCount}</div>
                            <div class="stat-label">Câu trả lời đúng</div>
                        </div>
                    </div>
 
                    <div class="stat-item incorrect-stat">
                        <div class="stat-icon">❌</div>
                        <div class="stat-content">
                            <div class="stat-number">${totalQuestions - correctCount}</div>
                            <div class="stat-label">Câu trả lời sai</div>
                        </div>
                    </div>
 
                    <div class="stat-item total-stat">
                        <div class="stat-icon">📊</div>
                        <div class="stat-content">
                            <div class="stat-number">${totalQuestions}</div>
                            <div class="stat-label">Tổng số câu</div>
                        </div>
                    </div>
                </div>
 
                <div class="performance-message">
                    ${getPerformanceMessage(correctCount, totalQuestions)}
                </div>
 
                <div class="btn-group">
                    <button class="btn btn-review" id="btn-review">
                        📝 Xem lại bài làm
                    </button>
                    <a href="{{ route('student.lesson', ['course_id' => $courseId]) }}" class="btn btn-complete">
                        🏁 Hoàn thành
                    </a>
                </div>
           </div>
      `;

            const container = document.querySelector('.container');
            if (container) {
                container.insertAdjacentHTML('beforeend', statisticsHTML);
            }

            const btnReview = document.getElementById('btn-review');
            if (btnReview) {
                btnReview.addEventListener('click', () => {
                    idx = 0;
                    show(0);
                });
            }
        }

        function showStatistics() {
            blocks.forEach(b => b.classList.remove('active'));
            if (loading) loading.classList.remove('active');
            const statisticsView = document.getElementById('statistics-view');
            if (statisticsView) {
                statisticsView.classList.add('active');
            }
            if (progressBar) {
                progressBar.style.width = '100%';
            }
        }

        function getPerformanceMessage(correct, total) {
            const percentage = (correct / total) * 100;

            if (percentage >= 90) {
                return `
                <div class="performance excellent">
                    <div class="performance-icon">🌟</div>
                    <div class="performance-text">
                        <h3>Xuất sắc!</h3>
                        <p>Bạn đã làm rất tốt! Hãy tiếp tục phát huy nhé!</p>
                    </div>
                </div>
            `;
            } else if (percentage >= 75) {
                return `
                <div class="performance good">
                    <div class="performance-icon">🎯</div>
                    <div class="performance-text">
                        <h3>Tốt!</h3>
                        <p>Kết quả khá tốt, hãy cố gắng thêm một chút nữa!</p>
                    </div>
                </div>
            `;
            } else if (percentage >= 50) {
                return `
                <div class="performance average">
                    <div class="performance-icon">💪</div>
                    <div class="performance-text">
                        <h3>Cần cải thiện</h3>
                        <p>Bạn cần ôn tập thêm để nắm vững kiến thức hơn.</p>
                    </div>
                </div>
            `;
            } else {
                return `
                <div class="performance needs-work">
                    <div class="performance-icon">📚</div>
                    <div class="performance-text">
                        <h3>Cần học thêm</h3>
                        <p>Hãy xem lại bài học và luyện tập thêm nhé!</p>
                    </div>
                </div>
            `;
            }
        }

        // Khởi tạo ứng dụng
        if (blocks.length > 0) {
            show(0);
        }

        // Khởi tạo drag and drop
        initDragAndDrop();
        // Khởi tạo arrangement
        initArrangement();

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            initDragAndDrop();
            initArrangement();
        });

        // Next button với validation
        document.querySelectorAll('.btn-next').forEach(btn =>
            btn.addEventListener('click', () => {
                if (!isSubmitted && !isCurrentQuestionAnswered()) {
                    const currentBlock = blocks[idx];
                    const qid = currentBlock.getAttribute('data-question-id');
                    showValidationMessage(qid);
                    return;
                }

                if (idx < total - 1) {
                    idx++;
                    show(idx);
                }
            })
        );

        // Previous button
        document.querySelectorAll('.btn-prev').forEach(btn =>
            btn.addEventListener('click', () => {
                if (idx > 0) {
                    idx--;
                    show(idx);
                }
            })
        );

        // Submit bài làm với validation
        if (btnSubmit) {
            btnSubmit.addEventListener('click', () => {
                const firstUnansweredIndex = findFirstUnansweredQuestion();

                if (firstUnansweredIndex !== -1) {
                    idx = firstUnansweredIndex;
                    show(idx);
                    const unansweredBlock = blocks[firstUnansweredIndex];
                    const qid = unansweredBlock.getAttribute('data-question-id');
                    showValidationMessage(qid);
                    return;
                }

                showLoading();

                // Thu thập câu trả lời
                const answers = {};
                blocks.forEach(block => {
                    const qid = block.getAttribute('data-question-id');
                    if (!qid) return;

                    const radio = block.querySelector('input[type="radio"]:checked');
                    const text = block.querySelector('input[type="text"]');
                    const matchingContainer = block.querySelector('.matching-container');
                    const arrangementContainer = block.querySelector('.arrangement-container');

                    if (radio) {
                        answers[qid] = radio.value;
                    } else if (text) {
                        answers[qid] = text.value.trim() || null;
                    } else if (matchingContainer) {
                        // Thu thập kết quả matching
                        const matches = {};

                        // Kiểm tra image-drop-zone format
                        const imageDropZones = matchingContainer.querySelectorAll(
                            '.image-drop-zone.filled');
                        if (imageDropZones.length > 0) {
                            imageDropZones.forEach(zone => {
                                const matchedText = zone.querySelector('.matched-text');
                                if (matchedText) {
                                    matches[matchedText.dataset.matchKey] = zone.dataset
                                        .matchKey;
                                }
                            });
                        }

                        // Kiểm tra drop-zone format
                        const dropZones = matchingContainer.querySelectorAll('.drop-zone.filled');
                        if (dropZones.length > 0) {
                            dropZones.forEach(zone => {
                                const droppedItem = zone.querySelector('.matching-item');
                                if (droppedItem) {
                                    matches[droppedItem.dataset.matchKey] = zone.dataset
                                        .matchKey;
                                }
                            });
                        }

                        answers[qid] = matches;
                    } else if (arrangementContainer) {
                        // Thu thập kết quả arrangement
                        const sentenceBuilder = arrangementContainer.querySelector(
                            '.sentence-builder');
                        const wordsInSentence = sentenceBuilder.querySelectorAll(
                            '.word-item.in-sentence');

                        const arrangement = [];
                        wordsInSentence.forEach(word => {
                            arrangement.push(word.dataset.word);
                        });

                        answers[qid] = arrangement;
                    }
                });

                // Gửi lên server
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

                        isSubmitted = true;
                        const results = json.results;

                        // Hiển thị kết quả cho từng câu hỏi
                        Object.entries(results).forEach(([qid, data]) => {
                            const block = document.querySelector(
                                `.question-block\[data-question-id="${qid}"]`);
                            if (!block) return;

                            // Xử lý single_choice
                            const radios = block.querySelectorAll('input[type="radio"]');
                            if (radios.length > 0) {
                                radios.forEach(radio => {
                                    const label = block.querySelector(
                                        `label\[for="${radio.id}"]`);
                                    if (!label) return;

                                    radio.disabled = true;
                                    label.classList.remove('correct', 'incorrect');

                                    if (radio.getAttribute('data-is-correct') ===
                                        'true') {
                                        label.classList.add('correct');
                                    }

                                    if (radio.checked && radio.getAttribute(
                                            'data-is-correct') === 'false') {
                                        label.classList.add('incorrect');
                                    }
                                });
                            }
                            // Xử lý fill_blank
                            else if (block.querySelector('input[type="text"]')) {
                                const input = block.querySelector('input[type="text"]');
                                input.disabled = true;
                                input.classList.remove('correct', 'incorrect');
                                input.classList.add(data.is_correct ? 'correct' : 'incorrect');

                                if (!data.is_correct) {
                                    const correctAnswer = input.getAttribute(
                                        'data-correct-answer');
                                    if (correctAnswer) {
                                        const correctDiv = document.createElement('div');
                                        correctDiv.className = 'correct-answer';
                                        correctDiv.innerHTML =
                                            `<strong>Đáp án đúng:</strong> ${correctAnswer}`;
                                        input.parentNode.insertBefore(correctDiv, input
                                            .nextSibling);
                                    }
                                }
                            }
                            // Xử lý matching
                            else if (block.querySelector('.matching-container')) {
                                const matchingContainer = block.querySelector(
                                    '.matching-container');

                                // Xử lý image-drop-zone
                                const imageDropZones = matchingContainer.querySelectorAll(
                                    '.image-drop-zone');
                                imageDropZones.forEach(zone => {
                                    const matchedText = zone.querySelector(
                                        '.matched-text');
                                    if (matchedText) {
                                        const isCorrect = matchedText.dataset
                                            .matchKey === zone.dataset.matchKey;
                                        zone.classList.remove('filled');
                                        zone.classList.add(isCorrect ? 'correct' :
                                            'incorrect');
                                    }
                                });

                                // Xử lý drop-zone
                                const dropZones = matchingContainer.querySelectorAll(
                                    '.drop-zone');
                                dropZones.forEach(zone => {
                                    const droppedItem = zone.querySelector(
                                        '.matching-item');
                                    if (droppedItem) {
                                        const isCorrect = droppedItem.dataset
                                            .matchKey === zone.dataset.matchKey;
                                        droppedItem.classList.remove('connected');
                                        droppedItem.classList.add(isCorrect ?
                                            'correct' : 'incorrect');
                                        droppedItem.draggable = false;
                                        droppedItem.style.cursor = 'default';
                                    }
                                });

                                // Disable reset button
                                const resetBtn = block.querySelector('.reset-matching');
                                if (resetBtn) {
                                    resetBtn.disabled = true;
                                    resetBtn.style.opacity = '0.5';
                                }
                            } // Xử lý arrangement
                            else if (block.querySelector('.arrangement-container')) {
                                const arrangementContainer = block.querySelector(
                                    '.arrangement-container');
                                const sentenceBuilder = arrangementContainer.querySelector(
                                    '.sentence-builder');
                                const wordsPool = arrangementContainer.querySelector(
                                    '.words-pool');
                                const wordsInSentence = sentenceBuilder.querySelectorAll(
                                    '.word-item');
                                const wordsInPool = wordsPool.querySelectorAll(
                                    '.word-item');

                                // Disable tất cả các từ
                                wordsInSentence.forEach(word => {
                                    word.classList.remove('clickable');
                                    word.draggable = false;
                                    word.style.cursor = 'default';
                                    word.style.pointerEvents = 'none';
                                });
                                wordsInPool.forEach(word => {
                                    word.classList.remove('clickable');
                                    word.draggable = false;
                                    word.style.cursor = 'default';
                                    word.style.pointerEvents = 'none';
                                });

                                // Tô màu đúng/sai cho từng từ
                                if (data.word_results) {
                                    Object.entries(data.word_results).forEach(([answerId,
                                        result
                                    ]) => {
                                        const word = arrangementContainer.querySelector(
                                            `[data-answer-id="${answerId}"]`);
                                        if (word) {
                                            word.classList.add(result.is_correct ?
                                                'correct' : 'incorrect');
                                        }
                                    });
                                }

                                // Disable reset button
                                const resetBtn = block.querySelector('.btn-reset-arrangement');
                                if (resetBtn) {
                                    resetBtn.disabled = true;
                                    resetBtn.style.opacity = '0.5';
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
                            fb.innerHTML = data.feedback || (data.is_correct ? '✅ Chính xác!' :
                                '❌ Sai rồi!');
                        });

                        createStatisticsView(json.correct_count, json.total_questions);
                        showStatistics();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert("Có lỗi xảy ra. Vui lòng thử lại!");
                        show(idx);
                    });
            });
        }

        // Xử lý khi tải trang
        window.addEventListener('load', () => {
            initDragAndDrop();
            if (loading) {
                loading.classList.remove('active');
            }
        });

        // Xử lý khi người dùng rời khỏi trang
        window.addEventListener('beforeunload', (e) => {
            if (!isSubmitted) {
                e.preventDefault();
                e.returnValue = 'Bạn có chắc chắn muốn rời khỏi trang? Bài làm của bạn sẽ không được lưu.';
            }
        });

        // Xử lý phím tắt
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' || e.key === 'Enter') {
                if (idx < total - 1) {
                    if (!isSubmitted && !isCurrentQuestionAnswered()) {
                        const currentBlock = blocks[idx];
                        const qid = currentBlock.getAttribute('data-question-id');
                        showValidationMessage(qid);
                        return;
                    }
                    idx++;
                    show(idx);
                }
            } else if (e.key === 'ArrowLeft') {
                if (idx > 0) {
                    idx--;
                    show(idx);
                }
            }
        });

        // Xử lý auto-save cho fill_blank
        document.querySelectorAll('.fill-blank-input').forEach(input => {
            input.addEventListener('input', () => {
                console.log('Auto-saving:', input.value);
            });
        });

        // Xử lý click vào progress bar để chuyển câu
        if (progressBar) {
            progressBar.addEventListener('click', (e) => {
                if (isSubmitted) {
                    const rect = progressBar.getBoundingClientRect();
                    const clickX = e.clientX - rect.left;
                    const progressWidth = rect.width;
                    const targetIndex = Math.floor((clickX / progressWidth) * total);

                    if (targetIndex >= 0 && targetIndex < total) {
                        idx = targetIndex;
                        show(idx);
                    }
                }
            });
        }
    })();
</script>

</html>
