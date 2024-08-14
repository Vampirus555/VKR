<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\DialogCollection;
use App\Http\Resources\DialogResource;
use App\Models\Dialog;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\Delegate;
use Illuminate\Http\Request;

class DialogController extends Controller
{
    use ApiHelpers;

    public function index(): DialogCollection
    {
        return new DialogCollection(Dialog::all());
    }

    public function indexByRole(Request $request): DialogCollection
    {
        $user = $request->user();
        if(isDelegate($user)){
            $delegate = Delegate::where('user_id', $user->id)->firstOrFail();
            $dialogs = Dialog::where('delegate_id', $delegate->id)->get();
        }
        elseif(isOrganizer($user)){
            $organizer = Organizer::where('user_id', $user->id)->firstOrFail();
            $dialogs = Dialog::where('organizer_id', $organizer->id)->get();
        }
        
        return new DialogCollection($dialogs);
    }

    public function show(Dialog $dialog): DialogResource
    {
        return new DialogResource($dialog);
    }

    public function store(Request $request): DialogResource
    {
        $validated = $request->validate([
            'organizer_id' => 'required|integer',
            'event_id' => 'required|integer',
        ]);


        $user = $request->user();

        if ($this->isDelegate($user)) {
            $delegate = Delegate::where('user_id', $user->id)->first();
        } 


        $dialog = new Dialog($validated);
        
        $dialog->delegate()->associate($delegate);

        $event = Event::findOrFail($validated['event_id']);
        $dialog->event()->associate($event);

        $organizer = Organizer::findOrFail($validated['organizer_id']);
        $dialog->organizer()->associate($organizer);

        $dialog->save();

        return new DialogResource($dialog);
    }

    public function update(Request $request, Dialog $dialog): DialogResource
    {
        $validated = $request->validate([
            'organizer_id' => 'required|integer',
            'event_id' => 'required|integer',
        ]);

        $user = $request->user();

        if ($this->isDelegate($user)) {
            $delegate = Delegate::where('user_id', $user->id)->first();
        } 


        $dialog->update($validated);
        $dialog->delegate()->associate($delegate);

        $event = Event::findOrFail($validated['event_id']);
        $dialog->event()->associate($event);

        $organizer = Organizer::findOrFail($validated['organizer_id']);
        $dialog->organizer()->associate($organizer);

        $dialog->save();

    }

    public function destroy(Dialog $dialog)
    {
        return $dialog->delete();
    }
}
