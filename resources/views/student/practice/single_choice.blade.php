<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Làm bài trắc nghiệm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h4>📝 Câu hỏi:</h4>
        <p class="fw-bold">{{ $question->question_text }}</p>

        <form id="answerForm">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->questions_id }}">
            <input type="hidden" name="score_id" value="{{ $score->score_id }}">

            @foreach ($question->answers as $index => $answer)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer_id"
                           id="answer_{{ $answer->answers_id }}" value="{{ $answer->answers_id }}">
                    <label class="form-check-label" for="answer_{{ $answer->answers_id }}">
                        {{ chr(65 + $index) }}. {{ $answer->answer_text }}
                    </label>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary mt-3">Nộp</button>
        </form>

        <div id="feedback" class="mt-4 fw-bold"></div>
        <button id="nextQuestionBtn" class="btn btn-success mt-3 d-none">Tiếp tục</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        $('#answerForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('student.quiz.submit', $score->lesson_part_id) }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $('#feedback').html(res.correct ? '✅ Đúng!' : '❌ Sai! Đáp án: ' + res.correct_answer);
                    $('#nextQuestionBtn').removeClass('d-none');
                    $('input[name="answer_id"]').prop('disabled', true);
                    $('button[type="submit"]').hide();
                }
            });
        });

        $('#nextQuestionBtn').click(function () {
            window.location.href = '{{ route('student.quiz.start', $score->lesson_part_id) }}';
        });
    });
</script>
</body>
</html>
