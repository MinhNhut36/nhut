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
            // Lấy thông báo dành cho học sinh cụ thể hoặc tất cả học sinh
            $notifications = Notification::where(function($query) use ($studentId) {
                $query->where('target', $studentId)
                      ->orWhere('target', 0); // 0 = tất cả học sinh
            })
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
            $notification = Notification::find($notificationId);
            
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
     * Đánh dấu thông báo đã đọc
     * PUT /api/notifications/{notificationId}/read
     */
    public function markNotificationAsRead($notificationId)
    {
        try {
            $notification = Notification::find($notificationId);
            
            if (!$notification) {
                return response()->json([
                    'error' => 'Không tìm thấy thông báo'
                ], 404);
            }
            
            $notification->update(['status' => 1]); // 1 = đã đọc
            
            return response()->json($notification, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
