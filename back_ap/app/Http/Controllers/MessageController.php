<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(): MessageCollection
    {
        return new MessageCollection(Message::all());
    }

    public function indexMessagesForDialog(Request $request): MessageCollection
    {
        $dialogId = $request->input('dialog_id');
        $messages = Message::where('dialog_id', $dialogId)->get();
        
        return new MessageCollection($messages);
    }

    public function show(Message $message): MessageResource
    {
        return new MessageResource($message);
    }

    public function store(Request $request): MessageResource
    {
        $validated = $request->validate([
            'dialog_id' => 'required|integer',
            'text' => 'required|string|max:500',
        ]);


        $user = $request->user();
        $message = Message::create($validated);
        $message->user()->associate($user);

        $dialog = Dialog::findOrFail($validated['dialog_id']);
        $message->dialog()->associate($dialog);

        $message->save();

        return new MessageResource($message);
    }

    public function update(Request $request, Message $message): MessageResource
    {
        $validated = $request->validate([
            'text' => 'required|string|max:500',
        ]);

        $user = $request->user();
        if ($user->id === $message->user_id) {
            $message->update($validated)->save();
            return new MessageResource($message);
        } else {
            return response()->json(['error' => 'Вы не можете обновить этот отзыв.'], 403);
        }
    }

    public function destroy(Message $message)
    {
        return $message->delete();
    }
}
