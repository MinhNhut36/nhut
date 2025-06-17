# Progress API Migration Guide

## Tổng quan
Tài liệu này hướng dẫn việc chuyển đổi từ các API progress cũ sang API progress mới với công thức tính toán chính xác theo tài liệu tham khảo.

## ⚠️ API Deprecated

### 1. API Cũ (DEPRECATED)

#### `GET /api/progress/student/{studentId}`
- **Trạng thái**: DEPRECATED ❌
- **Thay thế bằng**: `GET /api/progress/student/{studentId}/overall`
- **Lý do deprecated**: Không áp dụng công thức tính tiến độ chính xác

#### `GET /api/progress/student/{studentId}/course/{courseId}`
- **Trạng thái**: DEPRECATED ❌
- **Thay thế bằng**: `GET /api/progress/lesson/{lessonLevel}/student/{studentId}`
- **Lý do deprecated**: Không áp dụng công thức tính tiến độ chính xác

## ✅ API Mới (RECOMMENDED)

### 1. Tiến độ Lesson Part
```
GET /api/progress/lesson-part/{lessonPartId}/student/{studentId}
```

### 2. Tiến độ Lesson
```
GET /api/progress/lesson/{lessonLevel}/student/{studentId}
```

### 3. Tiến độ Tổng quan
```
GET /api/progress/student/{studentId}/overall
```

## 🔄 Migration Steps

### Bước 1: Cập nhật Android App

#### Thay thế API calls cũ:

**Cũ:**
```kotlin
// DEPRECATED - Không sử dụng nữa
val response = apiService.getStudentProgress(studentId)
```

**Mới:**
```kotlin
// SỬ DỤNG API MỚI
val response = apiService.getStudentOverallProgress(studentId)
```

**Cũ:**
```kotlin
// DEPRECATED - Không sử dụng nữa
val response = apiService.getStudentProgressByCourse(studentId, courseId)
```

**Mới:**
```kotlin
// SỬ DỤNG API MỚI - Lấy tiến độ theo lesson level
val response = apiService.getLessonProgress(lessonLevel, studentId)
```

### Bước 2: Cập nhật Response Handling

#### Response Format Cũ vs Mới

**API Cũ Response:**
```json
{
    "success": true,
    "message": "API này đã deprecated...",
    "data": {
        "student_id": 1,
        "overall_progress_percentage": 30.0,
        "lessons_progress": [...]
    }
}
```

**API Mới Response:**
```json
{
    "success": true,
    "data": {
        "student_id": 1,
        "student_name": "Nguyễn Văn A",
        "overall_progress_percentage": 30.0,
        "lessons_progress": [...]
    }
}
```

### Bước 3: Cập nhật Retrofit Interface

**Cũ:**
```kotlin
interface ApiService {
    @GET("progress/student/{studentId}")
    suspend fun getStudentProgress(@Path("studentId") studentId: Int): Response<ProgressResponse>
    
    @GET("progress/student/{studentId}/course/{courseId}")
    suspend fun getStudentProgressByCourse(
        @Path("studentId") studentId: Int,
        @Path("courseId") courseId: Int
    ): Response<ProgressResponse>
}
```

**Mới:**
```kotlin
interface ApiService {
    @GET("progress/student/{studentId}/overall")
    suspend fun getStudentOverallProgress(@Path("studentId") studentId: Int): Response<ProgressResponse>
    
    @GET("progress/lesson/{lessonLevel}/student/{studentId}")
    suspend fun getLessonProgress(
        @Path("lessonLevel") lessonLevel: String,
        @Path("studentId") studentId: Int
    ): Response<ProgressResponse>
    
    @GET("progress/lesson-part/{lessonPartId}/student/{studentId}")
    suspend fun getLessonPartProgress(
        @Path("lessonPartId") lessonPartId: Int,
        @Path("studentId") studentId: Int
    ): Response<ProgressResponse>
}
```

## 🎯 Lợi ích của API Mới

### 1. Tính toán chính xác
- ✅ Áp dụng công thức 70% câu đúng để hoàn thành lesson part
- ✅ Tiến độ tối đa 99% nếu chưa hoàn thành
- ✅ Chỉ đạt 100% khi thực sự hoàn thành

### 2. Hiệu suất tốt hơn
- ✅ Query database tối ưu
- ✅ Tính toán real-time
- ✅ Response format chuẩn

### 3. Dễ bảo trì
- ✅ Code sạch và có cấu trúc
- ✅ Error handling tốt hơn
- ✅ Documentation đầy đủ

## 📅 Timeline Migration

### Phase 1: Parallel Support (Hiện tại)
- ✅ API cũ vẫn hoạt động nhưng có warning deprecated
- ✅ API mới đã sẵn sàng sử dụng
- ✅ Response của API cũ đã được cập nhật để sử dụng công thức mới

### Phase 2: Migration Period (1-2 tháng)
- 🔄 Cập nhật Android app để sử dụng API mới
- 🔄 Test và verify tính chính xác
- 🔄 Monitor performance

### Phase 3: Deprecation (Sau 2-3 tháng)
- ❌ Xóa hoàn toàn API cũ
- ✅ Chỉ giữ lại API mới

## 🧪 Testing

### Test API Cũ (để verify migration)
```bash
# Test API cũ - sẽ có warning deprecated
curl -X GET "http://localhost:8000/api/progress/student/1"
curl -X GET "http://localhost:8000/api/progress/student/1/course/1"
```

### Test API Mới
```bash
# Test API mới - recommended
curl -X GET "http://localhost:8000/api/progress/student/1/overall"
curl -X GET "http://localhost:8000/api/progress/lesson/A1/student/1"
curl -X GET "http://localhost:8000/api/progress/lesson-part/1/student/1"
```

## 🚨 Breaking Changes

### 1. Response Structure
- API mới có cấu trúc response khác
- Cần cập nhật data models trong Android app

### 2. URL Parameters
- Course ID thay bằng Lesson Level
- Cần mapping từ course sang lesson level

### 3. Progress Calculation
- Công thức tính tiến độ hoàn toàn mới
- Kết quả có thể khác so với trước

## 📞 Support

Nếu gặp vấn đề trong quá trình migration:
1. Kiểm tra documentation mới
2. Test với Postman/curl
3. Verify response format
4. Liên hệ team backend để support

## 📋 Checklist Migration

- [ ] Cập nhật Retrofit interface
- [ ] Thay thế API calls cũ
- [ ] Cập nhật response handling
- [ ] Test với data thật
- [ ] Verify UI hiển thị đúng
- [ ] Performance testing
- [ ] Deploy và monitor
