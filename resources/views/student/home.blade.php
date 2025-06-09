@extends('layouts.student')
@section('title', 'Thông tin sinh viên')
@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
  <div class="card shadow p-4 w-100" style="max-width: 700px;">
    <div class="d-flex align-items-center mb-4">
      <img src="{{ asset('images/' . $student->avatar) }}"
           class="rounded-circle border border-secondary me-3" width="96" height="96" style="object-fit: cover;">
      <div>
        <h2 class="h4 text-primary mb-1">{{ $student->fullname }}</h2>
        <p class="mb-0 text-muted small">Mã sinh viên: {{ $student->student_id }}</p>
      </div>
    </div>
    <div class="row g-3 text-dark small">
      <div class="col-md-6"><strong>Email:</strong> {{ $student->email}}</div>
      <div class="col-md-6"><strong>Giới tính:</strong> {{ $student->gender->getLabel()}}</div>
      <div class="col-md-6"><strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</div>
      <div class="col-md-6">
        <strong>Trạng thái:</strong>
        @if ($student->is_status)
          <span class="text-success fw-semibold">Đang hoạt động</span>
        @else
          <span class="text-danger fw-semibold">Bị khóa</span>
        @endif
      </div>
      <div class="col-md-6"><strong>Ngày tạo:</strong> {{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y H:i') }}</div>
      <div class="col-md-6"><strong>Cập nhật:</strong> {{ \Carbon\Carbon::parse($student->updated_at)->format('d/m/Y H:i') }}</div>
    </div>
  </div>
</div>
@endsection
