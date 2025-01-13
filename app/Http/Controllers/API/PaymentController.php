<?php

namespace App\Http\Controllers\API;

use App\Enums\ActivityActions;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Notifications\DBNotification;
use App\Traits\APIResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PayMobService;

class PaymentController extends Controller
{
    //
    use APIResponses;
    protected $payMobService;

    public function __construct(PayMobService $payMobService)
    {
        $this->payMobService = $payMobService;
    }

    
    public function subscribe(Request $request)
    {
        // Validate the booking and transaction_id
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'transaction_id' => 'required'
        ]);


        $user = Auth::user();
        if (!$user->isAccount('client')) {
            return $this->badResponse('Only Clients can Access to this Payment');
        }

        $client = $user->account();

        $booking = Booking::where('id', $request->booking_id)
            ->where('client_id', $client->id)
            ->first();

        if (!$booking) {
            return $this->badResponse('Unauthorized access to the booking');
        }

        // Update the booking status
        $booking->status = 'confirmed';
        $booking->save();

        $service = $booking->service;
        $serviceName = $service->name;
        $amount = $service->is_offer ? $service->price_after : $service->price_before;

        // Start a database transaction
        DB::beginTransaction();

        try {

            // Create the payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_status' => 'paid',
                'transaction_reference' => $request->transaction_id,  // Corrected variable
                'amount' => $amount,  // Assuming you need to store the amount as well
                'service_id' => $service->id, // Assuming you need to store service_id
            ]);

            // Commit the transaction
            DB::commit();

            $targtUser = $booking->service->serviceProvider->user;

            $title   = 'تم تأكيد موعد';
            $message = __('mobile.confirmed_booking',[
                'service_name' => $booking->service->name,
                'name' => $user->name,
                'time' => $booking->date->format('h:i A')
            ], 'ar');
            
            $data = [
                'status' => 'confirmed',
                'date' => $booking->date,
                'sent_at' => now(),
                'title' => $title,
                'message' => $message,
                'user' => [
                    'id' => (string) $user->id,
                    'account_id' => (string) $user->account()->id,
                    'name' => $user->name
                ]
            ];

            // Notify the target user in the database.
            $targtUser->notify(new DBNotification($data));

            // Notify the target user via FCM
            app('App\Http\Controllers\API\FcmController')
                ->sendFcmNotification(
                    $targtUser->id,
                    $title,
                    $message
                );

                activities(ActivityActions::PaymentMade, 'عملية دفع جديدة', "قام $user->name بدفع $amount ريال لجلسة {$booking->service->name}");

            // Optionally: You can return a success response with payment details
            return $this->okResponse(['payment' => $payment], 'Payment subscription created successfully.');
        } catch (\Exception $e) {
            // If an error occurs, rollback the transaction
            DB::rollBack();
            // Return an error response
            return $this->badResponse([], 'Failed to create subscription: ' . $e->getMessage());
        }
    }
}
