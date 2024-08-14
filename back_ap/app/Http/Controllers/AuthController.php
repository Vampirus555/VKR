<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Faculty;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:45',
            'surname' => 'required|string|max:45',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'role_id' => 'integer',
        ]);
        $validated['email_verified_at'] = now(); 
        $user = new User($validated);
        $user->password = bcrypt($validated['password']); //шифрвоание полученного пароля с помощью хеш-функции

        $role = Role::findOrFail($validated['role_id']);
        $user->role()->associate($role);

        if ($request->hasFile('image')) {
            $user->img_path = $request->file('image')->store('img/users' . $user->id, 'public');
        }

        $user->save();
        
        $roleSlug = $role->slug;
        $token = $user->createToken('aptoken', [$roleSlug])->plainTextToken; // создание JWT-токена, в котором хранится рроль пользователя

        return response()->json([
            'user' => new UserResource($user),
            // 'token' => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        // Проверка введенного пароля
        if (!Hash::check($validated['password'], $user->password)) { 
            return response()->json(['message' => 'Неверный логин или пароль.'], 401);
        }

        //создание токена для текущей сессии
        $token = $user->createToken('aptoken', $user->role->pluck('slug')->toArray())->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            // 'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out.'], 200);
    }

    public function user(): UserResource
    {
        if(auth()->check()) {
            return new UserResource(auth()->user());
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
