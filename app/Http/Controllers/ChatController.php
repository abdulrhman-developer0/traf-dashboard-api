<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function getAllChats()
    {
        $chats = Chat::all();
        return response()->json($chats);
    }
    public function getChat($id)
    {
        $chat = Chat::find($id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }
        return response()->json($chat);
    }

    public function createChat(Request $request)
    {
        $chat = Chat::create();
        return response()->json(['message' => 'Chat created successfully', 'chat' => $chat], 201);
    }


    public function updateChat(Request $request, $id)
    {
        $chat = Chat::find($id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }

        $chat->save();
        return response()->json(['message' => 'Chat updated successfully', 'chat' => $chat]);
    }
    public function deleteChat($id)
    {
        $chat = Chat::find($id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }
        $chat->delete();
        return response()->json(['message' => 'Chat deleted successfully']);
    }
}
