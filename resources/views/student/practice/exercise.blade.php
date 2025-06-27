<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Trắc nghiệm</title>
    <style>
        body {
            background: #fff;
            color: #333;
            font-family: sans-serif;
            padding: 2rem;
        }

        /* XÓA phần .question-block + .question-block */
        .question-block {
            display: none;
            max-width: 600px;
            margin: 0 auto;
        }

        .question-block.active {
            display: block;
        }

        .answers {
            list-style: none;
            padding: 0;
            margin-bottom: 1.5rem;
        }

        .answers li {
            margin-bottom: 0.75rem;
        }

        .btn {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            margin: 0 0.5rem;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn:disabled {
            background: #aaa;
            cursor: default;
        }

        .btn-group {
            text-align: center;
            margin-top: 1.5rem;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

    @php $total = $questions->count(); @endphp

    @foreach ($questions as $i => $question)
        <div class="question-block" data-index="{{ $i }}">
            <h4>Câu {{ $i + 1 }} / {{ $total }}</h4>
            <p>{{ $question->question_text }}</p>
            <ul class="answers">
                @foreach ($question->answers as $j => $ans)
                    <li>
                        <label>
                            <input type="radio" name="answer_{{ $question->questions_id }}"
                                value="{{ $ans->answers_id }}">
                            {{ chr(65 + $j) }}. {{ $ans->answer_text }}
                        </label>
                    </li>
                @endforeach
            </ul>
            <div class="btn-group">
                <button class="btn btn-prev" @if ($i === 0) disabled @endif>Previous</button>
                @if ($i < $total - 1)
                    <button class="btn btn-next">Next</button>
                @else
                    <button class="btn" id="btn-submit">Submit</button>
                @endif
            </div>
        </div>
    @endforeach

    <script>
        (function() {
            const blocks = document.querySelectorAll('.question-block');
            let idx = 0;
            const total = blocks.length;

            function show(i) {
                blocks.forEach((b, j) =>
                    b.classList.toggle('active', j === i)
                );
            }
            show(0);

            document.querySelectorAll('.btn-next').forEach(btn =>
                btn.addEventListener('click', () => {
                    if (idx < total - 1) idx++;
                    show(idx);
                })
            );
            document.querySelectorAll('.btn-prev').forEach(btn =>
                btn.addEventListener('click', () => {
                    if (idx > 0) idx--;
                    show(idx);
                })
            );

            document.getElementById('btn-submit').addEventListener('click', () => {
                const answers = {};
                blocks.forEach(b => {
                    const radio = b.querySelector('input[type=radio]:checked');
                    if (radio) {
                        const qid = radio.name.split('_')[1];
                        answers[qid] = radio.value;
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
                            answers
                        })
                    })
                    .then(r => r.json()).then(json => {
                        if (json.success && json.redirect) {
                            window.location = json.redirect;
                        } else {
                            alert('Có lỗi khi nộp bài');
                        }
                    });
            });
        })();
    </script>

</body>

</html>
