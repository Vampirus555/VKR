<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewCollection;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Event;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(): ReviewCollection
    {    
        return new ReviewCollection(Review::all());
    }
    
    public function indexEventReview($id): ReviewCollection
    {    
        

        $reviews = Review::where('event_id', $id)->get();
       
        return new ReviewCollection($reviews);
    }

    public function show(Review $review): ReviewResource
    {
        return new ReviewResource($review);
    }

    public function store(Request $request): ReviewResource
    {
        $validated = $request->validate([
            'event_id' => 'integer',
            'rating' => 'integer',
            'text' => 'string',
        ]);

        $user = $request->user();
        $review = new Review($validated);
        $review->user()->associate($user);

        $event = Event::findOrFail($validated['event_id']);
        $review->event()->associate($event);

        $review->save();

        return new ReviewResource($review);
    }

    public function update(Request $request, Review $review): ReviewResource
    {
        $validated = $request->validate([
            'rating' => 'required|integer',
            'text' => 'required|text',
        ]);
        $user = $request->user();

        if ($user->id === $review->user_id) {
            $review->update($validated)->save();
            return new ReviewResource($review);
        } else {
            return response()->json(['error' => 'Вы не можете обновить этот отзыв.'], 403);
        }
    }

    public function destroy(Review $review)
    {
        return $note->delete();
    }
}
