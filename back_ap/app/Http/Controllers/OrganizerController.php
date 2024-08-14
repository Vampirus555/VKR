<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\OrganizerCollection;
use App\Http\Resources\OrganizerResource;
use App\Models\Organizer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class OrganizerController extends Controller
{
    use ApiHelpers;

    public function index(): OrganizerCollection
    {
        return new OrganizerCollection(Organizer::all());
    }

    public function show(Organizer $organizer): OrganizerResource
    {
        return new OrganizerResource($organizer);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'organization' => 'required|string|max:65',
        ]);

        $user = User::findOrFail($validated['user_id']);
        if ($user->organizer()->exists()) {
            return response(['message' => 'Данный организатор мероприятий уже зарегистрирован.'], 409);
        }

        $organizer = new Organizer($validated);
        $organizer->user()->associate($user);
        $organizer->save();

        return new OrganizerResource($organizer);
    }

    public function update(Request $request, Organizer $organizer): OrganizerResource
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'organization' => 'required|string|max:65',
        ]);

        $user = $request->user();

        if (!$this->isAdmin($user)) {
            if ($user->id !== $organizer->user_id) {
                return response(['message' => 'You do not have permission to do this.'], 403);
            }
        } else {
            if ($request->has('user_id')) {
                $newUser = User::findOrFail($validated['user_id']);
                if ($newUser->organizer()->exists()) {
                    return response()->json(['message' => 'User is already associated.'], 409);
                }
                $organizer->user()->associate($newUser);
            }
        }

        $organizer->update($validated);
        $organizer->save();

        return new OrganizerResource($organizer);
    }

    public function destroy(Organizer $organizer)
    {

        return $organizer->delete();
    }
}
