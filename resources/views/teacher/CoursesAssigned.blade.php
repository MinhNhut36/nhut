@extends('layouts.teacher')
@section('title', 'Danh sách khóa học được giao')
@section('styles')
    .card-status {
      background-color: #d1fae5;
      color: #065f46;
      padding: 4px 12px;
      font-weight: 600;
      font-size: 0.9rem;
      border-radius: 9999px;
    }
    .custom-btn {
      background-color: #4f46e5;
      color: white;
      font-weight: 800;
      text-transform: uppercase;
      font-size: 1.1rem;
      transition: background-color 0.3s;
    }
    .custom-btn:hover {
      background-color: #4338ca;
    }
@endsection
@section('content')
<div class="container py-5">
  <h2 class="mb-4 fw-bold text-center">Danh sách khóa học được giao</h2>
  @if ($courses->isEmpty())
            <div class="alert alert-info" role="alert">
                Bạn hiện chưa được phân công cho khóa học nào.
            </div>
  @else
  <div class="row g-4">
    @foreach ($courses as $assigned)
      <div class="col-md-3 col-sm-6">
        <div class="card border rounded shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-dark small fw-bold">Ngày được giao</span>
              <span class="fw-semibold">{{ \Carbon\Carbon::parse($assigned->assigned_at)->format('d/m/Y') }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-dark small fw-bold">Vai trò</span>
              <span class="fw-semibold">{{ $assigned->role }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
              <span class="text-dark small fw-bold">Trạng thái</span>
              <span class="card-status">{{ $assigned->course->status }}</span>
            </div>
          </div>
          <a href="{{ route('teacher.studentlist', $assigned->course->course_id) }}" class="btn custom-btn w-100 py-2 d-flex align-items-center justify-content-center gap-2 border-0">
            <span>{{ $assigned->course->course_name }} </span>
            <i class="fas fa-chevron-right"></i>
          </a>
        </div>
      </div>
    @endforeach
  </div>
  @endif
</div>


@endsection