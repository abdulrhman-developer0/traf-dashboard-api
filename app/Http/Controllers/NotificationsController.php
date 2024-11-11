<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Notification;
use App\Models\BulkNotificationMember;
use Auth;


class NotificationsController extends Controller
{
 
    public function index()
    {

        if (!Auth::user()->can('notification-system-notifications-view')) {
            abort(403,'Access Forbidden');
        }

    }

    public function update(Request $request, Notification $notification)
    {
        if (!Auth::user()->can('notification-system-notifications-edit')) {
            abort(403,'Access Forbidden');
        }

        $notification->update([
            'read' => 1
        ]);

        $check_bulk = BulkNotificationMember::where('notification_id',$notification->id)->first();
        if($check_bulk){
            $check_bulk->bulk_notification->read_rate += 1;
            $check_bulk->bulk_notification->save();
        }


        return;
    }

}
