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
    @include('partials.alerts')
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

                        <!-- Form 1: Trắc nghiệm -->
                        <div id="singleChoiceFormContainer" class="question-form-container" style="display: none;">
                            <form id="singleChoiceForm" method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.questions.store') }}">
                                @csrf
                                <input type="hidden" name="lesson_part_id" class="lesson-part-input">
                                <input type="hidden" name="question_type" value="single_choice">

                                <h5 class="mb-3">
                                    <i class="fas fa-list-ul me-2"></i>Câu Hỏi Trắc Nghiệm
                                </h5>
                                <div class="mb-3">
                                    <label class="form-label">Thứ tự câu hỏi (order_index):</label>
                                    <input type="number" class="form-control" name="order_index"
                                        placeholder="Nhập thứ tự (ví dụ: 1)" min="1" value="1">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Câu hỏi:</label>
                                    <textarea class="form-control" name="question_text" rows="3" placeholder="Nhập câu hỏi..."></textarea>
                                </div>
                                <label class="form-label">Đáp án (chọn đáp án đúng):</label>
                                <div id="multipleChoiceAnswers">
                                    @php $labels = ['A', 'B', 'C', 'D']; @endphp
                                    @foreach ($labels as $index => $label)
                                        <div class="answer-option mb-2">
                                            <label class="d-flex align-items-center w-100">
                                                <input type="radio" name="correct_answer" value="{{ $index }}"
                                                    class="me-2">
                                                <span class="me-2 fw-bold">{{ $label }}.</span>
                                                <input type="text" class="form-control" name="answers[]"
                                                    placeholder="Nhập đáp án {{ $label }}">
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
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

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-save me-1"></i>Lưu Câu Hỏi
                                    </button>
                                    <button type="button" class="btn btn-secondary cancel-btn">
                                        <i class="fas fa-times me-1"></i>Hủy
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Form 2: Nối từ -->
                        <div id="matchingFormContainer" class="question-form-container" style="display: none;">
                            <form id="matchingForm" method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.questions.store') }}">
                                @csrf
                                <input type="hidden" name="lesson_part_id" class="lesson-part-input">
                                <input type="hidden" name="question_type" value="matching">

                                <h5 class="mb-3">
                                    <i class="fas fa-link me-2"></i>Câu Hỏi Nối Từ
                                </h5>
                                <div class="mb-3">
                                    <label class="form-label">Thứ tự câu hỏi (order_index):</label>
                                    <input type="number" class="form-control" name="order_index"
                                        placeholder="Nhập thứ tự (ví dụ: 1)" min="1" value="1">
                                </div>
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
                                            <input type="file" class="form-control" name="images[]" accept="image/*">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger remove-pair">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary mb-3" id="addMatchingPair">
                                    <i class="fas fa-plus me-1"></i>Thêm cặp
                                </button>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-save me-1"></i>Lưu Câu Hỏi
                                    </button>
                                    <button type="button" class="btn btn-secondary cancel-btn">
                                        <i class="fas fa-times me-1"></i>Hủy
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Form 3: Phân loại từ -->
                        <div id="classificationFormContainer" class="question-form-container" style="display: none;">
                            <form id="classificationForm" method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.questions.store') }}">
                                @csrf
                                <input type="hidden" name="lesson_part_id" class="lesson-part-input">
                                <input type="hidden" name="question_type" value="classification">

                                <h5 class="mb-3">
                                    <i class="fas fa-tags me-2"></i>Câu Hỏi Phân Loại Từ
                                </h5>
                                <div class="mb-3">
                                    <label class="form-label">Thứ tự câu hỏi (order_index):</label>
                                    <input type="number" class="form-control" name="order_index"
                                        placeholder="Nhập thứ tự (ví dụ: 1)" min="1" value="1">
                                </div>
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

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-save me-1"></i>Lưu Câu Hỏi
                                    </button>
                                    <button type="button" class="btn btn-secondary cancel-btn">
                                        <i class="fas fa-times me-1"></i>Hủy
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Form 4: Điền chỗ trống -->
                        <div id="fillBlankFormContainer" class="question-form-container" style="display: none;">
                            <form id="fillBlankForm" method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.questions.store') }}">
                                @csrf
                                <input type="hidden" name="lesson_part_id" class="lesson-part-input">
                                <input type="hidden" name="question_type" value="fill_blank">

                                <h5 class="mb-3">
                                    <i class="fas fa-edit me-2"></i>Câu Hỏi Điền Chỗ Trống
                                </h5>
                                <div class="mb-3">
                                    <label class="form-label">Thứ tự câu hỏi (order_index):</label>
                                    <input type="number" class="form-control" name="order_index"
                                        placeholder="Nhập thứ tự (ví dụ: 1)" min="1" value="1">
                                </div>
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
                                    <label class="form-label">Gợi ý khi sai:</label>
                                    <input type="text" class="form-control" name="wrong_feedback"
                                        placeholder="Ví dụ: Bạn cần ôn lại phần này.">
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-save me-1"></i>Lưu Câu Hỏi
                                    </button>
                                    <button type="button" class="btn btn-secondary cancel-btn">
                                        <i class="fas fa-times me-1"></i>Hủy
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Form 5: Sắp xếp câu -->
                        <div id="arrangementFormContainer" class="question-form-container" style="display: none;">
                            <form id="arrangementForm" method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.questions.store') }}">
                                @csrf
                                <input type="hidden" name="lesson_part_id" class="lesson-part-input">
                                <input type="hidden" name="question_type" value="arrangement">

                                <h5 class="mb-3">
                                    <i class="fas fa-sort me-2"></i>Câu Hỏi Sắp Xếp Câu
                                </h5>
                                <div class="mb-3">
                                    <label class="form-label">Thứ tự câu hỏi (order_index):</label>
                                    <input type="number" class="form-control" name="order_index"
                                        placeholder="Nhập thứ tự (ví dụ: 1)" min="1" value="1">
                                </div>
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

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-save me-1"></i>Lưu Câu Hỏi
                                    </button>
                                    <button type="button" class="btn btn-secondary cancel-btn">
                                        <i class="fas fa-times me-1"></i>Hủy
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Form 6: Nhìn ảnh ghép từ -->
                        <div id="imageWordFormContainer" class="question-form-container" style="display: none;">
                            <form id="imageWordForm" method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.questions.store') }}">
                                @csrf
                                <input type="hidden" name="lesson_part_id" class="lesson-part-input">
                                <input type="hidden" name="question_type" value="image_word">

                                <h5 class="mb-3">
                                    <i class="fas fa-image me-2"></i>Câu Hỏi Nhìn Ảnh Ghép Từ
                                </h5>
                                <div class="mb-3">
                                    <label class="form-label">Thứ tự câu hỏi (order_index):</label>
                                    <input type="number" class="form-control" name="order_index"
                                        placeholder="Nhập thứ tự (ví dụ: 1)" min="1" value="1">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh:</label>
                                    <div class="image-upload-area" data-form="imageWord">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">Click để chọn ảnh</p>
                                        <input type="file" class="form-control" name="media_url" accept="image/*"
                                            style="display: none;">
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

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-save me-1"></i>Lưu Câu Hỏi
                                    </button>
                                    <button type="button" class="btn btn-secondary cancel-btn">
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
        let selectedLessonPartId = '';
        var count_create_matching_pair = 1;
        $(document).ready(function() {
            // Xử lý chọn level
            $('#levelSelect').on('change', function() {
                const level = $(this).val();
                $('#lessonSelect').html('<option value="">-- Đang tải bài học... --</option>');
                $('#questionTypeSection').hide();
                hideAllForms();

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
                    selectedLessonPartId = lessonPartId;
                    $('#questionTypeSection').show();
                    $('.lesson-part-input').val(lessonPartId);
                } else {
                    $('#questionTypeSection').hide();
                    hideAllForms();
                }
            });

            // Xử lý chọn dạng câu hỏi
            $('.question-type-card').on('click', function() {
                $('.question-type-card').removeClass('active');
                $(this).addClass('active');

                selectedQuestionType = $(this).data('type');
                showQuestionForm(selectedQuestionType);
            });

            // Xử lý upload ảnh
            $('.image-upload-area').on('click', function() {
                $(this).find('input[type="file"]').click();
            });
            // Xử lý thêm cặp nối
            $('#addMatchingPair').on('click', function() {
                if(count_create_matching_pair < 5)
                {
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
                        count_create_matching_pair++;
                }
                else
                {
                    alert('Bạn chỉ được thêm tối đa 5 cặp');
                }
                console.log(count_create_matching_pair);
                
            });

            // Xử lý xóa cặp nối
            $(document).on('click', '.remove-pair', function() {
                if ($('#matchingPairs .matching-pair').length > 1) {
                    $(this).closest('.matching-pair').remove();
                }
                count_create_matching_pair--;
                console.log('zzz');
                console.log(count_create_matching_pair);
            });

            // Tự động tạo từ xáo trộn cho sắp xếp câu
            $(document).on('input', 'input[name="correct_sentence"]', function() {
                const sentence = $(this).val();
                if (sentence) {
                    const words = sentence.split(' ').filter(word => word.length > 0);
                    const scrambledHtml = words.map(word =>
                        `<span class="badge bg-secondary">${word}</span>`
                    ).join(' ');
                    $('#scrambledWords').html(scrambledHtml);
                }
            });

            // Tự động tạo ô chữ cái cho ghép từ
            $(document).on('input', '#correctWordInput', function() {
                const word = $(this).val().toUpperCase();
                if (word) {
                    const lettersHtml = word.split('').map(letter =>
                        `<div class="letter-box">${letter}</div>`
                    ).join('');
                    $('#letterBoxes').html(lettersHtml);
                }
            });

            // Xử lý nút hủy
            $('.cancel-btn').on('click', function() {
                hideAllForms();
                $('.question-type-card').removeClass('active');
                selectedQuestionType = '';
            });

            // Xử lý radio button cho trắc nghiệm
            $(document).on('change', 'input[name="correct_answer"]', function() {
                $('.answer-option').removeClass('correct-answer');
                $(this).closest('.answer-option').addClass('correct-answer');
            });

            // Xử lý preview ảnh
            $(document).on('change', 'input[type="file"]', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    const $container = $(this).closest('.image-upload-area, .matching-pair');

                    reader.onload = function(e) {
                        const preview = `
                            <div class="preview mt-2">
                                <img src="${e.target.result}" alt="Preview" 
                                     style="max-width: 200px; max-height: 200px; border-radius: 5px;">
                            </div>
                        `;
                        $container.find('.preview').remove();
                        $container.append(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Validation trước khi submit
            $(document).on('submit', 'form', function(e) {
                const formType = $(this).attr('id');
                let isValid = true;

                switch (formType) {
                    case 'singleChoiceForm':
                        isValid = validateSingleChoice();
                        break;
                    case 'matchingForm':
                        isValid = validateMatching();
                        break;
                    case 'classificationForm':
                        isValid = validateClassification();
                        break;
                    case 'fillBlankForm':
                        isValid = validateFillBlank();
                        break;
                    case 'arrangementForm':
                        isValid = validateArrangement();
                        break;
                    case 'imageWordForm':
                        isValid = validateImageWord();
                        break;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });

        function showQuestionForm(type) {
            hideAllForms();

            const formMap = {
                'single_choice': '#singleChoiceFormContainer',
                'matching': '#matchingFormContainer',
                'classification': '#classificationFormContainer',
                'fill_blank': '#fillBlankFormContainer',
                'arrangement': '#arrangementFormContainer',
                'image_word': '#imageWordFormContainer'
            };

            const targetForm = formMap[type];
            if (targetForm) {
                $(targetForm).show();
                console.log('Showing form:', targetForm); // Debug log
            } else {
                console.error('Form type not found:', type);
            }
        }

        function hideAllForms() {
            $('.question-form-container').hide();
        }

        // Validation functions
        function validateSingleChoice() {
            const question = $('textarea[name="question_text"]').val();
            const answers = $('input[name="answers[]"]').map(function() {
                return $(this).val().trim();
            }).get();
            const correctAnswer = $('input[name="correct_answer"]:checked').val();

            if (!question.trim()) {
                alert('Vui lòng nhập câu hỏi');
                return false;
            }

            // Kiểm tra tất cả đáp án đã được nhập
            if (answers.some(answer => !answer)) {
                alert('Vui lòng nhập đầy đủ 4 đáp án');
                return false;
            }

            // Kiểm tra đáp án có trùng lặp không
            const uniqueAnswers = [...new Set(answers)];
            if (uniqueAnswers.length !== answers.length) {
                alert('Các đáp án không được trùng lặp. Vui lòng nhập 4 đáp án khác nhau.');
                return false;
            }


            if (correctAnswer === undefined) {
                alert('Vui lòng chọn đáp án đúng cho câu hỏi');
                return false;
            }

            return true;
        }

        function validateMatching() {
            const words = $('#matchingForm input[name="words[]"]').map(function() {
                return $(this).val();
            }).get();
            const images = $('#matchingForm input[name="images[]"]').map(function() {
                return this.files[0];
            }).get();

            if (words.length < 2) {
                alert('Cần ít nhất 2 cặp nối');
                return false;
            }

            if (words.length >= 5) {
                alert('Chỉ được thêm tối đa 5 cặp');
                return false;
            }

            if (words.some(word => !word.trim())) {
                alert('Vui lòng nhập đầy đủ từ vựng');
                return false;
            }

            if (images.some(image => !image)) {
                alert('Vui lòng chọn đầy đủ hình ảnh');
                return false;
            }

            return true;
        }

        function validateClassification() {
            const nouns = $('#classificationForm textarea[name="nouns"]').val();
            const verbs = $('#classificationForm textarea[name="verbs"]').val();
            const adjectives = $('#classificationForm textarea[name="adjectives"]').val();

            if (!nouns.trim() && !verbs.trim() && !adjectives.trim()) {
                alert('Vui lòng nhập ít nhất một loại từ');
                return false;
            }

            return true;
        }

        function validateFillBlank() {
            const question = $('#fillBlankForm textarea[name="question_text"]').val();
            const correctWord = $('#fillBlankForm input[name="correct_word"]').val();

            if (!question.trim()) {
                alert('Vui lòng nhập câu hỏi');
                return false;
            }

            if (!question.includes('___')) {
                alert('Câu hỏi phải có ít nhất một chỗ trống (___)');
                return false;
            }

            if (!correctWord.trim()) {
                alert('Vui lòng nhập từ đúng');
                return false;
            }

            return true;
        }

        function validateArrangement() {
            const sentence = $('#arrangementForm input[name="correct_sentence"]').val();

            if (!sentence.trim()) {
                alert('Vui lòng nhập câu đúng');
                return false;
            }

            if (sentence.split(' ').filter(word => word.length > 0).length < 2) {
                alert('Câu phải có ít nhất 2 từ');
                return false;
            }

            return true;
        }

        function validateImageWord() {
            const image = $('#imageWordForm input[name="media_url"]')[0].files[0];
            const correctWord = $('#imageWordForm input[name="correct_word"]').val();

            if (!image) {
                alert('Vui lòng chọn hình ảnh');
                return false;
            }

            if (!correctWord.trim()) {
                alert('Vui lòng nhập từ đúng');
                return false;
            }

            if (correctWord.length < 2) {
                alert('Từ phải có ít nhất 2 ký tự');
                return false;
            }

            return true;
        }

        // Utility functions
        function resetAllForms() {
            $('form').each(function() {
                this.reset();
            });
            hideAllForms();
            $('.question-type-card').removeClass('active');
            selectedQuestionType = '';
            $('.preview').remove();
            $('#scrambledWords').html('');
            $('#letterBoxes').html('');
        }

        // Debug function
        function debugFormVisibility() {
            $('.question-form-container').each(function() {
                console.log($(this).attr('id'), $(this).is(':visible'));
            });
        }
    </script>
@endsection
