<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
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

    public function subscribe(Request $request) {

        $validatedBooking = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
           
        ]);
        $user = Auth::user();
        if(!$user->isAccount('client')){
         return $this->badResponse('Only Clients can Access to this Payment');
        }
        $client= $user->account();
        
        $singleBooking =Booking::where('id',$validatedBooking['booking_id'])
        ->where('client_id', $client->id)
        ->first();
        if (!$singleBooking) {
            return $this->badResponse('Unauthorized access to the booking');
        }
        $serviceId =$singleBooking->service_id;
        $service= Service::where('id',$serviceId)->first();
        if (!$service) {
            return $this->badResponse('Service not found');
        }
        $serviceName=$service->name;
        $amount = $service->is_offer ? $service->price_after : $service->price_before;
        echo $amount;
   
     
       try {

        DB::beginTransaction();
        $payment =Payment::create([
            'client_id' => $client->id, 
            'status' => 'pending',
                  'payment_status' => 'pending',
        ]);
        $paymentResponse = $this->payMobService->createPaymentOrder([
            'amount' => $amount * 100, // Amount in cents
            'currency' => 'EGP',
            'order_id' => $payment->id,
            'items' => [[
                'name' => $serviceName,
                'amount' => $amount * 100,
                'description' => "Subscription to {$serviceName} package",
                'quantity' => 1
            ]],
            'customer' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $client->phone
            ]
        ]);
        $payment->update([
            'transaction_reference' => $paymentResponse['id']
        ]);

        DB::commit();

        return $this->okResponse([
            'payment_id' => $payment->id,
            'payment_url' => $paymentResponse['payment_url']
        ], 'payment initiated successfully');




       } catch(\Exception $e) {
        DB::rollBack();
        return $this->badResponse([], 'Failed to create subscription: ' . $e->getMessage());
       }
    }
      /**
     * Handle PayMob payment webhook
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handlePaymentWebhook(Request $request)
{
    try {
       
        $paymentData = $this->payMobService->validateWebhook($request->all());

        if (!$paymentData) {
            return $this->badResponse('Invalid webhook data');
        }

        // Find the payment record by transaction_reference
        $payment = Payment::where('transaction_reference', $paymentData['order_id'])->firstOrFail();

        if ($paymentData['success']) {
            // Update the payment status and set the subscription active
            $payment->update([
                'status' => 'active',
                'payment_status' => 'paid',
                'payment_id' => $paymentData['transaction_id'],
                'start_date' => now(),
                'end_date' => now()->addDays(30), // Assuming a 30-day duration for the service
            ]);
        } else {
            // Update the payment status to failed
            $payment->update([
                'status' => 'failed',
                'payment_status' => 'failed',
            ]);
        }

        return $this->okResponse(null, 'Webhook processed successfully');
    } catch (\Exception $e) {
        // Handle any exceptions
        return $this->badResponse('Failed to process payment webhook: ' . $e->getMessage());
    }
}

    
}
