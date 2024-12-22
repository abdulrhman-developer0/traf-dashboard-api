<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    use APIResponses;

    public function __construct()
    {
        // protected methods
        $this->middleware(['auth:sanctum']);

        // this methods are only for client and service provider
        $this->middleware('account:client,service-provider')->only([
            'store'
        ]);

        // this methods are only for service provider and admin
        $this->middleware('account:service-provider,admin')->only([
            'update',
            'destroy'
        ]);
    }

    public function index(Request $request)
    {
        $query = Review::query()
            ->latest()
            ->when($request->has('limit'), function ($q) use ($request) {
                $q->limit(
                    $request->query('limit', 20)
                );
            })->when($request->has('booking_id'), function ($q) use ($request) {
                $q->where('booking_id', $request->query('booking_id'));
            })->when($request->has('service_id'), function ($q) use ($request) {
                $q->whereHas(
                    'booking',
                    fn($q) => $q->where('service_id', $request->query('service_id'))
                );
            })->when($request->has('provider_id'), function ($q) use ($request) {
                $q->whereHas(
                    'booking.service',
                    fn($q) => $q->where('service_provider_id', $request->query('provider_id'))
                );
            })->when($request->has('client_id'), function ($q) use ($request) {
                $q->whereHas(
                    'booking.client',
                    fn($q) => $q->where('id', $request->query('client_id'))
                );
            });




        $reviews = $query->get();


        return $this->okResponse(ReviewResource::collection($reviews), 'Reviews retrieved successfuly');
    }

    public function store(Request $request)
    {
        $reviewData = $request->validate([
            'booking_id' => 'required|integer|exists:bookings,id',
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'nullable|string|min:1|max:500',
        ]);

        $booking    = Booking::find($request->booking_id);


        $user       = Auth::user();
        $account    = $user->account();

        if (
            $booking->client->user->id != $user->id && $booking->service->serviceProvider->user->id != $user->id
        ) {
            return $this->badResponse([], "You not have a booking with id {$request->booking_id}");
        }


        $ratableAccount = match ($user->account_type) {
            'client'            => $booking->service->serviceProvider,
            'service-provider'  => $booking->client,
            default             => null
        };

        $ratableAccount->reviews()->create($reviewData);

        

        $ratableAccount->update([
            'rating'  => $ratableAccount->reviews()->avg('rating')
        ]);

        $booking->service->update([
            'rating'  => Review::whereHas(
                'booking',
                fn($q) => $q->where('service_id', $booking->service_id)
            )->avg('rating')
        ]);

        return $booking->service->refresh();


        return $this->createdResponse([], 'Review created successfuly');;
    }

    public function show(string $id)
    {
        $review = Review::find($id);

        if (! $review) {
            return $this->badResponse([], "No Riview With id '{$id}'");
        }

        return $this->okResponse(
            ReviewResource::make($review),
            'Retrieved Review data successfuly'
        );
    }

    public function update(Request $request, string $id)
    {
        return; // disabled this action
        $review = Review::find($id);

        if (! $review) {
            return $this->badResponse([], "No Riview With id '{$id}'");
        }

        $reviewData = $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'required|string|min:1|max:500',
        ]);


        $review->update($reviewData);

        $reviewable = $review->reviewable;
        $reviewable->rating = $reviewable->reviews()->avg('rating');
        $reviewable->save();

        return $this->okResponse([], 'Review updated successfuly');;
    }

    public function destroy(string $id)
    {
        $review = Review::find($id);

        if (! $review) {
            return $this->badResponse([], "No Riview With id '{$id}'");
        }

        $review->delete();

        return $this->okResponse([], 'Review deleted successfuly');
    }
}
