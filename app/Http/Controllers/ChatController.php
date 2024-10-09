<?php

namespace App\Http\Controllers;

use App\Events\messageSent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Factory|Application
    {

        $chats = Chat::query()
            ->join('users as user1', 'user1.id', '=', 'chats.user_id')
            ->join('users as user2', 'user2.id', '=', 'chats.contact_id')
            ->where(function ($query) {
                $query->where('chats.user_id', '=', auth()->id())
                    ->orWhere('chats.contact_id', '=', auth()->id());
            })
            ->joinSub(
                DB::table('messages')
                    ->select('messages.*')
                    ->whereIn('messages.id', function ($query) {
                        $query->select(DB::raw('MAX(id)'))
                            ->from('messages')
                            ->groupBy('chat_id');
                    }),
                'latest_messages',
                'latest_messages.chat_id',
                'chats.id'
            )
            ->latest('latest_messages.created_at')
            ->get([
                'chats.id',
                DB::raw('IF(chats.user_id = ' . auth()->id() . ', user2.name, user1.name) as name'),
                'latest_messages.message',
                'latest_messages.created_at'
            ]);


        return view('chat.index', ['chats' => $chats]);
    }

    /**
     * handles the form for creating a new resource.
     */
    public function create(Request $request, Chat $chat)
    {
        $msg = $request->post('message');
        $chat_id = $chat->getAttribute('id');

        $message_id = Message::query()
            ->insertGetId([
                'chat_id' => $chat_id,
                'sender_id' => auth()->id(),
                'message' => $msg,
                'created_at' => now(),
                'updated_at' => now()
            ]);

        $message = Message::find($message_id);
        error_log($message);

        broadcast(new MessageSent($chat_id, User::find(auth()->id()), $message));


        // TODO don't do a full page load
        return redirect()->route('chat.show', ['chat' => $chat_id]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        return view('chat.show', ['chats' => $request]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        $userId = $chat->getAttribute('user_id');
        $contactId = $chat->getAttribute('contact_id');

        $authId = auth()->id();
        // authId should match either userId or contactId because that means its a participant  in the conversation

        if ($userId !== $authId && $contactId !== $authId) {
            error_log("Redirecting due to mismatch.");
            return redirect()->route('chat');
        }


        // get the messages with the chat_id from $chat
        $messages = Chat::query()
            ->join('messages', 'chats.id', '=', 'messages.chat_id')
            ->where('messages.chat_id', '=', $chat->getAttribute('id'))
            ->latest('messages.created_at')
            ->limit(10)
            ->get('*')
            ->reverse();

        $index = 0;
        foreach ($messages as $message) {
            $message->index = $index++;
        }
        error_log($chat);

        return view('chat.show', ['messages' => $messages, 'chat' => $chat]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chat $chat)
    {
//        return view('chat.edit', ['chat' => $chat]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        return 'destroy';
    }
}
