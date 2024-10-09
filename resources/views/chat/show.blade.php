<x-app-layout>
    <div class="grid" id="messageContainer"
         x-init="
    Echo.channel('Chat')
    .listen('messageSent',(e)=> {
    console.log(e)
    })
    ">
        @foreach($messages as $message)
            <div class="w-full mb-1
                    @if(auth()->id() == $message->sender_id)
                    grid place-items-end
                   @endif
                    ">
                <p class="text-amber-50 w-1/2 bg-gray-700 p-1.5  rounded">
                    <span class="text-amber-50 text-sm">{{$message->created_at}}</span>
                    <br>
                    {{$message->message}}
                </p>
            </div>
        @endforeach



        @include('chat.create')

    </div>
</x-app-layout>
