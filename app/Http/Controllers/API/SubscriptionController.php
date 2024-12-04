<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use App\Services\PayMobService;
use App\Traits\APIResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        ]);

        $user = Auth::user();
        if (!$user->isAccount('service-provider')) {
            return $this->badResponse('Only service providers can subscribe to packages');
        }

        $package = Package::find($validated['package_id']);
        $serviceProvider = $user->account();

        try {
            DB::beginTransaction();

            $subscription = Subscription::create([
                'service_provider_id' => $serviceProvider->id,
                'package_id' => $package->id,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            $paymentResponse = $this->payMobService->createPaymentOrder([
                'amount' => $package->price * 100, // Amount in cents
                'currency' => 'SAR',
                'order_id' => $subscription->id,
                'items' => [[
                    'name' => $package->name,
                    'amount' => $package->price * 100,
                    'description' => "Subscription to {$package->name} package",
                    'quantity' => 1
                ]],
                'customer' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $serviceProvider->phone
                ]
            ]);

            $subscription->update([
                'transaction_reference' => $paymentResponse['id']
            ]);

            DB::commit();

            return $this->okResponse([
                'subscription_id' => $subscription->id,
                'payment_url' => $paymentResponse['payment_url']
            ], 'Subscription initiated successfully');
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
