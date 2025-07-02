<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Lấy thông báo theo học sinh
     * GET /api/notifications/student/{studentId}
     */
    public function getNotificationsByStudentId($studentId)
    {
        try {
            // Validate student exists
            $student = \App\Models\Student::find($studentId);
            if (!$student) {
                return response()->json([
                    'error' => 'Không tìm thấy học sinh'
                ], 404);
            }

            // Lấy tất cả thông báo (không còn filter theo target)
            $notifications = Notification::with('admin')
                                        ->orderBy('notification_date', 'desc')
                                        ->get();

            return response()->json($notifications, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy thông báo theo ID
     * GET /api/notifications/{notificationId}
     */
    public function getNotificationById($notificationId)
    {
        try {
            $notification = Notification::with('admin')->find($notificationId);

            if (!$notification) {
                return response()->json([
                    'error' => 'Không tìm thấy thông báo'
                ], 404);
            }

            return response()->json($notification, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Tạo thông báo mới
     * POST /api/notifications
     */
    public function createNotification(Request $request)
    {
        try {
            $validated = $request->validate([
                'admin_id' => 'required|integer|exists:users,admin_id',
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'notification_date' => 'required|date'
            ]);

            $notification = Notification::create($validated);

            return response()->json([
                'success' => true,
                'data' => $notification->load('admin'),
                'message' => 'Notification created successfully'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông báo
     * PUT /api/notifications/{notificationId}
     */
    public function updateNotification(Request $request, $notificationId)
    {
        try {
            $notification = Notification::findOrFail($notificationId);

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'message' => 'sometimes|string',
                'notification_date' => 'sometimes|date'
            ]);

            $notification->update($validated);

            return response()->json([
                'success' => true,
                'data' => $notification->load('admin'),
                'message' => 'Notification updated successfully'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Notification not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa thông báo
     * DELETE /api/notifications/{notificationId}
     */
    public function deleteNotification($notificationId)
    {
        try {
            $notification = Notification::findOrFail($notificationId);
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Notification not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
