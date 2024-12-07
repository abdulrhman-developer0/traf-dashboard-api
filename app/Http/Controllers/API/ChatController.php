<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Events\Message;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    //
    ////
  
    public function index()
    {
        $user=Auth::user();
        $userId = $user->id; 

        if (!$userId) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $chats = Chat::where('user_id', $userId)->get();
    
        if ($chats->isEmpty()) {
            return response()->json(['message' => 'No chats found for the current user'], 404);
        }
    
        return response()->json($chats, 200);
    }
    public function show($id)
    {
        $chat = Chat::find($id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }
        return response()->json($chat);
    }

    public function store(Request $request)
    {
        
        $chat = Chat::create();
        return response()->json(['message' => 'Chat created successfully', 'chat' => $chat], 201);
    }


    public function update(Request $request, $id)
    {
        $chat = Chat::find($id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }

        $chat->save();
        return response()->json(['message' => 'Chat updated successfully', 'chat' => $chat]);
    }
    public function destroy($id)
    {
        $chat = Chat::find($id);
        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }
        $chat->delete();
        return response()->json(['message' => 'Chat deleted successfully']);
    }
}
