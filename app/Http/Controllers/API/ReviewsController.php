<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Traits\APIResponses;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    use APIResponses;

    public function store(Request $request)
    {
        $reviewData = $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'required|string|min:1|max:500',
        ]);

        $user = $request->user();

        $reviewable = $user->client ?? $user->serviceProvider;
        $reviewable->reviews()->create($reviewData);

        $reviewable->rating = $reviewable->reviews()->avg('rating');
        $reviewable->save();


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
