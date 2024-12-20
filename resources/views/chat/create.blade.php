<form id="sentMessageForm" class="w-full h-10 bg-amber-100 fixed bottom-0 grid grid-cols-[9fr,1fr]" method="POST">
    @csrf
    <input placeholder="msg" class="border-0" name="message">
    <button type="submit">Sent</button>
</form>

<script>
    document.getElementById('sentMessageForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission
        const formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
            .catch((error) => {
                console.error('Error:', error);
                // Handle error
            });
    });
</script>
