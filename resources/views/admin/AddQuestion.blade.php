@extends('layouts.admin')
@section('styles')
    <style>
        .question-form-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid #dee2e6;
        }

        .question-type-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .question-type-card:hover {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
        }

        .question-type-card.active {
            border-color: #007bff;
            background: #e7f3ff;
        }

        .answer-option {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
        }

        .correct-answer {
            border-color: #28a745;
            background: #d4edda;
        }

        .image-upload-area {
            border: 2px dashed #ccc;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-upload-area:hover {
            border-color: #007bff;
            background: #f8f9fa;
        }

        .matching-pair {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .word-letters {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .letter-box {
            width: 40px;
            height: 40px;
            border: 2px solid #dee2e6;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus-circle me-2"></i>Thêm Câu Hỏi Mới
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Chọn Level và Lesson -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="levelSelect" class="form-label">
                                    <i class="fas fa-layer-group me-1"></i>Trình độ
                                </label>
                                <select id="levelSelect" class="form-control">
                                    <option value="">-- Chọn trình độ --</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level }}">{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="lessonSelect" class="form-label">
                                    <i class="fas fa-book me-1"></i>Bài học
                                </label>
                                <select id="lessonSelect" class="form-control">
                                    <option value="">-- Chọn bài học --</option>
                                </select>
                            </div>
                        </div>

                        <!-- Chọn dạng câu hỏi -->
                        <div id="questionTypeSection" style="display: none;">
                            <h5 class="mb-3">
                                <i class="fas fa-question-circle me-2"></i>Chọn Dạng Câu Hỏi
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="question-type-card" data-type="single_choice">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-list-ul text-primary me-3 fa-2x"></i>
                                            <div>
                                                <h6 class="mb-1">Trắc Nghiệm</h6>
                                                <small class="text-muted">4 đáp án, 1 đáp án đúng</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="question-type-card" data-type="matching">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-link text-success me-3 fa-2x"></i>
                                            <div>
                                                <h6 class="mb-1">Nối Từ</h6>
                                                <small class="text-muted">Nối từ với hình ảnh/nghĩa</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="question-type-card" data-type="classification">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-tags text-warning me-3 fa-2x"></i>
                                            <div>
                                                <h6 class="mb-1">Phân Loại Từ</h6>
                                                <small class="text-muted">Danh từ, động từ, tính từ</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="question-type-card" data-type="fill_blank">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-edit text-info me-3 fa-2x"></i>
                                            <div>
                                                <h6 class="mb-1">Điền Chỗ Trống</h6>
                                                <small class="text-muted">Điền từ vào chỗ trống</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="question-type-card" data-type="arrangement">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-sort text-purple me-3 fa-2x"></i>
                                            <div>
                                                <h6 class="mb-1">Sắp Xếp Câu</h6>
                                                <small class="text-muted">Sắp xếp thành câu đúng</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="question-type-card" data-type="image_word">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-image text-danger me-3 fa-2x"></i>
                                            <div>
                                                <h6 class="mb-1">Nhìn Ảnh Ghép Từ</h6>
                                                <small class="text-muted">Xem ảnh sắp xếp thành từ</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- các Form nhập câu hỏi theo dạng-->
                        <div id="questionFormContainer" class="question-form-container" style="display: none;">

                            <form id="questionForm">
                                <input type="hidden" id="selectedLessonPart" name="lesson_part_id">
                                <input type="hidden" id="selectedQuestionType" name="question_type">

                                <!-- Form cho Trắc nghiệm -->
                                <div id="singleChoiceForm" class="question-form" style="display: none;">
                                    <h5 class="mb-3">
                                        <i class="fas fa-list-ul me-2"></i>Câu Hỏi Trắc Nghiệm
                                    </h5>

                                    {{-- Câu hỏi --}}
                                    <div class="mb-3">
                                        <label class="form-label">Câu hỏi:</label>
                                        <input class="form-control" name="question_text"
                                            placeholder="Nhập câu hỏi..."></input>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Thứ tự câu hỏi (order_index):</label>
                                        <input type="number" class="form-control" name="order_index"
                                            placeholder="Nhập thứ tự (ví dụ: 1)" min="1">
                                    </div>
                                    <label class="form-label">Đáp án (chọn đáp án đúng):</label>
                                    <div id="multipleChoiceAnswers">
                                        @php $labels = ['A', 'B', 'C', 'D']; @endphp
                                        @foreach ($labels as $index => $label)
                                            <div class="answer-option mb-2">
                                                <label class="d-flex align-items-center w-100">
                                                    <input type="radio" name="correct_answer"
                                                        value="{{ $index }}" class="me-2">
                                                    <span class="me-2 fw-bold">{{ $label }}.</span>
                                                    <input type="text" class="form-control" name="answers[]"
                                                        placeholder="Nhập đáp án {{ $label }}">
                                                </label>
                                            </div>
                                        @endforeach
                                        <div class="mb-3">
                                            <label class="form-label">Phản hồi khi đúng:</label>
                                            <input type="text" class="form-control" name="correct_feedback"
                                                placeholder="Ví dụ: Chính xác!">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Phản hồi khi sai:</label>
                                            <input type="text" class="form-control" name="wrong_feedback"
                                                placeholder="Ví dụ: Bạn cần ôn lại phần này.">
                                        </div>
                                    </div>
                                </div>

                                <!-- Form cho Nối từ -->
                                <div id="matchingForm" class="question-form" style="display: none;">
                                    <h5 class="mb-3">
                                        <i class="fas fa-link me-2"></i>Câu Hỏi Nối Từ
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label">Hướng dẫn:</label>
                                        <textarea class="form-control" name="question_text" rows="2"
                                            placeholder="Ví dụ: Nối từ với hình ảnh tương ứng..."></textarea>
                                    </div>

                                    <label class="form-label">Các cặp nối:</label>
                                    <div id="matchingPairs">
                                        <div class="matching-pair">
                                            <div class="flex-fill">
                                                <input type="text" class="form-control" name="words[]"
                                                    placeholder="Từ vựng">
                                            </div>
                                            <i class="fas fa-arrows-alt-h text-muted"></i>
                                            <div class="flex-fill">
                                                <input type="file" class="form-control" name="images[]"
                                                    accept="image/*">
                                            </div>
                                            <button type="button" class="btn btn-sm btn-danger remove-pair">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" id="addMatchingPair">
                                        <i class="fas fa-plus me-1"></i>Thêm cặp
                                    </button>
                                </div>

                                <!-- Form cho Phân loại từ -->
                                <div id="classificationForm" class="question-form" style="display: none;">
                                    <h5 class="mb-3">
                                        <i class="fas fa-tags me-2"></i>Câu Hỏi Phân Loại Từ
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label">Câu hỏi:</label>
                                        <textarea class="form-control" name="question_text" rows="2"
                                            placeholder="Ví dụ: Phân loại các từ sau theo loại từ..."></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Danh từ (Nouns):</label>
                                            <textarea class="form-control" name="nouns" rows="4" placeholder="Nhập các danh từ, mỗi từ một dòng"></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Động từ (Verbs):</label>
                                            <textarea class="form-control" name="verbs" rows="4" placeholder="Nhập các động từ, mỗi từ một dòng"></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tính từ (Adjectives):</label>
                                            <textarea class="form-control" name="adjectives" rows="4" placeholder="Nhập các tính từ, mỗi từ một dòng"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form cho Điền chỗ trống -->
                                <div id="fillBlankForm" class="question-form" style="display: none;">
                                    <h5 class="mb-3">
                                        <i class="fas fa-edit me-2"></i>Câu Hỏi Điền Chỗ Trống
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label">Câu hỏi (dùng ___ để đánh dấu chỗ trống):</label>
                                        <textarea class="form-control" name="question_text" rows="3"
                                            placeholder="Ví dụ: I ___ to school every day. (go/goes/going)"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Từ đúng:</label>
                                        <input type="text" class="form-control" name="correct_word"
                                            placeholder="Nhập từ đúng">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Các lựa chọn khác (tùy chọn):</label>
                                        <input type="text" class="form-control" name="other_options"
                                            placeholder="Nhập các lựa chọn sai, cách nhau bởi dấu phẩy">
                                    </div>
                                </div>

                                <!-- Form cho Sắp xếp câu -->
                                <div id="arrangementForm" class="question-form" style="display: none;">
                                    <h5 class="mb-3">
                                        <i class="fas fa-sort me-2"></i>Câu Hỏi Sắp Xếp Câu
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label">Hướng dẫn:</label>
                                        <textarea class="form-control" name="question_text" rows="2"
                                            placeholder="Ví dụ: Sắp xếp các từ sau thành câu đúng..."></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Câu đúng:</label>
                                        <input type="text" class="form-control" name="correct_sentence"
                                            placeholder="Nhập câu đúng">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Các từ sẽ được xáo trộn:</label>
                                        <div id="scrambledWords" class="d-flex flex-wrap gap-2"></div>
                                    </div>
                                </div>

                                <!-- Form cho Nhìn ảnh ghép từ -->
                                <div id="imageWordForm" class="question-form" style="display: none;">
                                    <h5 class="mb-3">
                                        <i class="fas fa-image me-2"></i>Câu Hỏi Nhìn Ảnh Ghép Từ
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label">Hình ảnh:</label>
                                        <div class="image-upload-area">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">Click để chọn ảnh</p>
                                            <input type="file" class="form-control" name="media_url" accept="image/*"
                                                style="display: none;" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Từ đúng:</label>
                                        <input type="text" class="form-control" name="correct_word"
                                            placeholder="Nhập từ đúng" id="correctWordInput">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Các chữ cái sẽ được xáo trộn:</label>
                                        <div id="letterBoxes" class="word-letters"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Gợi ý (tùy chọn):</label>
                                        <input type="text" class="form-control" name="hint"
                                            placeholder="Nhập gợi ý cho câu hỏi">
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-save me-1"></i>Lưu Câu Hỏi
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="cancelBtn">
                                        <i class="fas fa-times me-1"></i>Hủy
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let selectedQuestionType = '';

        $(document).ready(function() {
            // Xử lý chọn level
            $('#levelSelect').on('change', function() {
                const level = $(this).val();
                $('#lessonSelect').html('<option value="">-- Đang tải bài học... --</option>');
                $('#questionTypeSection').hide();
                $('#questionFormContainer').hide();

                if (level) {
                    $.ajax({
                        url: `/admin/api/lessons/by-level/${level}`,
                        method: 'GET',
                        success: function(data) {
                            let options = '<option value="">-- Chọn bài học --</option>';
                            data.forEach(part => {
                                options +=
                                    `<option value="${part.lesson_part_id}">${part.content}</option>`;
                            });
                            $('#lessonSelect').html(options);
                        },
                        error: function() {
                            alert('Không thể tải bài học');
                        }
                    });
                }
            });

            // Xử lý chọn lesson
            $('#lessonSelect').on('change', function() {
                const lessonPartId = $(this).val();
                if (lessonPartId) {
                    $('#questionTypeSection').show();
                    $('#selectedLessonPart').val(lessonPartId);
                } else {
                    $('#questionTypeSection').hide();
                    $('#questionFormContainer').hide();
                }
            });

            // Xử lý chọn dạng câu hỏi
            $('.question-type-card').on('click', function() {
                $('.question-type-card').removeClass('active');
                $(this).addClass('active');

                selectedQuestionType = $(this).data('type');
                $('#selectedQuestionType').val(selectedQuestionType);

                showQuestionForm(selectedQuestionType);
            });

            // Xử lý upload ảnh
            $('.image-upload-area').on('click', function() {
                $(this).find('input[type="file"]').click();
            });

            // Xử lý thêm cặp nối
            $('#addMatchingPair').on('click', function() {
                const newPair = `
                    <div class="matching-pair">
                        <div class="flex-fill">
                            <input type="text" class="form-control" name="words[]" placeholder="Từ vựng">
                        </div>
                        <i class="fas fa-arrows-alt-h text-muted"></i>
                        <div class="flex-fill">
                            <input type="file" class="form-control" name="images[]" accept="image/*">
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-pair">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                $('#matchingPairs').append(newPair);
            });

            // Xử lý xóa cặp nối
            $(document).on('click', '.remove-pair', function() {
                if ($('#matchingPairs .matching-pair').length > 1) {
                    $(this).closest('.matching-pair').remove();
                }
            });

            // Tự động tạo từ xáo trộn cho sắp xếp câu
            $('input[name="correct_sentence"]').on('input', function() {
                const sentence = $(this).val();
                if (sentence) {
                    const words = sentence.split(' ').filter(word => word.length > 0);
                    const scrambledHtml = words.map(word =>
                        `<span class="badge bg-secondary">${word}</span>`
                    ).join('');
                    $('#scrambledWords').html(scrambledHtml);
                }
            });

            // Tự động tạo chữ cái cho ghép từ
            $('#correctWordInput').on('input', function() {
                const word = $(this).val().toUpperCase();
                if (word) {
                    const letters = word.split('');
                    const letterBoxesHtml = letters.map(letter =>
                        `<div class="letter-box">${letter}</div>`
                    ).join('');
                    $('#letterBoxes').html(letterBoxesHtml);
                }
            });

            // Xử lý submit form
            $('#questionForm').on('submit', function(e) {
                e.preventDefault();

                // Validate form
                if (!validateForm()) {
                    return;
                }

                // Tạo FormData để gửi file
                const formData = new FormData(this);

                // Xử lý dữ liệu theo từng loại câu hỏi
                processFormData(formData);

                // Gửi AJAX request
                $.ajax({
                    url: '/admin/questions/store',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('Câu hỏi đã được tạo thành công!');
                        resetForm();
                    },
                    error: function(xhr) {
                        alert('Có lỗi xảy ra: ' + xhr.responseText);
                    }
                });
            });

            // Xử lý hủy
            $('#cancelBtn').on('click', function() {
                resetForm();
            });
        });

        function showQuestionForm(type) {
            // Ẩn tất cả các form trước
            $('.question-form').hide();

            const formMap = {
                'single_choice': '#singleChoiceForm',
                'matching': '#matchingForm',
                'classification': '#classificationForm',
                'fill_blank': '#fillBlankForm',
                'arrangement': '#arrangementForm',
                'image_word': '#imageWordForm'
            };

            const targetForm = formMap[type];
            if (targetForm) {
                $(targetForm).show();
                $('#questionFormContainer').show();
            } else {
                alert('Loại câu hỏi không hợp lệ!');
            }
        }

        function processFormData(formData) {
            // Xử lý dữ liệu dựa trên loại câu hỏi
            switch (selectedQuestionType) {
                case 'single_choice':
                    // Dữ liệu đã được xử lý trong form
                    break;

                case 'matching':
                    // Kết hợp words và images thành JSON
                    const words = $('input[name="words[]"]').map(function() {
                        return $(this).val();
                    }).get();
                    const matchingData = {
                        words: words
                    };
                    formData.append('matching_data', JSON.stringify(matchingData));
                    break;

                case 'classification':
                    const nouns = $('textarea[name="nouns"]').val().split('\n').filter(w => w.trim());
                    const verbs = $('textarea[name="verbs"]').val().split('\n').filter(w => w.trim());
                    const adjectives = $('textarea[name="adjectives"]').val().split('\n').filter(w => w.trim());
                    const classificationData = {
                        nouns,
                        verbs,
                        adjectives
                    };
                    formData.append('classification_data', JSON.stringify(classificationData));
                    break;

                case 'fill_blank':
                    // Dữ liệu đã được xử lý trong form
                    break;

                case 'arrangement':
                    const correctSentence = $('input[name="correct_sentence"]').val();
                    const wordsArray = correctSentence.split(' ').filter(w => w.trim());
                    formData.append('arrangement_data', JSON.stringify({
                        words: wordsArray
                    }));
                    break;

                case 'image_word':
                    // Dữ liệu đã được xử lý trong form
                    break;
            }
        }

        function validateForm() {
            // Validate cơ bản
            if (!$('#selectedLessonPart').val()) {
                alert('Vui lòng chọn bài học');
                return false;
            }

            if (!selectedQuestionType) {
                alert('Vui lòng chọn dạng câu hỏi');
                return false;
            }

            // Validate theo từng loại
            switch (selectedQuestionType) {
                case 'single_choice':
                    if (!$('input[name="correct_answer"]:checked').length) {
                        alert('Vui lòng chọn đáp án đúng');
                        return false;
                    }
                    break;

                case 'matching':
                    if ($('input[name="words[]"]').filter(function() {
                            return $(this).val().trim() !== '';
                        }).length < 2) {
                        alert('Vui lòng nhập ít nhất 2 cặp từ');
                        return false;
                    }
                    break;

                case 'image_word':
                    if (!$('input[name="media_url"]')[0].files.length) {
                        alert('Vui lòng chọn hình ảnh');
                        return false;
                    }
                    break;
            }

            return true;
        }

        function resetForm() {
            $('#questionForm')[0].reset();
            $('.question-type-card').removeClass('active');
            $('#questionFormContainer').hide();
            $('#questionTypeSection').hide();
            selectedQuestionType = '';
        }
    </script>
@endsection
