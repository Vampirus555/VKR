<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\DelegateCollection;
use App\Http\Resources\DelegateResource;
use App\Models\Delegate;
use App\Models\User;
use App\Models\Park;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DelegateController extends Controller
{
    use ApiHelpers;

    public function index(): DelegateCollection
    {
        return new DelegateCollection(Delegate::all());
    }

    public function show(Delegate $delegate): DelegateResource
    {
        return new DelegateResource($delegate);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'park_id' => 'required|integer',
            'post' => 'required|string',
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($user->delegate()->exists()) {
            return response(['message' => 'Данный представитель парка уже зарегистрирован.'], 409);
        }
        
        $delegate = new Delegate($validated);
        $delegate->user()->associate($user);

        $park = Park::findOrFail($validated['park_id']);
        $delegate->park()->associate($park);

        $delegate->save();

        return new DelegateResource($delegate);
       
    }

    public function update(Request $request, Delegate $delegate): DelegateResource
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'park_id' => 'required|integer',
        ]);

        
        $user = $request->user();

        if (!$this->isAdmin($user)) {
            if ($user->id !== $delegate->user_id) {
                return response(['message' => 'You do not have permission to do this.'], 403);
            }
        } else {
            if ($request->has('user_id')) {
                $newUser = User::findOrFail($validated['user_id']);
                if ($newUser->delegate()->exists()) {
                    return response()->json(['message' => 'User is already associated.'], 409);
                }
                $delegate->user()->associate($newUser);
            }
        }

        $delegate->update($validated);

        if ($request->has('park_id')) {
            $park = Park::findOrFail($validated['park_id']);
            $delegate->park()->associate($park);
        }

     
        $delegate->save();

        return new DelegateResource($delegate);
    }

    public function destroy(Delegate $delegate)
    {
        return $delegate->delete();
    }
}
