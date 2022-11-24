<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Events\Notifications;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    public function getNotification($id)
    {
        // get all notifications for user
        $allNotif = Notification::where('to', auth()->user()->id)->get();
        return response()->json($allNotif);
    }
    public function getNotificationAll($id)
    {
        // get all notifications for user
        $allNotif = Notification::where([['to', '=', auth()->user()->id], ['status', '=', 'all']])->get();
        return response()->json($allNotif);
    }

    public function getNotificationNew($id)
    {
        // get all notifications for user
        $allNotif = Notification::where([['to', '=', auth()->user()->id], ['status', '=', 'new']])->get();
        return response()->json($allNotif);
    }

    public function getNotificationSaved($id)
    {
        // get all notifications for user
        $allNotif = Notification::where([['to', '=', auth()->user()->id], ['status', '=', 'saved']])->get();
        return response()->json($allNotif);
    }

    public function sendNotification(Request $request)
    {
        $notification = Notification::create([
            'from' => $request->from,
            'to' => $request->recipient,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'text' => $request->text,
            'date' => $request->date,
            'status' => $request->status,
        ]);

        broadcast(new Notifications($notification->load('fromUser')))->toOthers();

        return response()->json($notification);
    }

    public function deleteNotification(Request $request)
    {
        foreach ($request->ids as $ids):
          Notification::where('id', $ids)->delete();
        endforeach;
        return response()->json("successfully deleted notification");
    }

    public function addToSaved(Request $request)
    {
        foreach ($request->ids as $ids):
          Notification::where('id', $ids)->update(['status' => 'saved']);
        endforeach;
        return response()->json("successfully move notification to saved notifications");
    }

    public function addToAll(Request $request)
    {
        foreach ($request->ids as $ids):
          Notification::where('id', $ids)->update(['status' => 'all']);
        endforeach;
        return response()->json("successfully move notification from saved notifications to all");
    }

    public function markAsRead()
    {
        Notification::where('status', 'new')->update(['status' => 'all']);
        //"successfully deleted notification"
        return response()->json("successfully updated notifications status");
    }

    public function getFewNewestNotifications() {
      $user_id = auth()->user()->id;
      $fewNewestMNotifications = DB::select("select notifications.*, notifications.status as status_notif, users.name, users.email, users.status, users.avatar
       FROM notifications
       INNER JOIN users ON users.id = notifications.from
       WHERE notifications.status = 'new' AND notifications.to = $user_id
       ORDER BY notifications.created_at DESC
       LIMIT 3");
      return response()->json($fewNewestMNotifications);
    }

    public function getFewOldNotifications() {
      $user_id = auth()->user()->id;
      $fewOldMNotifications = DB::select("select notifications.*, users.name, users.email, users.status, users.avatar
       FROM notifications
       INNER JOIN users ON users.id = notifications.from
       WHERE notifications.status = 'all' AND notifications.to = $user_id
       ORDER BY notifications.created_at DESC
       LIMIT 3");
      return response()->json($fewOldMNotifications);
    }
}
