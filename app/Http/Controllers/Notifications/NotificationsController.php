<?php

namespace App\Http\Controllers\Notifications;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;

class NotificationsController extends Controller
{
    public function getLatestNotifications(Request $request, User $user)
    {
        $notifications = $user->notifications()
        ->where('read_at', null)
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json(['notifications' => NotificationResource::collection($notifications)]);
    }
    
    public function markNotificationRead(Request $request, User $user, string $notification) 
    {
        $notification = $user->notifications()->find($notification);
        if($notification) {
            $notification->delete();
        }

        return response()->json([
            'message' => 'success'
        ], 200);
    } 

    public function markAllNotificationRead(Request $request, User $user) 
    {   
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

}
