<?php

namespace App\Http\Controllers;

use App\Models\ChatMember;
use Illuminate\Http\Request;

class ChatMemberController extends Controller
{
    //
    public function getMembers($chat_id)
    {
        $members = ChatMember::where('chat_id', $chat_id)->get();
        if ($members->isEmpty()) {
            return response()->json(['message' => 'No members found for this chat'], 404);
        }
        return response()->json($members);
    }
    public function getChatsByUser($user_id)
    {
        $chats = ChatMember::where('user_id', $user_id)->with('chat')->get();
        if ($chats->isEmpty()) {
            return response()->json(['message' => 'No chats found for this user'], 404);
        }
        return response()->json($chats);
    }
    public function addMember(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $chatMember = ChatMember::create([
            'chat_id' => $request->chat_id,
            'user_id' => $request->user_id,
        ]);

        return response()->json(['message' => 'Member added to chat successfully', 'chat_member' => $chatMember], 201);
    }
    public function removeMember($id)
    {
        $chatMember = ChatMember::find($id);
        if (!$chatMember) {
            return response()->json(['message' => 'Chat member not found'], 404);
        }
        $chatMember->delete();
        return response()->json(['message' => 'Member removed from chat successfully']);
    }
    public function isMember(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $exists = ChatMember::where('chat_id', $request->chat_id)
            ->where('user_id', $request->user_id)
            ->exists();

        return response()->json(['is_member' => $exists]);
    }
}
