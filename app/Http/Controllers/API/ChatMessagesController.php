<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Events\Message;
use App\Events\MessageDeleted;
use App\Models\Chat;
use App\Models\ChatMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatMessagesController extends Controller
{
    //
  
  
    public function index() {
        $user = Auth::user();
        $userId = $user->id;
    
        $chats = Chat::whereHas('chatMembers', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->get(); 
    
        return response()->json($chats);
    }
    public function markAsRead($id)
    {
        $chatMessage = ChatMessages::find($id);
        if (!$chatMessage) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        $chatMessage->read_at = now();
        $chatMessage->save();
        broadcast(new Message($chatMessage,'read'))->toOthers();
        return response()->json(['message' => 'Message marked as read']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'user_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]); 
        $chatMessage = ChatMessages::create([
            'chat_id' => $validated['chat_id'],
            'user_id' => $validated['user_id'],
            'content' => $validated['content'],
            'read_at' => null, 
        ]);
        broadcast(new Message($chatMessage,'create'))->toOthers();
        return response()->json(['message' => 'Message created successfully', 'chat_message' => $chatMessage], 201);
    }
    public function destroy($id)
{
    $chatMessage = ChatMessages::find($id);
    if (!$chatMessage) {
        return response()->json(['message' => 'Message not found'], 404);
    }

    $chatMessage->delete();
    broadcast(new MessageDeleted($id, $chatMessage->chat_id))->toOthers();
    return response()->json(['message' => 'Message deleted successfully']);
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'content' => 'required|string',
    ]);

    $chatMessage = ChatMessages::find($id);
    if (!$chatMessage) {
        return response()->json(['message' => 'Message not found'], 404);
    }

    $chatMessage->content = $validated['content'];
    $chatMessage->save();
    broadcast(new Message($chatMessage, 'update'))->toOthers();
    return response()->json(['message' => 'Message updated successfully', 'chat_message' => $chatMessage]);
}



}
