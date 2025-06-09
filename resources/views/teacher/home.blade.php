@extends('layouts.teacher')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
  <div class="card shadow p-4 w-100" style="max-width: 700px;">
    <div class="d-flex align-items-center mb-4">
      <div>
        <h2 class="h4 text-primary mb-1">{{ $teacher['fullname'] }}</h2>
      </div>
    </div>
    <div class="row g-3 text-dark small">
      <div class="col-md-6"><strong>Email:</strong> {{ $teacher['email'] }}</div>
      <div class="col-md-6"><strong>Giới tính:</strong> {{ $teacher['gender']->getLabel()}}</div>
      <div class="col-md-6"><strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($teacher['date_of_birth'])->format('d/m/Y') }}</div>
      <div class="col-md-6">
        <strong>Trạng thái:</strong>
        @if ($teacher['is_status'])
          <span class="text-success fw-semibold">Đang hoạt động</span>
        @else
          <span class="text-danger fw-semibold">Bị khóa</span>
        @endif
      </div>
      <div class="col-md-6"><strong>Ngày tạo:</strong> {{ \Carbon\Carbon::parse($teacher['created_at'])->format('d/m/Y H:i') }}</div>
      <div class="col-md-6"><strong>Cập nhật:</strong> {{ \Carbon\Carbon::parse($teacher['updated_at'])->format('d/m/Y H:i') }}</div>
    </div>
  </div>
</div>
@endsection
