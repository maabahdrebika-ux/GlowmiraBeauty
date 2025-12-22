<?php 
namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(5);
        return view('dashbord.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->url ?? '/');
    }

    public function fetch(Request $request)
    {
        $unreadNotifications = Notification::where('is_read', false)->orderBy('created_at', 'desc')->take(5)->get();

        $notifications = $unreadNotifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->message,
                'is_read' => $notification->is_read,
                'timeAgo' => $notification->created_at->diffForHumans(),
                'readUrl' => route('notifications.read', $notification->id),
            ];
        });

        $unreadCount = Notification::where('is_read', false)->count();

        return response()->json([
            'unreadCount' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }
}
