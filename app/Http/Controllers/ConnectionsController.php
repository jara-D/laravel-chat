<?php

namespace App\Http\Controllers;

use App\Models\{
    Chat,
    Message,
    User
};
use Illuminate\{Contracts\View\View,
    Http\Request,
    Http\Response
};
use Exception;

class ConnectionsController extends Controller
{
    public function index(): View
    {
        error_log(auth()->id());

        $data = Chat::query()
            ->where('contact_id', auth()->id())
            ->where('approved', '==', 'false')
            ->join('users', 'users.id', '=', 'chats.user_id')
            ->get(['chats.id', 'name', 'chats.created_at']);
        return view('connect', ['data' => $data]);
    }

    public function connectUsers(Request $request): Response
    {
        // Validate the request
        $validatedData = $request->validate([
            'chat_id' => 'required|integer',
            'option' => 'required|boolean',
        ]);

        $chatId = $validatedData['chat_id'];
        $option = $validatedData['option'] ? 1 : 0;

        // Check if the current user has access to the chat
        $chat = Chat::query()
            ->where('id', $chatId)
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('contact_id', auth()->id());
            })
            ->first();

        if (!$chat) {
            return response('Chat not found or access denied', 404);
        }

        try {
            if ($option == 0) {
                // Delete related messages first
                Message::query()
                    ->where('chat_id', $chatId)
                    ->delete();

                // Then delete the chat
                $chat->delete();
            } else {
                // Update the chat approval status
                $chat->update(['approved' => $option]);
            }
            return response('Operation successful', 200);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return response('An error occurred', 500);
        }
    }

    public function request(Request $request): Response
    {
        // Validate the request
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        // Check if the chat already exists
        $existsChat = Chat::query()
            ->join('users', 'users.id', '=', 'chats.contact_id')
            ->where('users.email', $validatedData['email'])
            ->where('chats.user_id', auth()->id())
            ->first(['chats.*']);

        if ($existsChat) {
            return response('Already exists', 202);
        }

        $user = User::query()
            ->where('email', $validatedData['email'])
            ->first();

        if (!$user) {
            return response('User not found', 404);
        }

        $Contact_id = User::query()
            ->where('email', $validatedData['email'])
            ->first(['id']);

        $chat = [
            'user_id' => auth()->id(),
            'contact_id' => $Contact_id->id,
        ];
        Chat::query()
            ->create($chat);

        return response('', 200);
    }

}
