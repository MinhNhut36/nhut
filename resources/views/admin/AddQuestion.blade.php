@extends('layouts.admin')
@section('styles')
    <style>
    </style>
@endsection
@section('content')
    <div class="row">
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
@endsection
@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#levelSelect').on('change', function() {
            const level = $(this).val();
            $('#lessonSelect').html('<option value="">-- Đang tải bài học... --</option>');

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
    </script>
@endsection
