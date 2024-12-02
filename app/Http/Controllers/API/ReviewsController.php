<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use App\Traits\APIResponses;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    use APIResponses;

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
            $booking->client_id != $account?->id || $booking->service->service_provider_id != $account?->id
        ) {
            return $this->badResponse([], "You not have a booking with id {$request->booking_id}");
        }

        $booking->reviews()->create($reviewData);

        $account->update([
            'rating'  => $account->reviews()->avg('rating')
        ]);

        $booking->service->update([
            'rating'  => $booking->service->reviews()->avg('rating')
        ]);


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
