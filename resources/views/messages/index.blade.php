@extends('layouts.app')

@section('content')
<div class="py-4">
    <style>
        .message-sidebar {
            min-height: 640px;
            border-radius: 1.5rem;
        }
        .conversation-card {
            border-radius: 1.5rem;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        .conversation-item {
            transition: background 0.15s ease;
        }
        .conversation-item:hover,
        .conversation-item.active {
            background: #eff2ff;
        }
        .messages-panel {
            min-height: 640px;
            border-radius: 1.5rem;
            border: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .messages-panel .card-body {
            display: flex;
            flex-direction: column;
            min-height: 640px;
            padding: 0;
        }
        .chat-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
        }
        .chat-body {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 1.5rem;
            background: #f7f9fc;
        }
        .chat-message {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
            align-items: flex-end;
        }
        .chat-message.sent {
            justify-content: flex-end;
        }
        .chat-message.received {
            justify-content: flex-start;
        }
        .chat-message .bubble {
            max-width: 70%;
            padding: 0.95rem 1rem;
            line-height: 1.5;
        }
        .chat-message.received .bubble {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 1.5rem 1.5rem 1.5rem 0.5rem;
        }
        .chat-message.sent .bubble {
            background: #2563eb;
            color: #fff;
            border-radius: 1.5rem 1.5rem 0.5rem 1.5rem;
        }
        .chat-message .avatar {
            width: 38px;
            height: 38px;
            min-width: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            font-size: 0.95rem;
        }
        .conversation-item::after {
            display: none !important;
        }
        .chat-input-area {
            border-top: 1px solid #e9ecef;
            padding: 1rem 1.25rem;
            background: #fff;
        }
        .chat-input {
            min-height: 100px;
            resize: none;
        }
        .message-switcher .nav-link {
            border-radius: 999px;
            font-size: 0.95rem;
        }
    </style>

    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="card bg-white conversation-card shadow-sm message-sidebar">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h5 class="mb-1">Messages</h5>
                            <p class="text-muted small mb-0">Recent chats and open conversations.</p>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input id="conversationSearch" class="form-control border-start-0" placeholder="Search in Messages..." type="search">
                    </div>

                    <div class="nav message-switcher nav-pills gap-2 mb-3" role="tablist">
                        <button class="nav-link active px-3 py-2" type="button">All</button>
                        <button class="nav-link px-3 py-2" type="button">Unread</button>
                        <button class="nav-link px-3 py-2" type="button">Spam</button>
                    </div>

                    @php $activeId = optional($activeConversation)->id; @endphp
                    <div class="list-group list-group-flush" id="conversationsList">
                        @forelse($conversations as $conversation)
                            @php $other = $conversation->other; $latest = $conversation->latest; @endphp
                            <a href="{{ route('messages.index', ['conversation' => $conversation->id]) }}" class="list-group-item list-group-item-action conversation-item d-flex align-items-start gap-3 py-3 px-0 {{ $conversation->id === $activeId ? 'active' : '' }}">
                                <div class="flex-shrink-0">
                                    @if(optional($other->studentProfile)->profile_photo)
                                        <img src="{{ asset('storage/' . $other->studentProfile->profile_photo) }}" class="rounded-circle" style="width:52px;height:52px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                                            <strong>{{ strtoupper(substr($other->name,0,1)) }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div class="fw-semibold">{{ $other->name }}</div>
                                        <div class="small text-muted">{{ optional($latest)->created_at ? optional($latest)->created_at->format('M j') : '' }}</div>
                                    </div>
                                    <div class="small text-muted text-truncate">{{ optional($latest)->message }}</div>
                                </div>
                                @if($conversation->unread)
                                    <div class="badge bg-primary rounded-pill align-self-start">{{ $conversation->unread }}</div>
                                @endif
                            </a>
                        @empty
                            <div class="py-4 text-center text-muted">
                                <div class="mb-3">
                                    <i class="bi bi-chat-left-dots fs-1"></i>
                                </div>
                                <div class="fw-semibold">No conversations yet</div>
                                <div class="small">Start chatting by opening a student profile.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card rounded-4 shadow-sm messages-panel bg-white">
                @if($activeConversation)
                    <div class="card-body p-0">
                        <div class="border-bottom chat-header px-4 py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                @if(optional($activeOther->studentProfile)->profile_photo)
                                    <img src="{{ asset('storage/' . $activeOther->studentProfile->profile_photo) }}" class="rounded-circle" style="width:56px;height:56px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                                        <strong>{{ strtoupper(substr($activeOther->name,0,1)) }}</strong>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold fs-5 mb-1">{{ $activeOther->name }}</div>
                                    <div class="small text-muted">{{ optional($activeOther->studentProfile)->department ?? 'Department not set' }} • {{ optional($activeOther->studentProfile)->hall ?? 'Hall not set' }}</div>
                                </div>
                            </div>
                            @if(isset($activeScore))
                                <div class="text-end small text-success">Compatibility {{ $activeScore }}%</div>
                            @endif
                        </div>

                        <div class="chat-body" id="messagesList">
                            @foreach($activeConversation->messages as $msg)
                                @php $isMe = $msg->sender_id === auth()->id(); @endphp
                                <div class="chat-message {{ $isMe ? 'sent' : 'received' }}">
                                    @if(!$isMe)
                                        <div class="avatar">{{ strtoupper(substr($activeOther->name,0,1)) }}</div>
                                    @endif
                                    <div>
                                        <div class="bubble">{!! nl2br(e($msg->message)) !!}</div>
                                        <div class="small text-muted mt-1 {{ $isMe ? 'text-end' : '' }}">{{ $msg->created_at->format('j M Y, H:i') }}</div>
                                    </div>
                                    @if($isMe)
                                        <div class="avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <form id="messageForm" method="POST" action="{{ route('messages.send') }}" class="chat-input-area">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                            <div class="row g-2">
                                <div class="col-12">
                                    <textarea id="messageText" name="message" class="form-control form-control-lg" rows="2" maxlength="1000" placeholder="Write a message..."></textarea>
                                </div>
                                <div class="col-12 d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">Send</button>
                                </div>
                                <div class="col-12 text-end">
                                    <span class="small text-muted" id="charCount">0 / 1000</span>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-chat-dots fs-1 text-primary"></i>
                        </div>
                        <h4 class="fw-semibold mb-2">Pick a chat to continue</h4>
                        <p class="text-muted mb-0">Click any user on the left to open the conversation here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('messageForm');
    const textarea = document.getElementById('messageText');
    const charCount = document.getElementById('charCount');
    const chatBody = document.getElementById('messagesList');

    const scrollChatToBottom = () => {
        if (chatBody) {
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    };

    if (textarea && charCount) {
        textarea.addEventListener('input', () => {
            charCount.textContent = textarea.value.length + ' / 1000';
        });
    }

    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (!textarea || !chatBody) return;

            const message = textarea.value.trim();
            if (!message) return;

            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
            }

            const token = document.querySelector('input[name="_token"]').value;
            const formData = new URLSearchParams(new FormData(form));

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                        'X-CSRF-TOKEN': token,
                    },
                    body: formData.toString(),
                });

                if (!response.ok) throw new Error('Request failed');
                const data = await response.json();

                const messageEl = document.createElement('div');
                messageEl.className = 'chat-message sent';
                messageEl.innerHTML = `
                    <div>
                        <div class="bubble">${data.message.replace(/\n/g, '<br>')}</div>
                        <div class="small text-muted mt-1 text-end">${data.created_at}</div>
                    </div>
                    <div class="avatar">${data.sender_initial}</div>
                `;

                chatBody.appendChild(messageEl);
                textarea.value = '';
                charCount.textContent = '0 / 1000';
                scrollChatToBottom();
            } catch (error) {
                console.error(error);
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
        });
    }

    scrollChatToBottom();
</script>
@endsection
