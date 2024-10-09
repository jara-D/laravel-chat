<form class="w-full h-10 bg-amber-100 fixed bottom-0 grid grid-cols-[9fr,1fr]" method="POST">
    @csrf
    <input placeholder="msg" class="border-0" name="message">
    <button type="submit">Sent</button>
</form>