# Progress API Documentation

## Tổng quan
API tính tiến độ học tập được thiết kế dựa trên công thức tính tiến độ từ tài liệu tham khảo. API này cung cấp 3 endpoint chính để tính tiến độ ở các cấp độ khác nhau.

## Công thức tính tiến độ

### 1. Tiến độ Lesson Part
- **Điều kiện hoàn thành**: Trả lời hết tất cả câu hỏi VÀ đúng ít nhất 70%
- **Công thức**:
  - Nếu hoàn thành: 100%
  - Nếu chưa hoàn thành: `(số câu đã trả lời / tổng số câu) × 100%` (tối đa 99%)

### 2. Tiến độ Lesson
- **Công thức**: `(số lesson_part đã hoàn thành / tổng số lesson_part) × 100%`

### 3. Tiến độ tổng quan
- **Công thức**: `(số lesson đã hoàn thành / tổng số lesson) × 100%`

## API Endpoints

### 1. Tiến độ Lesson Part với Học sinh

**GET** `/api/progress/lesson-part/{lessonPartId}/student/{studentId}`

**Mô tả**: Tính tiến độ của một lesson_part cụ thể với một học sinh

**Parameters**:
- `lessonPartId` (integer): ID của lesson part
- `studentId` (integer): ID của học sinh

**Response**:
```json
{
    "success": true,
    "data": {
        "student_id": 1,
        "lesson_part_id": 1,
        "lesson_part_title": "Vocabulary",
        "total_questions": 10,
        "answered_questions": 8,
        "correct_answers": 6,
        "progress_percentage": 80.0,
        "is_completed": false,
        "required_correct_answers": 7
    }
}
```

**Ví dụ**:
```bash
GET /api/progress/lesson-part/1/student/1
```

### 2. Tiến độ Lesson với Học sinh

**GET** `/api/progress/lesson/{lessonLevel}/student/{studentId}`

**Mô tả**: Tính tiến độ của một lesson cụ thể với một học sinh

**Parameters**:
- `lessonLevel` (string): Level của lesson (ví dụ: "A1", "A2", "B1")
- `studentId` (integer): ID của học sinh

**Response**:
```json
{
    "success": true,
    "data": {
        "student_id": 1,
        "lesson_level": "A1",
        "lesson_title": "Basic English",
        "total_parts": 5,
        "completed_parts": 3,
        "progress_percentage": 60.0,
        "is_completed": false
    }
}
```

**Ví dụ**:
```bash
GET /api/progress/lesson/A1/student/1
```

### 3. Tiến độ Tổng quan của Học sinh

**GET** `/api/progress/student/{studentId}/overall`

**Mô tả**: Tính tiến độ tổng quan của học sinh trên tất cả các lesson

**Parameters**:
- `studentId` (integer): ID của học sinh

**Response**:
```json
{
    "success": true,
    "data": {
        "student_id": 1,
        "student_name": "Nguyễn Văn A",
        "total_lessons": 10,
        "completed_lessons": 3,
        "overall_progress_percentage": 30.0,
        "lessons_progress": [
            {
                "lesson_level": "A1",
                "lesson_title": "Basic English",
                "total_parts": 5,
                "completed_parts": 5,
                "progress_percentage": 100.0,
                "is_completed": true
            },
            {
                "lesson_level": "A2",
                "lesson_title": "Elementary English",
                "total_parts": 6,
                "completed_parts": 3,
                "progress_percentage": 50.0,
                "is_completed": false
            }
        ]
    }
}
```

**Ví dụ**:
```bash
GET /api/progress/student/1/overall
```

## Error Responses

### 404 - Not Found
```json
{
    "success": false,
    "message": "Student hoặc Lesson Part không tồn tại"
}
```

### 500 - Internal Server Error
```json
{
    "success": false,
    "message": "Lỗi khi tính tiến độ lesson part: [error details]"
}
```

## Cách sử dụng trong Android App

### 1. Hiển thị tiến độ lesson part
```kotlin
// Gọi API để lấy tiến độ lesson part
val response = apiService.getLessonPartProgress(lessonPartId, studentId)
if (response.success) {
    val progress = response.data.progressPercentage
    val isCompleted = response.data.isCompleted
    
    // Cập nhật UI
    progressBar.progress = progress.toInt()
    if (isCompleted) {
        showCompletedBadge()
    }
}
```

### 2. Hiển thị tiến độ lesson
```kotlin
// Gọi API để lấy tiến độ lesson
val response = apiService.getLessonProgress(lessonLevel, studentId)
if (response.success) {
    val completedParts = response.data.completedParts
    val totalParts = response.data.totalParts
    
    // Hiển thị progress
    lessonProgressText.text = "$completedParts/$totalParts parts completed"
}
```

### 3. Dashboard tổng quan
```kotlin
// Gọi API để lấy tiến độ tổng quan
val response = apiService.getStudentOverallProgress(studentId)
if (response.success) {
    val overallProgress = response.data.overallProgressPercentage
    val lessonsProgress = response.data.lessonsProgress
    
    // Hiển thị dashboard
    overallProgressBar.progress = overallProgress.toInt()
    setupLessonsRecyclerView(lessonsProgress)
}
```

## Lưu ý quan trọng

1. **Điều kiện hoàn thành lesson part**: Phải trả lời hết tất cả câu hỏi VÀ đúng ít nhất 70%
2. **Tiến độ tối đa khi chưa hoàn thành**: 99% (không bao giờ đạt 100% nếu chưa đáp ứng điều kiện)
3. **Caching**: Nên cache kết quả để tránh tính toán lại nhiều lần
4. **Performance**: API sử dụng các query tối ưu để tính toán nhanh chóng

## Testing

Để test API, bạn có thể sử dụng các tool như Postman hoặc curl:

```bash
# Test tiến độ lesson part
curl -X GET "http://localhost:8000/api/progress/lesson-part/1/student/1"

# Test tiến độ lesson
curl -X GET "http://localhost:8000/api/progress/lesson/A1/student/1"

# Test tiến độ tổng quan
curl -X GET "http://localhost:8000/api/progress/student/1/overall"
```
