@extends('layouts.app')

@section('content')
<div class="py-4">
    <style>
        /* Message Sidebar Styling */
        .message-sidebar {
            height: 700px;
            background: #ffffff;
            border-radius: 20px !important;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .conversation-card {
            border: none !important;
        }
        .conversation-card .card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 1.25rem;
        }
        .conversation-list-container {
            flex-grow: 1;
            overflow-y: auto;
            margin-right: -8px;
            padding-right: 8px;
        }
        .conversation-item {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 14px !important;
            margin-bottom: 0.35rem;
            padding: 12px 14px !important;
            border: none !important;
            background: transparent;
        }
        .conversation-item:hover {
            background: #f1f5f9 !important;
        }
        .conversation-item.active {
            background: #eff6ff !important;
            color: #1d4ed8 !important;
        }
        .conversation-item.active .fw-semibold {
            color: #1e3a8a !important;
        }
        .conversation-item.active .text-muted {
            color: #3b82f6 !important;
        }
        
        /* Search bar styling */
        .search-wrapper {
            position: relative;
            background: #f1f5f9;
            border-radius: 14px;
            padding: 2px 8px;
            display: flex;
            align-items: center;
            border: 1px solid transparent;
            transition: all 0.2s;
        }
        .search-wrapper:focus-within {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .search-input {
            border: none;
            background: transparent;
            outline: none;
            padding: 8px 6px;
            font-size: 0.95rem;
            width: 100%;
        }

        /* Message Switcher Pills */
        .message-switcher button {
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 6px 16px !important;
            border: 1px solid rgba(226, 232, 240, 0.8);
            background: #ffffff;
            color: #64748b;
            transition: all 0.2s;
        }
        .message-switcher button:hover {
            background: #f8fafc;
            color: #334155;
        }
        .message-switcher button.active {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        /* Messages Panel */
        .messages-panel {
            height: 700px;
            border-radius: 20px !important;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #ffffff;
        }
        .messages-panel .card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 0;
        }
        .chat-header {
            border-bottom: 1px solid rgba(241, 245, 249, 0.9);
            padding: 1rem 1.5rem;
            background: #ffffff;
            z-index: 10;
        }
        .chat-body {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 1.5rem;
            background: #f8fafc;
            background-image: radial-gradient(rgba(203, 213, 225, 0.25) 1px, transparent 0);
            background-size: 20px 20px;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* Telegram & Messenger style chat bubbles */
        .chat-message {
            display: flex;
            width: 100%;
            margin-bottom: 0.4rem;
            align-items: flex-end;
        }
        .chat-message.sent {
            justify-content: flex-end;
        }
        .chat-message.received {
            justify-content: flex-start;
        }
        .chat-message .bubble-wrapper {
            max-width: 65%;
            display: flex;
            flex-direction: column;
        }
        .chat-message.sent .bubble-wrapper {
            align-items: flex-end;
        }
        .chat-message.received .bubble-wrapper {
            align-items: flex-start;
        }
        .chat-message .bubble {
            padding: 0.7rem 1.1rem;
            font-size: 0.95rem;
            line-height: 1.5;
            position: relative;
        }
        .chat-message.received .bubble {
            background: #ffffff;
            color: #1e293b;
            border-radius: 18px 18px 18px 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .chat-message.sent .bubble {
            background: linear-gradient(135deg, #0084ff 0%, #0072ff 100%);
            color: #ffffff;
            border-radius: 18px 18px 4px 18px;
            box-shadow: 0 3px 10px rgba(0, 114, 255, 0.15);
        }
        .chat-message .meta {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 3px;
            padding: 0 4px;
        }
        .chat-message.sent .meta {
            justify-content: flex-end;
        }
        .chat-message.received .meta {
            justify-content: flex-start;
        }
        .chat-message .avatar {
            width: 32px;
            height: 32px;
            min-width: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e2e8f0;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 700;
            margin-right: 8px;
            margin-bottom: 2px;
        }

        /* Modern Input Bar */
        .chat-input-area {
            border-top: 1px solid rgba(241, 245, 249, 0.9);
            padding: 1rem 1.25rem;
            background: #ffffff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .chat-input-wrapper {
            flex-grow: 1;
            position: relative;
            background: #f1f5f9;
            border-radius: 24px;
            padding: 4px 16px;
            display: flex;
            align-items: center;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }
        .chat-input-wrapper:focus-within {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .chat-input-field {
            flex-grow: 1;
            border: none;
            background: transparent;
            outline: none;
            padding: 8px 0;
            font-size: 0.95rem;
            color: #1e293b;
            resize: none;
            height: 38px;
            max-height: 120px;
            line-height: 1.4;
        }
        .chat-input-field::placeholder {
            color: #94a3b8;
        }
        .chat-action-btn {
            background: transparent;
            border: none;
            color: #64748b;
            font-size: 1.3rem;
            padding: 4px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .chat-action-btn:hover {
            color: #1e293b;
            transform: scale(1.08);
        }
        .chat-send-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #0084ff;
            color: #ffffff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 132, 255, 0.15);
            font-size: 1.05rem;
        }
        .chat-send-btn:hover {
            background: #0072ff;
            transform: scale(1.08);
        }
        .chat-send-btn:active {
            transform: scale(0.92);
        }
        .chat-send-btn:disabled {
            background: #cbd5e1;
            color: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }
    </style>

    <div class="row gy-4">
        <!-- Conversations List Panel -->
        <div class="col-lg-4">
            <div class="card bg-white conversation-card shadow-sm message-sidebar">
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="fw-bold mb-1">Chats</h4>
                        <p class="text-muted small mb-0">Connect with your prospective roommates</p>
                    </div>

                    <!-- Modern Search Box -->
                    <div class="search-wrapper mb-3">
                        <i class="bi bi-search text-muted ms-1" style="font-size: 0.95rem;"></i>
                        <input id="conversationSearch" class="search-input" placeholder="Search in messages..." type="search">
                    </div>

                    <!-- Pills Segmented Switcher -->
                    <div class="nav message-switcher nav-pills gap-2 mb-3" role="tablist">
                        <button class="nav-link active" type="button">All</button>
                        <button class="nav-link" type="button">Unread</button>
                        <button class="nav-link" type="button">Spam</button>
                    </div>

                    <!-- Scrollable Conversations List -->
                    <div class="conversation-list-container">
                        @php $activeId = optional($activeConversation)->id; @endphp
                        <div class="list-group list-group-flush" id="conversationsList">
                            @forelse($conversations as $conversation)
                                @php $other = $conversation->other; $latest = $conversation->latest; @endphp
                                <a href="{{ route('messages.index', ['conversation' => $conversation->id]) }}" class="list-group-item list-group-item-action conversation-item d-flex align-items-center gap-3 py-3 px-3 {{ $conversation->id === $activeId ? 'active' : '' }}">
                                    <div class="flex-shrink-0 position-relative">
                                        @if(optional($other->studentProfile)->profile_photo)
                                            <img src="{{ asset('storage/' . $other->studentProfile->profile_photo) }}" class="rounded-circle" style="width:50px;height:50px;object-fit:cover;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:50px;height:50px;font-size:1.2rem;">
                                                {{ strtoupper(substr($other->name,0,1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <div class="fw-semibold text-truncate text-dark">{{ $other->name }}</div>
                                            <div class="small text-muted" style="font-size: 0.75rem;">{{ optional($latest)->created_at ? optional($latest)->created_at->format('M j') : '' }}</div>
                                        </div>
                                        <div class="small text-muted text-truncate">{{ optional($latest)->message }}</div>
                                    </div>
                                    @if($conversation->unread)
                                        <span class="badge bg-primary rounded-pill align-self-center px-2 py-1" style="font-size: 0.75rem;">{{ $conversation->unread }}</span>
                                    @endif
                                </a>
                            @empty
                                <div class="py-5 text-center text-muted">
                                    <div class="mb-3">
                                        <i class="bi bi-chat-left-dots fs-1 opacity-50"></i>
                                    </div>
                                    <div class="fw-semibold">No conversations yet</div>
                                    <div class="small mt-1">Visit a student profile to start a chat.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Stream Panel -->
        <div class="col-lg-8">
            <div class="card rounded-4 shadow-sm messages-panel bg-white">
                @if($activeConversation)
                    <div class="card-body p-0">
                        <!-- Chat Header -->
                        <div class="chat-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                @if(optional($activeOther->studentProfile)->profile_photo)
                                    <img src="{{ asset('storage/' . $activeOther->studentProfile->profile_photo) }}" class="rounded-circle" style="width:50px;height:50px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:50px;height:50px;font-size:1.2rem;">
                                        {{ strtoupper(substr($activeOther->name,0,1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold text-dark fs-5">{{ $activeOther->name }}</div>
                                    <div class="small text-muted">{{ optional($activeOther->studentProfile)->department ?? 'Department not set' }} • {{ optional($activeOther->studentProfile)->hall ?? 'Hall not set' }}</div>
                                </div>
                            </div>
                            @if(isset($activeScore))
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-semibold">
                                    <i class="bi bi-heart-fill me-1 small"></i> Compatibility {{ $activeScore }}%
                                </span>
                            @endif
                        </div>

                        <!-- Chat Messages List -->
                        <div class="chat-body" id="messagesList">
                            @foreach($activeConversation->messages as $msg)
                                @php $isMe = $msg->sender_id === auth()->id(); @endphp
                                <div class="chat-message {{ $isMe ? 'sent' : 'received' }}">
                                    @if(!$isMe)
                                        @if(optional($activeOther->studentProfile)->profile_photo)
                                            <img src="{{ asset('storage/' . $activeOther->studentProfile->profile_photo) }}" class="rounded-circle me-2 align-self-end" style="width:30px;height:30px;object-fit:cover;margin-bottom:2px;">
                                        @else
                                            <div class="avatar align-self-end">{{ strtoupper(substr($activeOther->name,0,1)) }}</div>
                                        @endif
                                    @endif
                                    <div class="bubble-wrapper">
                                        @if($msg->image)
                                            <div class="mb-1">
                                                <img src="{{ asset('storage/' . $msg->image) }}" class="rounded-3 border border-secondary border-opacity-10 shadow-sm" style="max-width: 280px; max-height: 200px; object-fit: cover; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                                            </div>
                                        @endif
                                        @if($msg->message)
                                            <div class="bubble">{!! nl2br(e($msg->message)) !!}</div>
                                        @endif
                                        <div class="meta">
                                            <span>{{ $msg->created_at->format('h:i A') }}</span>
                                            @if($isMe)
                                                <i class="bi bi-check2-all text-primary" style="font-size: 0.95rem;"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Chat Input Form -->
                        <form id="messageForm" method="POST" action="{{ route('messages.send') }}" class="chat-input-area flex-column align-items-stretch" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                            <input type="file" id="imageInput" name="image" accept="image/*" class="d-none">

                            <!-- Image Preview Container inside form -->
                            <div id="imagePreviewContainer" class="d-none mb-2 p-2 bg-light rounded-3 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative">
                                        <img id="imagePreview" src="" class="rounded-3 border border-secondary border-opacity-10" style="width: 50px; height: 50px; object-fit: cover;">
                                        <button type="button" id="btnCancelImage" class="btn btn-danger btn-sm rounded-circle position-absolute" style="top: -6px; right: -6px; width: 18px; height: 18px; padding: 0; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <span class="small text-muted text-truncate" id="imageName" style="max-width: 200px;"></span>
                                </div>
                                <span class="small text-primary fw-semibold me-2">Ready to send</span>
                            </div>

                            <div class="d-flex align-items-center gap-2 w-100">
                                <!-- Attachment Icon placeholder -->
                                <button type="button" class="chat-action-btn" title="Add attachment">
                                    <i class="bi bi-plus-circle" style="font-size: 1.35rem; color: #0084ff;"></i>
                                </button>
                                
                                <!-- Image Icon -->
                                <button type="button" id="btnTriggerImage" class="chat-action-btn" title="Send image">
                                    <i class="bi bi-image" style="font-size: 1.35rem;"></i>
                                </button>

                                <!-- Modern Text Input Container -->
                                <div class="chat-input-wrapper">
                                    <textarea id="messageText" name="message" class="chat-input-field" rows="1" maxlength="1000" placeholder="Write a message..." style="height: 38px; overflow-y: hidden;"></textarea>
                                    
                                    <!-- Emoji Button placeholder -->
                                    <button type="button" class="chat-action-btn ms-2" title="Emoji">
                                        <i class="bi bi-emoji-smile" style="font-size: 1.25rem;"></i>
                                    </button>
                                </div>

                                <!-- Circle Send Button -->
                                <button type="submit" id="submitBtn" class="chat-send-btn" title="Send message" disabled>
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- Empty Chat Slate -->
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                        <div class="mb-4 bg-primary-glow p-4 rounded-circle">
                            <i class="bi bi-chat-dots-fill fs-1 text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-2">Pick a chat to continue</h4>
                        <p class="text-muted mb-0">Select any conversation from the list to start messaging.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('messageForm');
        const textarea = document.getElementById('messageText');
        const submitBtn = document.getElementById('submitBtn');
        const chatBody = document.getElementById('messagesList');
        const searchInput = document.getElementById('conversationSearch');
        const switcherBtns = document.querySelectorAll('.message-switcher button');

        const imageInput = document.getElementById('imageInput');
        const btnTriggerImage = document.getElementById('btnTriggerImage');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const btnCancelImage = document.getElementById('btnCancelImage');
        const imageName = document.getElementById('imageName');

        const scrollChatToBottom = () => {
            if (chatBody) {
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        };

        const updateSendButtonState = () => {
            if (!submitBtn || !textarea) return;
            const hasText = textarea.value.trim().length > 0;
            const hasImage = imageInput && imageInput.files && imageInput.files.length > 0;
            submitBtn.disabled = !((hasText || hasImage));
        };

        // Textarea height auto-expansion & Send button toggling
        if (textarea) {
            textarea.addEventListener('input', () => {
                updateSendButtonState();

                textarea.style.height = '38px';
                const newHeight = Math.min(textarea.scrollHeight, 120);
                textarea.style.height = `${newHeight}px`;

                if (textarea.scrollHeight > 120) {
                    textarea.style.overflowY = 'auto';
                } else {
                    textarea.style.overflowY = 'hidden';
                }
            });

            // Enter key submits the message
            textarea.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const hasText = textarea.value.trim().length > 0;
                    const hasImage = imageInput && imageInput.files && imageInput.files.length > 0;
                    if (hasText || hasImage) {
                        form.dispatchEvent(new Event('submit', { cancelable: true }));
                    }
                }
            });
        }

        // Image input trigger
        if (btnTriggerImage && imageInput) {
            btnTriggerImage.addEventListener('click', () => {
                imageInput.click();
            });

            imageInput.addEventListener('change', () => {
                if (imageInput.files && imageInput.files[0]) {
                    const file = imageInput.files[0];
                    if (imageName) imageName.textContent = file.name;
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        if (imagePreview) imagePreview.src = e.target.result;
                        if (imagePreviewContainer) imagePreviewContainer.classList.remove('d-none');
                        updateSendButtonState();
                    };
                    reader.readAsDataURL(file);
                } else {
                    if (imagePreviewContainer) imagePreviewContainer.classList.add('d-none');
                    updateSendButtonState();
                }
            });
        }

        // Cancel Image
        if (btnCancelImage && imageInput) {
            btnCancelImage.addEventListener('click', () => {
                imageInput.value = '';
                if (imagePreviewContainer) imagePreviewContainer.classList.add('d-none');
                if (imagePreview) imagePreview.src = '';
                if (imageName) imageName.textContent = '';
                updateSendButtonState();
            });
        }

        // Live Sidebar Filter
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase().trim();
                const items = document.querySelectorAll('.conversation-item');
                items.forEach(item => {
                    const nameEl = item.querySelector('.fw-semibold');
                    if (nameEl) {
                        const name = nameEl.textContent.toLowerCase();
                        if (name.includes(query)) {
                            item.style.setProperty('display', 'flex', 'important');
                        } else {
                            item.style.setProperty('display', 'none', 'important');
                        }
                    }
                });
            });
        }

        // Switcher Tabs Live Filter (All / Unread / Spam)
        switcherBtns.forEach((btn, index) => {
            btn.addEventListener('click', () => {
                switcherBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const items = document.querySelectorAll('.conversation-item');
                items.forEach(item => {
                    const hasUnread = item.querySelector('.badge') !== null;
                    if (index === 0) { // All
                        item.style.setProperty('display', 'flex', 'important');
                    } else if (index === 1) { // Unread
                        if (hasUnread) {
                            item.style.setProperty('display', 'flex', 'important');
                        } else {
                            item.style.setProperty('display', 'none', 'important');
                        }
                    } else if (index === 2) { // Spam (Placeholders)
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        });

        // AJAX Form Submission
        if (form) {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                if (!textarea || !chatBody) return;

                const message = textarea.value.trim();
                const hasImage = imageInput && imageInput.files && imageInput.files.length > 0;
                if (!message && !hasImage) return;

                if (submitBtn) {
                    submitBtn.disabled = true;
                }

                const token = document.querySelector('input[name="_token"]').value;
                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: formData,
                    });

                    if (!response.ok) throw new Error('Request failed');
                    const data = await response.json();

                    // Create sent bubble
                    const messageEl = document.createElement('div');
                    messageEl.className = 'chat-message sent';
                    
                    let bubbleContent = '';
                    if (data.image) {
                        bubbleContent += `
                            <div class="mb-1">
                                <img src="${data.image}" class="rounded-3 border border-secondary border-opacity-10 shadow-sm" style="max-width: 280px; max-height: 200px; object-fit: cover; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                            </div>
                        `;
                    }
                    if (data.message) {
                        bubbleContent += `<div class="bubble">${data.message.replace(/\n/g, '<br>')}</div>`;
                    }

                    messageEl.innerHTML = `
                        <div class="bubble-wrapper">
                            ${bubbleContent}
                            <div class="meta">
                                <span>${data.created_at}</span>
                                <i class="bi bi-check2-all text-primary" style="font-size: 0.95rem;"></i>
                            </div>
                        </div>
                    `;

                    chatBody.appendChild(messageEl);
                    
                    // Reset input field state
                    textarea.value = '';
                    textarea.style.height = '38px';
                    textarea.style.overflowY = 'hidden';
                    
                    if (imageInput) imageInput.value = '';
                    if (imagePreviewContainer) imagePreviewContainer.classList.add('d-none');
                    if (imagePreview) imagePreview.src = '';
                    if (imageName) imageName.textContent = '';
                    
                    submitBtn.disabled = true;
                    scrollChatToBottom();
                } catch (error) {
                    console.error(error);
                } finally {
                    updateSendButtonState();
                }
            });
        }

        scrollChatToBottom();
    });
</script>
@endsection
