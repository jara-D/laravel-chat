<x-app-layout>
    <div class="grid justify-center">
        <form method="POST" class="w-full rounded m-5 p-1 flex flex-col items-center" id="ConnectionRequestForm">
            @csrf
            <div class="flex flex-col mb-4 w-full text-stone-50">
                <label for="email">Contact's Email</label>
                <div class="flex">
                    <input type="email" required name="email" id="InputConRequest" class="mr-2 h-12 text-black w-full">
                    <button type="submit" class="bg-blue-500 px-4 h-12">Add connection</button>
                </div>
                <span class="hidden" id="conRequestFeedback"></span>
            </div>
        </form>
        @foreach($data as $entry)
            <form class="w-full bg-cyan-600 rounded m-5 p-1 flex items-center justify-between" id="form-{{$entry->id}}">
                <span>{{$entry->name}}</span>
                <div class="flex space-x-2">
                    <button onclick="DenyOrApprove('0', {{$entry->id}}, this.closest('form'))">×</button>
                    <button onclick="DenyOrApprove('1', {{$entry->id}}, this.closest('form'))">✓</button>
                </div>
            </form>
        @endforeach
    </div>

</x-app-layout>

<script>
    function DenyOrApprove(option, id, formElement) {
        event.preventDefault();
        if (option !== "0" && option !== "1") {
            console.log("Invalid option");
            return;
        }

        let body = JSON.stringify({
            'option': option,
            'chat_id': id
        });

        fetch("./connect/approve", {
            method: 'POST',
            body: body,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
            .then(response => {
                if (response.ok) {
                    formElement.remove();
                } else {
                    console.error('Failed to update chat');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
            })
            .catch((error) => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    const ReqeustInput = document.getElementById("InputConRequest")
    const RequestFeedback = document.getElementById("conRequestFeedback")
    document.getElementById("ConnectionRequestForm").addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        console.log(formData);
        fetch("./connect/request", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
            .then(data => {
                console.log('Response data:', data["status"]);
                switch (data["status"]) {
                    case 404: {
                        ReqeustInput.classList.add("border-rose-500")
                        RequestFeedback.classList.remove("hidden")
                        RequestFeedback.textContent = "User not found"
                        break;
                    }
                    case 202: {
                        ReqeustInput.classList.add("border-rose-500")
                        RequestFeedback.classList.remove("hidden")
                        RequestFeedback.textContent = "Chat already exist or request already send"
                        break;
                    }
                    case 200: {
                        ReqeustInput.classList.add("border-lime-500")
                        RequestFeedback.classList.remove("hidden")
                        RequestFeedback.textContent = "Successfully sent request"
                        break
                    }
                    default: {
                        ReqeustInput.classList.add("border-rose-500")
                        RequestFeedback.classList.remove("hidden")
                        RequestFeedback.textContent = "Unknown error"
                        break;
                    }
                }

            })
            .catch((error) => {
                console.error('Error:', error);
                // Handle error
            });
    }, true);

</script>
