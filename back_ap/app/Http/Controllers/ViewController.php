<?php

namespace App\Http\Controllers;

use App\Http\Resources\ViewCollection;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function index(): ViewCollection
    {    
        return new ViewCollection(View::all());
    }

    public function show(View $view): ViewResource
    {
        return new ViewResource($view);
    }

    public function store(Request $request): ViewResource
    {
        $validated = $request->validate([
            'event_id' => 'integer',
        ]);

        $user = $request->user();
        $verifView = View::where('user_id', $user->id)->firstOrFail();

        if (!$verifView) {
            $view = View::create([
                'user_id' => $user->id,
                'event_id' => $validated['event_id'],
                'count_views' => 1
            ]);

            $event = Event::findOrFail($validated['event_id']);
            $view->event()->associate($event);

            $verifUser = User::findOrFail($user->id);
            $view->user()->associate($verifUser);
        } else {
            $view = $verifView->increment('count_views');
        }

        $view->save();
        return new ViewResource($view);
    }

   
    public function update(Request $request, View $view): ViewResource
    {
        $validated = $request->validate([
            'event_id' => 'integer',
            'count_views' => 'integer',
        ]);

        $view->update($validated);

        $validatedEventId = Event::whereIn('id', $validated['event_id'])->pluck('id');
        $view->event()->associate($validatedEventId);
        $view->save();

        return new ViewResource($note);
    }

    public function destroy(View $view)
    {
        return $view->delete();
    }
}
