<x-app-layout>
    <div class="grid" id="messageContainer"
         style="margin-bottom: 40px; padding-bottom: 10px"
         x-init="
    Echo.private('Chat.{{$chat->id}}')
    .listen('messageSent', (e) => {
    createNewMessage(e);
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
    <script>
        function createNewMessage(e) {
            console.log(e);
            const newElement = document.createElement('div');
            newElement.classList.add('w-full', 'mb-1');
            if (e.User.id === {{ auth()->id() }}) {
                newElement.classList.add('grid', 'place-items-end');
            }
            newElement.innerHTML = `
                <p class="text-amber-50 w-1/2 bg-gray-700 p-1.5 rounded">
                    <span class="text-amber-50 text-sm">${e.message.created_at}</span>
                    <br>
                    ${e.message.msg}
                </p>
            `;
            document.getElementById('messageContainer').appendChild(newElement);
        }
    </script>
</x-app-layout>
