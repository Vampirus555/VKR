<?php

namespace App\Http\Controllers;

use App\Http\Library\ApiHelpers;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use ApiHelpers;

    public function index(): UserCollection
    {
        return new UserCollection(User::all());
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function store(Request $request): UserResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'surname' => 'required|string|max:45',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'role_id' => 'integer',
        ]);

        $user = new User($validated);
        $user->password = bcrypt($validated['password']);

        $role = Role::findOrFail($validated['role_id']);
        $user->role()->associate($role);

        if ($request->hasFile('image')) {
            $user->img_path = $request->file('image')->store('img/users' . $user->id, 'public');
        }

        $user->save();

        return new UserResource($user);
    }

    public function update(Request $request, User $user): UserResource
    {
        $validated = $request->validate([
            'name' => 'string|max:45',
            'surname' => 'string|max:45',
            'email' => 'email|unique:users,email',
            'password' => 'string|confirmed',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            
        ]);

        $authUser = $request->user();

        if (!$this->isAdmin($authUser) && $authUser->id != $user->id) {
            return response(['message' => 'You do not have permission to do this.'], 401);
        }

        if ($this->isAdmin($authUser) && $request->has('role_id')) {
            $validatedRole = Role::findOrFail($validated['role_id']);
            $user->role()->associate($validatedRole);
        }

        $user->update($validated);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($user->img_path);
            $user->img_path = $request->file('image')->store('img/users' . $user->id, 'public');
        }
        
        $user->save();

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        Storage::disk('public')->delete($user->img_path);

        return $user->delete();
    }
}
