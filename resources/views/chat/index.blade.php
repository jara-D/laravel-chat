@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    <div class="chats">
        @foreach($chats as $chat)
            <a href="/chat/{{$chat->id}}" class="chat w-full text-white border-b-2 border-b-gray-700 grid">
                <b>{{ $chat->name  }}</b>
                <p>
                    @if($chat->message != null)
                        {{ Str::limit($chat->message, 30) }}
                    &#x2022; {{$chat->created_at}}
                    @else
                        Nothing sent
                   @endif
                </p>
            </a>
        @endforeach
    </div>
</x-app-layout>
