@extends('layouts.admin')

@section('title', 'DANH SÁCH CÁC TRÌNH ĐỘ')

@section('styles')
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 12px;
            text-align: center;
        }

        .lesson-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .lesson-table thead {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
        }

        .lesson-table th {
            font-weight: 600;
            padding: 1.2rem;
            border: none;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
        }

        .lesson-table td {
            padding: 1.2rem;
            vertical-align: middle;
            border-bottom: 1px solid #e5e7eb;
        }

        .lesson-table tbody tr {
            transition: background-color 0.3s ease;
        }

        .lesson-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .lesson-table tbody tr:last-child td {
            border-bottom: none;
        }

        .level-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .lesson-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .lesson-description {
            color: #64748b;
            line-height: 1.5;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            margin: 0.25rem;
        }

        .btn-edit {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-add {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.3);
        }

        .btn-add:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            transform: translateY(-3px) scale(1.02);
            box-shadow:
                0 10px 30px rgba(139, 92, 246, 0.3),
                0 5px 15px rgba(139, 92, 246, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #64748b;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
            color: #cbd5e1;
        }

        .empty-state h4 {
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .header-actions {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1rem;
        }

        .header-actions .page-header {
            flex: 1;
            margin-bottom: 0;
        }

        /* Chevron Animation */
        .chevron-icon {
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .chevron-icon.rotated {
            transform: rotate(180deg);
        }

        /* Accordion Row Styles */
        .accordion-toggle {
            cursor: pointer;
        }

        .collapse-row {
            background-color: #f8fafc !important;
        }

        .collapse-row td {
            border-top: none !important;
            padding-top: 0 !important;
        }

        /* Modal Styles */
        #addLevelModal .modal-content {
            border: none !important;
            border-radius: 15px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
            background: white !important;
        }

        #addLevelModal .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white !important;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem !important;
            border: none !important;
        }

        #addLevelModal .modal-title {
            font-weight: 600 !important;
            font-size: 1.25rem !important;
            color: white !important;
            margin: 0 !important;
        }

        #addLevelModal .modal-body {
            padding: 2rem !important;
            background: white !important;
        }

        #addLevelModal .form-group {
            margin-bottom: 1.5rem !important;
        }

        #addLevelModal .form-label {
            font-weight: 600 !important;
            color: #374151 !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
        }

        #addLevelModal .form-control {
            border: 2px solid #e5e7eb !important;
            border-radius: 8px !important;
            padding: 0.75rem !important;
            font-size: 0.95rem !important;
            transition: border-color 0.3s ease, box-shadow 0.3s ease !important;
            width: 100% !important;
            background: white !important;
            color: #374151 !important;
        }

        #addLevelModal .form-control:focus {
            border-color: #8b5cf6 !important;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1) !important;
            outline: none !important;
        }

        #addLevelModal .btn-close {
            background: white !important;
            border-radius: 50% !important;
            width: 32px !important;
            height: 32px !important;
            opacity: 0.8 !important;
            border: none !important;
        }

        #addLevelModal .btn-close:hover {
            opacity: 1 !important;
        }

        #addLevelModal .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
            border: none !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            color: white !important;
            transition: all 0.3s ease !important;
        }

        #addLevelModal .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #2563eb) !important;
            color: white !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4) !important;
        }

        #addLevelModal .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        #addLevelModal .col-md-6 {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        /* Modal z-index */
        .modal {
            z-index: 9999 !important;
        }

        .modal-backdrop {
            z-index: 9998 !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .lesson-table {
                font-size: 0.875rem;
            }

            .lesson-table th,
            .lesson-table td {
                padding: 0.8rem;
            }

            .page-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }

            .header-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .header-actions .page-header {
                text-align: center;
            }

            .modal-body {
                padding: 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 80%;">
                <!-- Header with Add Button -->
                <div class="header-actions">
                    <div class="page-header">
                        <h1 class="mb-0">
                            <i class="fas fa-graduation-cap me-3"></i>
                            Danh sách các trình độ
                        </h1>
                    </div>
                    <div>
                        <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#lessonModal"
                            onclick="openLessonModal('create')">
                            <i class="fas fa-plus me-2"></i> Thêm trình độ
                        </button>
                    </div>
                </div>

                @include('partials.alerts')

                <!-- Lessons Table -->
                <div class="lesson-table">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">Thứ tự</th>
                                <th style="width: 5%">trình độ</th>
                                <th style="width: 10%">Tiêu đề</th>
                                <th style="width: 40%">Mô tả</th>
                                <th style="width: 10%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="lessonTableAccordion">
                            @forelse ($lessons as $lesson)
                                <tr class="accordion-toggle" data-lesson-order="{{ $lesson->order_index }}">
                                    <td>
                                        <span class="level-badge">
                                            <i class="fas fa-chevron-down me-2 text-primary chevron-icon"
                                                id="chevron-{{ $lesson->order_index }}"></i>
                                            {{ $lesson->order_index }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="level-badge">{{ $lesson->level }}</span>
                                    </td>
                                    <td>
                                        <div class="lesson-title">
                                            {{ $lesson->title }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="lesson-description">
                                            {{ Str::limit(strip_tags($lesson->description), 100) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-edit" title="Chỉnh sửa" data-bs-toggle="modal"
                                                data-bs-target="#lessonModal" data-level="{{ $lesson->level }}"
                                                data-title="{{ $lesson->title }}"
                                                data-description="{{ $lesson->description }}"
                                                data-order="{{ $lesson->order_index }}"
                                                onclick="event.stopPropagation(); openLessonModal('edit', this)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Dòng con: danh sách lessonParts --}}
                                <tr class="collapse-row" id="parts-{{ $lesson->order_index }}" style="display: none;">
                                    <td colspan="5" style="padding-left: 3rem;">
                                        @if ($lesson->lessonParts->count())
                                            <ul class="list-group">
                                                @foreach ($lesson->lessonParts as $part)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>{{ $part->part_type }}</strong>
                                                            <small class="text-muted ms-2">({{ $part->content }})</small>
                                                        </div>
                                                        <div class="">
                                                            <span class="badge bg-secondary">Bài:
                                                                {{ $part->order_index }}</span>
                                                            <a href="#" class="btn-action btn-edit-part"
                                                                title="Chỉnh sửa" data-bs-toggle="modal"
                                                                data-bs-target="#editLessonPartModal"
                                                                data-id="{{ $part->lesson_part_id }}"
                                                                data-level="{{ $part->level }}"
                                                                data-type="{{ $part->part_type }}"
                                                                data-content="{{ $part->content }}"
                                                                data-order="{{ $part->order_index }}"
                                                                onclick="event.stopPropagation();">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-muted">Không có phần nào cho trình độ này.</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fas fa-book-open"></i>
                                            <h4>Chưa có bài học nào</h4>
                                            <p class="mb-0">Danh sách trống. Hãy thêm bài học mới.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Lesson Modal (Create & Edit) -->
    <div class="modal fade" id="lessonModal" tabindex="-1" aria-labelledby="lessonModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="lessonForm" method="POST" action="{{ route('admin.lesson.create') }}">
                @csrf
                <input type="hidden" name="_method" id="lessonFormMethod" value="POST">
                <input type="hidden" name="_form_name" value="lesson_form">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lessonModalTitle"><i class="fas fa-plus-circle me-2"></i> Thêm trình độ
                            mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="lessonLevel">Trình độ</label>
                                <input type="text" class="form-control @error('level') is-invalid @enderror"
                                    id="lessonLevel" name="level" value="{{ old('level') }}" required>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="lessonTitle">Tiêu đề</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="lessonTitle" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="lessonDescription">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="lessonDescription" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 col-md-6">
                            <label for="lessonOrder">Thứ tự hiển thị</label>
                            <input type="number" class="form-control @error('order_index') is-invalid @enderror"
                                id="lessonOrder" name="order_index" min="1" value="{{ old('order_index', 1) }}" required>
                            @error('order_index')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i> Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Lesson Part Modal -->
    <div class="modal fade" id="editLessonPartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editLessonPartForm" method="POST" action="#">
                @csrf
                @method('PUT')
                <input type="hidden" name="_form_name" value="edit_lesson_part">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Chỉnh sửa Lesson Part</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="lesson_part_id" id="editPartId">
                        <input type="hidden" name="level" id="editPartLevel">

                        <div class="mb-3">
                            <label>Loại</label>
                            <input type="text" class="form-control @error('part_type') is-invalid @enderror"
                                id="editPartType" name="part_type" value="{{ old('part_type') }}" required>
                            @error('part_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Nội dung</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="editPartContent" name="content">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label>Thứ tự</label>
                            <input type="number" class="form-control @error('order_index') is-invalid @enderror"
                                id="editPartOrder" name="order_index" value="{{ old('order_index') }}" required>
                            @error('order_index')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <!-- CKEditor Script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        let lessonEditor = null;

        // Initialize CKEditor
        function initializeCKEditor() {
            ClassicEditor
                .create(document.querySelector('#lessonDescription'))
                .then(editor => {
                    lessonEditor = editor;
                })
                .catch(error => console.error('Error initializing CKEditor:', error));
        }

        // Open lesson modal for create or edit
        function openLessonModal(mode, button = null) {
            const form = document.getElementById('lessonForm');
            const methodInput = document.getElementById('lessonFormMethod');
            const modalTitle = document.getElementById('lessonModalTitle');
            const levelInput = document.getElementById('lessonLevel');
            const titleInput = document.getElementById('lessonTitle');
            const orderInput = document.getElementById('lessonOrder');

            // Clear previous validation errors
            clearValidationErrors();

            if (mode === 'create') {
                modalTitle.innerHTML = `<i class="fas fa-plus-circle me-2"></i> Thêm trình độ mới`;
                form.action = "{{ route('admin.lesson.create') }}";
                methodInput.value = 'POST';

                // Reset form
                levelInput.value = '';
                titleInput.value = '';
                orderInput.value = 1;
                if (lessonEditor) lessonEditor.setData('');
            }

            if (mode === 'edit' && button) {
                const level = button.getAttribute('data-level');
                const title = button.getAttribute('data-title');
                const description = button.getAttribute('data-description');
                const order = button.getAttribute('data-order');

                modalTitle.innerHTML = `<i class="fas fa-edit me-2"></i> Chỉnh sửa trình độ`;
                form.action = "{{ route('admin.lesson.EditLesson', ['level' => '__level__']) }}".replace('__level__',
                    encodeURIComponent(level));
                methodInput.value = 'PUT';

                // Fill form with data
                levelInput.value = level;
                titleInput.value = title;
                orderInput.value = order;
                if (lessonEditor) lessonEditor.setData(description || '');
            }
        }

        // Clear validation error classes and messages
        function clearValidationErrors() {
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            document.querySelectorAll('.alert-danger').forEach(el => el.remove());
        }

        // Toggle accordion
        function toggleAccordion(orderIndex) {
            const partsRow = document.getElementById('parts-' + orderIndex);
            const chevronIcon = document.getElementById('chevron-' + orderIndex);
            if (!partsRow || !chevronIcon) return;

            const isHidden = window.getComputedStyle(partsRow).display === 'none';
            partsRow.style.display = isHidden ? 'table-row' : 'none';
            chevronIcon.classList.toggle('rotated', isHidden);
        }

        // Document ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor
            initializeCKEditor();

            // Initialize tooltips
            document.querySelectorAll('[title]').forEach(el => {
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    new bootstrap.Tooltip(el);
                }
            });

            // Setup accordion toggles
            document.querySelectorAll('.accordion-toggle').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('.action-buttons')) {
                        toggleAccordion(this.getAttribute('data-lesson-order'));
                    }
                });
                row.style.cursor = 'pointer';
            });

            // Setup lesson form submission
            const lessonForm = document.getElementById('lessonForm');
            if (lessonForm) {
                lessonForm.addEventListener('submit', function() {
                    if (lessonEditor) {
                        document.getElementById('lessonDescription').value = lessonEditor.getData();
                    }
                });
            }

            // Setup edit lesson part modal
            const editPartModal = document.getElementById('editLessonPartModal');
            editPartModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const id = button.getAttribute('data-id');
                const level = button.getAttribute('data-level');
                const type = button.getAttribute('data-type');
                const content = button.getAttribute('data-content');
                const order = button.getAttribute('data-order');

                // Update form action
                const form = document.getElementById('editLessonPartForm');
                form.action = "{{ route('admin.lesson.EditLessonPart', ['lesson_part_id' => '__id__']) }}"
                    .replace('__id__', id);

                // Fill form data
                document.getElementById('editPartId').value = id;
                document.getElementById('editPartLevel').value = level;
                document.getElementById('editPartType').value = type;
                document.getElementById('editPartContent').value = content;
                document.getElementById('editPartOrder').value = order;
            });
        });

        // Show modals on validation errors
        @if ($errors->any())
            @if (old('_form_name') === 'lesson_form')
                document.addEventListener('DOMContentLoaded', function() {
                    // Restore form mode first
                    const mode = '{{ old('_method') === 'PUT' ? 'edit' : 'create' }}';
                    const modal = new bootstrap.Modal(document.getElementById('lessonModal'));

                    // Set up form based on mode before showing modal
                    if (mode === 'create') {
                        // Setup for create mode
                        document.getElementById('lessonModalTitle').innerHTML =
                            `<i class="fas fa-plus-circle me-2"></i> Thêm trình độ mới`;
                        document.getElementById('lessonForm').action = "{{ route('admin.lesson.create') }}";
                        document.getElementById('lessonFormMethod').value = 'POST';
                    } else {
                        // Setup for edit mode
                        document.getElementById('lessonModalTitle').innerHTML =
                            `<i class="fas fa-edit me-2"></i> Chỉnh sửa trình độ`;
                        const level = '{{ old('level') }}';
                        document.getElementById('lessonForm').action =
                            "{{ route('admin.lesson.EditLesson', ['level' => '__level__']) }}".replace('__level__',
                                encodeURIComponent(level));
                        document.getElementById('lessonFormMethod').value = 'PUT';
                    }

                    // Fill form with old values
                    document.getElementById('lessonLevel').value = '{{ old('level', '') }}';
                    document.getElementById('lessonTitle').value = '{{ old('title', '') }}';
                    document.getElementById('lessonOrder').value = '{{ old('order_index', '1') }}';

                    // Show modal
                    modal.show();

                    // Set CKEditor content after modal is shown
                    setTimeout(() => {
                        if (lessonEditor) {
                            lessonEditor.setData(`{!! old('description', '') !!}`);
                        }
                    }, 500);
                });
            @elseif (old('_form_name') === 'edit_lesson_part')
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = new bootstrap.Modal(document.getElementById('editLessonPartModal'));
                    modal.show();

                    // Fill old values
                    document.getElementById('editPartId').value = '{{ old('lesson_part_id') }}';
                    document.getElementById('editPartLevel').value = '{{ old('level') }}';
                    document.getElementById('editPartType').value = '{{ old('part_type') }}';
                    document.getElementById('editPartContent').value = '{{ old('content') }}';
                    document.getElementById('editPartOrder').value = '{{ old('order_index') }}';

                    // Update form action
                    const form = document.getElementById('editLessonPartForm');
                    form.action = "{{ route('admin.lesson.EditLessonPart', ['lesson_part_id' => '__id__']) }}"
                        .replace('__id__', '{{ old('lesson_part_id') }}');
                });
            @endif
        @endif
    </script>
@endsection
