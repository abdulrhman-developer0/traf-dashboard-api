<?php

namespace App\Http\Controllers;

use App\Events\Message;
use App\Models\ChatMessages;
use Illuminate\Http\Request;

class ChatMessagesController extends Controller
{
    //
    public function getMessages($chat_id)
    {
        $messages = ChatMessages::where('chat_id', $chat_id)->with('user')->get();
        if ($messages->isEmpty()) {
            return response()->json(['message' => 'No messages found in this chat'], 404);
        }
        return response()->json($messages);
    }
    public function markAsRead($id)
    {
        $chatMessage = ChatMessages::find($id);
        if (!$chatMessage) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        $chatMessage->read_at = now();
        $chatMessage->save();

        return response()->json(['message' => 'Message marked as read']);
    }

    public function createMessage(Request $request)
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
            'read_at' => null, // default to unread
        ]);
       broadcast(new Message($chatMessage))->toOthers();
        return response()->json(['message' => 'Message created successfully', 'chat_message' => $chatMessage], 201);
    }
    public function deleteMessage($id)
{
    $chatMessage = ChatMessages::find($id);
    if (!$chatMessage) {
        return response()->json(['message' => 'Message not found'], 404);
    }

    $chatMessage->delete();
    return response()->json(['message' => 'Message deleted successfully']);
}
public function getUnreadMessages($chat_id, $user_id)
{
    $unreadMessages = ChatMessages::where('chat_id', $chat_id)
                                  ->where('user_id', '!=', $user_id)
                                  ->whereNull('read_at')
                                  ->get();

    return response()->json($unreadMessages);
}
public function updateMessage(Request $request, $id)
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

    return response()->json(['message' => 'Message updated successfully', 'chat_message' => $chatMessage]);
}



}
