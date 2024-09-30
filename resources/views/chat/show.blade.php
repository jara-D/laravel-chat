<x-app-layout>
    <div class="grid" id="messageContainer">

        @foreach($messages as $message)
            <div class="w-full mb-10
            @if(auth()->id() == $message->sender_id)
            grid place-items-end
            @endif
            ">
                <span class="text-amber-50 text-sm p-1.5">{{$message->created_at}}</span>
                <p class="text-amber-50 w-1/2 bg-gray-700 p-1.5 m-1.5 rounded">
                    {{$message->message}}
                </p>
            </div>
        @endforeach


        @include('chat.create')

    </div>

</x-app-layout>
