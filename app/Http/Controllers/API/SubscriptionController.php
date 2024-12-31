<?php

namespace App\Http\Controllers\API;

use App\Enums\ActivityActions;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use App\Services\PayMobService;
use App\Traits\APIResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Reverb\Protocols\Pusher\Server;

class SubscriptionController extends Controller
{
    use APIResponses;

    protected $payMobService;

    public function __construct(PayMobService $payMobService)
    {
        $this->payMobService = $payMobService;
    }

    /**
     * Create a new subscription and initialize payment process
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'transaction_id' => 'required'
        ]);

        $user = Auth::user();
        if (!$user->isAccount('service-provider')) {
            return $this->badResponse('Only service providers can subscribe to packages');
        }

        $package = Package::find($validated['package_id']);
        $serviceProvider = $user->account();

        try {
            DB::beginTransaction();

            if (! $serviceProvider->currentSubscription()->exists() ) {
                $subscription = Subscription::create([
                    'service_provider_id' => $serviceProvider->id,
                    'package_id' => $package->id,
                    'payment_status' => 'paid',
                    'amount' => $package->price,
                    'start_date' => Carbon::now(),  // Set current timestamp as the start date
                    'end_date' => Carbon::now()->addDays($package->duration_in_days),
                    'transaction_reference'=> $validated["transaction_id"]
                ]);
            } else {
                $subscription = $serviceProvider->currentSubscription;

                // $leftDays =  round(now()->diffInDays($subscription->end_date));
                
                $subscription->end_date = $subscription->end_date->addDays($package->duration_in_days);
                $subscription->save();
            }

            activities(ActivityActions::PackageSubscribed, 'اشتراك جديد', "قام $user->name بالاشتراك في $package->name");


            DB::commit();

            return $this->okResponse(['payment' => $subscription ], 'Payment subscription created successfully.');
        } catch (\Exception $e) {
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

            $subscription = Subscription::where('transaction_reference', $paymentData['order_id'])->firstOrFail();

            if ($paymentData['success']) {
                $package = $subscription->package;
                return $subscription;

                $subscription->update([
                    'status' => 'active',
                    'payment_status' => 'paid',
                    'payment_id' => $paymentData['transaction_id'],
                    'start_date' => now(),
                    'end_date' => now()->addDays($package->duration_in_days)
                ]);
            } else {
                $subscription->update([
                    'status' => 'failed',
                    'payment_status' => 'failed'
                ]);
            }

            return $this->okResponse(null, 'Webhook processed successfully');
        } catch (\Exception $e) {
            return $this->badResponse('Failed to process payment webhook: ' . $e->getMessage());
        }
    }
}
