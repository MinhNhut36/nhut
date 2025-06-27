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
                        <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addLevelModal">
                            <i class="fas fa-plus me-2"></i>
                            Thêm trình độ
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
                                            <a href="#" class="btn-action btn-edit" title="Chỉnh sửa"
                                                onclick="event.stopPropagation();">
                                                <i class="fas fa-edit"></i>
                                            </a>
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
                                                            <a href="#"
                                                                onclick="openEditPartModal({{ $part->id }})"
                                                                title="Chỉnh sửa phần này">
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

    <!-- Add Level Modal -->
    <div class="modal fade" id="addLevelModal" tabindex="-1" aria-labelledby="addLevelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLevelModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>
                        Thêm trình độ mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>

                {{-- form thêm trình độ --}}
                <div class="modal-body">
                    <form id="addLevelForm" method="POST" action="{{ route('admin.lesson.create') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="levelSelect" class="form-label">
                                        <i class="fas fa-layer-group me-1"></i>
                                        trình độ
                                    </label>
                                    <input class="form-control @error('level') is-invalid @enderror" type="text"
                                        name="level" value="{{ old('level') }}" placeholder="Nhập tên trình độ...">
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lessonTitle" class="form-label">
                                        <i class="fas fa-heading me-1"></i>
                                        Tiêu đề cho cấp đô
                                    </label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="lessonTitle" name="title" value="{{ old('title') }}"
                                        placeholder="Nhập tên tiêu đề...">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lessonDescription" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Mô tả trình độ
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="lessonDescription" name="description"
                                rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lessonOrder" class="form-label">
                                    <i class="fas fa-sort-numeric-up me-1"></i>
                                    Thứ tự hiển thị
                                </label>
                                <input type="number" class="form-control @error('order_index') is-invalid @enderror"
                                    name="order_index" id="lessonOrder" min="1"
                                    value="{{ old('order_index', 1) }}">
                                @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="saveLesson">
                                <i class="fas fa-save me-1"></i>
                                Lưu trình độ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @if ($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var addLevelModal = new bootstrap.Modal(document.getElementById('addLevelModal'));
                addLevelModal.show();
            });
        </script>
    @endif
    <!-- CKEditor Script -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('lessonDescription');
    </script>
    <script>
        // Custom accordion toggle function sử dụng order_index
        function toggleAccordion(orderIndex) {
            console.log('Toggle accordion for order_index:', orderIndex);

            const partsRow = document.getElementById('parts-' + orderIndex);
            const chevronIcon = document.getElementById('chevron-' + orderIndex);

            console.log('Parts row:', partsRow);
            console.log('Chevron icon:', chevronIcon);

            // Check if elements exist
            if (!partsRow || !chevronIcon) {
                console.error('Elements not found for order_index:', orderIndex);
                return;
            }

            // Get computed style to check current display state
            const currentDisplay = window.getComputedStyle(partsRow).display;
            console.log('Current display:', currentDisplay);

            if (currentDisplay === 'none') {
                // Show the parts
                partsRow.style.display = 'table-row';
                chevronIcon.classList.add('rotated');
                console.log('Expanded order_index:', orderIndex);
            } else {
                // Hide the parts
                partsRow.style.display = 'none';
                chevronIcon.classList.remove('rotated');
                console.log('Collapsed order_index:', orderIndex);
            }
        }

        // Initialize after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing accordion...');

            // Tooltips
            document.querySelectorAll('[title]').forEach(function(element) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    new bootstrap.Tooltip(element);
                }
            });

            // Add event listeners for accordion toggle
            const accordionRows = document.querySelectorAll('.accordion-toggle');
            console.log('Found accordion rows:', accordionRows.length);

            accordionRows.forEach(function(row, index) {
                console.log('Adding listener to row:', index, row);

                row.addEventListener('click', function(e) {
                    console.log('Row clicked:', e.target);

                    // Don't trigger if clicking on action buttons
                    if (e.target.closest('.action-buttons')) {
                        console.log('Clicked on action button, ignoring...');
                        return;
                    }

                    // Lấy order_index từ data attribute
                    const orderIndex = this.getAttribute('data-lesson-order');
                    console.log('Order Index from attribute:', orderIndex);

                    if (orderIndex) {
                        toggleAccordion(orderIndex);
                    } else {
                        console.error('No order index found on row');
                    }
                });

                // Also add cursor pointer style
                row.style.cursor = 'pointer';
            });
        });
    </script>
@endsection
