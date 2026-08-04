{{--
    Reusable message thread partial. Include with:
    @include('admin.affiliates._message_thread', ['submission' => $submission, 'sendRoute' => route('affiliates.social-submissions.message', $submission)])

    Works for both admin and affiliate views since it just needs the
    submission + the correct route to post a reply to.
--}}
<div class="border rounded p-2 mt-2" style="max-height: 250px; overflow-y: auto; background: #f9f9f9;">
    @forelse ($submission->messages as $msg)
        <div class="mb-2 {{ $msg->sender_role === 'admin' ? 'text-end' : '' }}">
            <div class="d-inline-block px-2 py-1 rounded {{ $msg->sender_role === 'admin' ? 'bg-primary text-white' : 'bg-white border' }}" style="max-width: 80%;">
                <small class="d-block fw-bold">{{ $msg->sender->name }} ({{ ucfirst($msg->sender_role) }})</small>
                {{ $msg->message }}
                <small class="d-block text-muted" style="font-size: 10px;">{{ $msg->created_at->format('Y-m-d H:i') }}</small>
            </div>
        </div>
    @empty
        <p class="text-muted mb-0">No messages yet.</p>
    @endforelse
</div>

<form method="POST" action="{{ $sendRoute }}" class="d-flex gap-1 mt-2">
    @csrf
    <input type="text" name="message" class="form-control form-control-sm" placeholder="Type a message..." required>
    <button class="btn btn-sm btn-primary">Send</button>
</form>
