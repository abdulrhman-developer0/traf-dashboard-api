<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\AdCollection;
use App\Models\Ad;
use App\Notifications\DBNotification;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdController extends Controller
{
    use APIResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $total_in_ads = Ad::count();

        $in_ads_amount = Ad::sum('total_price');

        $in_ads_today = Ad::whereDay('created_at', now())->count();

        $in_ads_today_amount = Ad::whereDay('created_at', now())
            ->whereIn('status', ['approved', 'waiting'])
            ->sum('total_price');

        $stats = [
            'total_in_ads' => $total_in_ads,
            'in_ads_amount' => $in_ads_amount,
            'in_ads_today' => $in_ads_today,
            'in_ads_today_amount' => $in_ads_today_amount,
        ];

        $ads = Ad::query()
            ->when($request->status, fn($q) => $q->whereStatus($request->status))
            ->latest()
            ->paginate(6);

        $data = [
            'stats' => $stats,
            'ads' => AdCollection::make($paginator)
        ];

        //dd($data);

        // return Inertia::render('ads/index', [
        //     'data' => $data,
        //     'title' => 'Ads'
        // ]);
        return $this->okResponse($ads, "Ads retrieved successfuly");
    }


    public function update(Request $request, Ad $ad)
    {
        $request->validate([
            'status' => 'required|string|in:approved,rejected',
            'notes'  => 'required_if:status,rejected|string',
        ]);

        $ad->update($request->only(['status', 'notes']));

        $status = $request->status;

        if ($status == 'pending-payment') {
            $ad->update([
                'start_date' => now(),
                'end_date' => now()->addDay($ad->duration_in_days)
            ]);
        }

        if (in_array($status, ['rejected', 'pending-payment'])) {
            $targetUser = $ad->serviceProvider->user;

            $title = match ($status) {
                'rejected' => 'تم رفض اعلانك',
                'pending-payment' => 'تم مراجعة اعلانك',
                default => 'default'
            };

            $message = match ($status) {
                'rejected' => 'يرجى مراجعة اسباب الرفض ثم المحاولة مرة اخرة',
                'pending-payment' => 'تم الموافقة على اعلانك يرجى متابعة عملية الدفع لنشر اعلانك',
                default => 'default'
            };

            $data = [
                'status' => $status,
                'title' => $title,
                'message' => $message,
                'sent_at' => now(),
                'ad_id' => $ad->id,
                'user' => null,
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

        // return back()->with('status', ['type' => 'success', 'action' => 'تم تحديث الإعلان بنجاح', 'text' => '']);
        return $this->okResponse($ad, 'تم تحديث الإعلان بنجاح');
    }

    // public function update(Request $request, $id)
    // {
    //     $ad = Ad::findOrFail($id);


    //     $validatedData = $request->validate([
    //         'status' => 'required|string',
    //         'notes' => 'nullable|string'
    //     ]);


    //     $ad->fill($validatedData);


    //     if ($request->status === 'pending-payment') {
    //         $ad->start_date = now();
    //         $ad->end_date = now()->addDays($ad->duration_in_days);
    //     }

    //     $ad->save();


    //     if (in_array($request->status, ['rejected', 'pending-payment'])) {
    //         $targetUser = $ad->serviceProvider->user;

    //         $messages = [
    //             'rejected' => ['تم رفض إعلانك', 'يرجى مراجعة أسباب الرفض ثم المحاولة مرة أخرى.'],
    //             'pending-payment' => ['تم مراجعة إعلانك', 'تمت الموافقة على إعلانك، يرجى متابعة عملية الدفع لنشره.']
    //         ];

    //         $data = [
    //             'status' => $request->status,
    //             'title' => $messages[$request->status][0],
    //             'message' => $messages[$request->status][1],
    //             'sent_at' => now(),
    //             'ad_id' => $ad->id,
    //             'user' => null,
    //         ];


    //         $targetUser->notify(new DBNotification($data));

    //         app('App\Http\Controllers\API\FcmController')
    //             ->sendFcmNotification($targetUser->id, $data['title'], $data['message']);
    //     }

    //     return $this->okResponse($ad, 'تم تحديث الإعلان بنجاح');
    // }
}
