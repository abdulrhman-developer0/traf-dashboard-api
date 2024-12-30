<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\AdCollection;
use App\Models\Ad;
use App\Notifications\DBNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $total_in_ads           = Ad::count();

        $in_ads_amount           = Ad::sum('total_price');

        $in_ads_today           = Ad::whereDay('created_at', now())->count();

        $in_ads_today_amount    = Ad::whereDay('created_at', now())
            ->whereIn('status', ['approved', 'waiting'])
            ->sum('total_price');

        $stats = [
            'total_in_ads'          => $total_in_ads,
            'in_ads_amount'          => $in_ads_amount,
            'in_ads_today'          => $in_ads_today,
            'in_ads_today_amount'   => $in_ads_today_amount,
        ];

        $ads = [];

        // Grab all ad statuses.
        foreach (Ad::STATUSES as $status) {
            $paginator = Ad::query()
                ->whereStatus($status)
                ->latest()
                ->paginate(6);

            $ads[$status] = AdCollection::make($paginator);
        }

        $data = [
            'stats' => $stats,
            'ads'   => $ads
        ];

        //dd($data);

        return Inertia::render('ads/index', [
            'data' => $data,
            'title' => 'Ads'
        ]);
    }


    public function update(Request $request, Ad $ad)
    {
        $request->validate([
            'status'    => 'required',
        ]);

        $ad->update($request->only(['status', 'notes']));

        $status = $request->status;

        if (in_array($status, ['rejected', 'pending-payment'])) {
            $targetUser = $ad->serviceProvider->user;

            $title = match ($status) {
                'rejected'          => 'تم رفض اعلانك',
                'pending-payment'   => 'تم مراجعة اعلانك',
                default             => 'default'
            };

            $message = match ($status) {
                'rejected'          => 'يرجى مراجعة اسباب الرفض ثم المحاولة مرة اخرة',
                'pending-payment'   => 'تم الموافقة على اعلانك يرجى متابعة عملية الدفع لنشر اعلانك',
                default             => 'default'
            };

            $data = [
                'title'             => $title,
                'message'           => $message,
                'ad_id'             => $ad->id,
                'duration'          => $ad->duration_in_days,
            ];

            // Notify the target user in the database.
            $targetUser->notify(new DBNotification($data));

            // Notify the target user via FCM
            app('App\Http\Controllers\API\FcmController')
                ->sendFcmNotification(
                    $targetUser->id,
                    $title,
                    $message
                );
        }

        return back()->with('status', ['type' => 'success', 'action' => 'تم تحديث الإعلان بنجاح', 'text' => '']);
    }
}
