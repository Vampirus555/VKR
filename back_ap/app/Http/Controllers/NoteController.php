<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteCollection;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\Event;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function index(): NoteCollection
    {
        return new NoteCollection(Note::all());
    }

    public function indexUserNote(Request $request): NoteCollection
    {
        $user = $request->user();
        $notes = Note::where('user_id', $user->id)->get();
    
        return new NoteCollection($notes);
    }

    public function show(Note $note): NoteResource
    {
        return new NoteResource($note);
    }

    public function store(Request $request): NoteResource
    {
        $validated = $request->validate([
            'event_id' => 'integer',
            'text' => 'required|string',
        ]);

        $user = $request->user();
    
        $note = new Note($validated);
        $note->user()->associate($user);

        $event = Event::findOrFail($validated['event_id']);
        $note->event()->associate($event);

        $note->save();

        return new NoteResource($note);
    }

    public function update(Request $request, $id): NoteResource
    {
        $note = Note::findOrFail($id);
        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        $note->update($validated);

        return new NoteResource($note);
    }

    public function destroy(Note $note)
    {
        return $note->delete();
    }
}
