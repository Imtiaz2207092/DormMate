@extends('layouts.app')

@section('content')
<div class="py-4">
    <style>
        .chat-header {
            border-bottom: 1px solid #e9ecef;
        }
        .chat-window {
            background: #f4f6fb;
            border-radius: 1.5rem;
            min-height: 620px;
            display: flex;
            flex-direction: column;
        }
        .chat-body {
            flex: 1 1 auto;
            padding: 1.5rem;
            overflow-y: auto;
        }
        .chat-message {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .chat-message.sent {
            justify-content: flex-end;
        }
        .chat-message .bubble {
            max-width: 68%;
            padding: 0.95rem 1rem;
            border-radius: 1.25rem;
            line-height: 1.5;
        }
        .chat-message.received .bubble {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-bottom-left-radius: 0.25rem;
        }
        .chat-message.sent .bubble {
            background: #2563eb;
            color: #fff;
            border-bottom-right-radius: 0.25rem;
        }
        .chat-input-area {
            background: #fff;
            border-top: 1px solid #e9ecef;
            padding: 1rem 1.25rem;
            border-bottom-left-radius: 1.5rem;
            border-bottom-right-radius: 1.5rem;
        }
    </style>

    <div class="card rounded-4 shadow-sm chat-window overflow-hidden">
        <div class="card-body chat-header d-flex align-items-center justify-content-between py-3 px-4">
            <div class="d-flex align-items-center gap-3">
                @if(optional($other->studentProfile)->profile_photo)
                    <img src="{{ asset('storage/' . $other->studentProfile->profile_photo) }}" class="rounded-circle" style="width:60px;height:60px;object-fit:cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                        <strong>{{ strtoupper(substr($other->name,0,1)) }}</strong>
                    </div>
                @endif
                <div>
                    <div class="fw-semibold fs-5 mb-1">{{ $other->name }}</div>
                    <div class="small text-muted">{{ optional($other->studentProfile)->department ?? 'Department not set' }} • {{ optional($other->studentProfile)->hall ?? 'Hall not set' }}</div>
                </div>
            </div>
            <div class="text-end">
                @if(isset($score))
                    <div class="fw-semibold text-success">Compatibility {{ $score }}%</div>
                @endif
            </div>
        </div>

        <div class="chat-body" id="messagesList">
            @foreach($conversation->messages as $msg)
                @php $isMe = $msg->sender_id === auth()->id(); @endphp
                <div class="chat-message {{ $isMe ? 'sent' : 'received' }}">
                    @if(!$isMe)
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
                            <strong>{{ strtoupper(substr($other->name,0,1)) }}</strong>
                        </div>
                    @endif
                    <div>
                        <div class="bubble">{!! nl2br(e($msg->message)) !!}</div>
                        <div class="small text-muted mt-1 {{ $isMe ? 'text-end' : '' }}">{{ $msg->created_at->format('j M Y, H:i') }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('messages.send') }}" class="chat-input-area">
            @csrf
            <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
            <div class="row g-2 align-items-end">
                <div class="col">
                    <textarea id="messageText" name="message" class="form-control form-control-lg" rows="2" maxlength="1000" placeholder="Write a message..."></textarea>
                </div>
                <div class="col-auto d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Send</button>
                </div>
                <div class="col-12 text-end">
                    <span class="small text-muted" id="charCount">0 / 1000</span>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const messagesList = document.getElementById('messagesList');
    if (messagesList) messagesList.scrollTop = messagesList.scrollHeight;

    const textarea = document.getElementById('messageText');
    const charCount = document.getElementById('charCount');
    if (textarea) {
        textarea.addEventListener('input', () => {
            charCount.textContent = textarea.value.length + ' / 1000';
        });

        textarea.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.form.submit();
            }
        });
    }
</script>

@endsection
