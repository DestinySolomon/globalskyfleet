{{-- resources/views/partials/live-chat-simple.blade.php --}}
<!-- Live Chat Button -->
<button type="button" class="btn btn-primary btn-floating live-chat-btn" id="liveChatBtn">
    <i class="ri-chat-3-line"></i>
    <span class="chat-notification-badge" style="display: none;"></span>
</button>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ri-chat-3-line me-2"></i>Live Chat Support
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="chat-container-simple d-flex flex-column" style="height: 500px;">
                    <!-- Chat Messages -->
                    <div class="chat-messages-simple flex-grow-1 p-3" id="chatMessagesSimple" style="overflow-y: auto;">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary mb-3" role="status"></div>
                            <p class="text-muted">Loading chat...</p>
                        </div>
                    </div>
                    
                    <!-- Chat Input -->
                    <div class="chat-input-simple p-3 border-top">
                        <form id="chatFormSimple" class="d-flex gap-2">
                            <input type="hidden" id="conversationIdSimple">
                            <input type="text" class="form-control" id="chatInputSimple" placeholder="Type your message..." 
                                   {{ !Auth::check() ? 'disabled' : '' }}>
                            <button type="submit" class="btn btn-primary" id="sendBtnSimple" {{ !Auth::check() ? 'disabled' : '' }}>
                                <i class="ri-send-plane-2-line"></i>
                            </button>
                        </form>
                        
                        @guest
                        <div class="alert alert-warning mt-2 mb-0 py-2">
                            <small>
                                <i class="ri-information-line me-1"></i>
                                Please <a href="{{ route('login') }}" class="alert-link">login</a> to use live chat.
                            </small>
                        </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Live Chat Button */
    .live-chat-btn {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        z-index: 1045;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        transition: all 0.3s ease;
    }
    
    .live-chat-btn:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }
    
    .chat-notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ef4444;
        color: white;
        font-size: 0.75rem;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Chat Messages */
    .message-simple {
        margin-bottom: 1rem;
        max-width: 80%;
    }
    
    .message-user-simple {
        margin-left: auto;
    }
    
    .message-admin-simple {
        margin-right: auto;
    }
    
    .message-bubble-simple {
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        position: relative;
    }
    
    .message-user-simple .message-bubble-simple {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
        border-bottom-right-radius: 0.25rem;
    }
    
    .message-admin-simple .message-bubble-simple {
        background: #f1f5f9;
        color: #1e293b;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 0.25rem;
    }
    
    .message-time-simple {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
    
    @media (max-width: 768px) {
        .live-chat-btn {
            bottom: 80px;
            right: 15px;
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
        
        .modal-dialog {
            margin: 0.5rem;
        }
        
        .chat-container-simple {
            height: 400px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatBtn = document.getElementById('liveChatBtn');
        const chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
        const chatMessages = document.getElementById('chatMessagesSimple');
        const chatForm = document.getElementById('chatFormSimple');
        const chatInput = document.getElementById('chatInputSimple');
        const sendBtn = document.getElementById('sendBtnSimple');
        const conversationId = document.getElementById('conversationIdSimple');
        const notificationBadge = document.querySelector('.chat-notification-badge');
        
        let currentConversation = null;
        let pusher = null;
        let channel = null;
        
        // Open chat modal when button is clicked
        chatBtn.addEventListener('click', function() {
            chatModal.show();
            initializeChat();
        });
        
        // Initialize chat
        async function initializeChat() {
            try {
                chatMessages.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p class="text-muted">Loading chat...</p>
                    </div>
                `;
                
                // Get or create conversation
                const response = await fetch('/chat/conversation', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: '{{ Auth::user()->name ?? "Guest" }}',
                        email: '{{ Auth::user()->email ?? "" }}'
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    currentConversation = data.conversation;
                    conversationId.value = currentConversation.id;
                    
                    // Display messages
                    displayMessages(data.messages);
                    
                    // Initialize Pusher for real-time updates
                    initializePusher();
                    
                    // Hide notification badge
                    hideNotification();
                }
                
            } catch (error) {
                console.error('Chat initialization error:', error);
                chatMessages.innerHTML = `
                    <div class="alert alert-danger m-3">
                        Failed to connect to chat. Please try again.
                    </div>
                `;
            }
        }
        
        // Initialize Pusher
        function initializePusher() {
            if (!window.PUSHER_APP_KEY || !currentConversation) return;
            
            // Disconnect existing Pusher connection
            if (pusher) {
                pusher.disconnect();
            }
            
            // Initialize Pusher
            pusher = new Pusher(window.PUSHER_APP_KEY, {
                cluster: window.PUSHER_APP_CLUSTER,
                encrypted: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }
            });
            
            // Subscribe to private channel
            channel = pusher.subscribe('private-chat.' + currentConversation.id);
            
            // Listen for new messages
            channel.bind('message.sent', function(data) {
                addMessage({
                    message: data.message,
                    sender_type: data.sender_type,
                    sender_name: data.sender_name,
                    is_admin: data.is_admin,
                    created_at: data.created_at
                });
                
                // Scroll to bottom
                scrollToBottom();
                
                // Show notification if chat is closed
                if (!document.querySelector('#chatModal').classList.contains('show')) {
                    showNotification();
                }
            });
        }
        
        // Display messages
        function displayMessages(messages) {
            chatMessages.innerHTML = '';
            
            if (messages.length === 0) {
                chatMessages.innerHTML = `
                    <div class="text-center py-5">
                        <i class="ri-chat-3-line text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="text-muted">No messages yet. Start the conversation!</p>
                    </div>
                `;
                return;
            }
            
            messages.forEach(message => {
                addMessage(message);
            });
            
            scrollToBottom();
        }
        
        // Add a single message
        function addMessage(message) {
            const messageClass = message.is_admin ? 'message-admin-simple' : 'message-user-simple';
            const time = formatTime(message.created_at);
            
            const messageElement = document.createElement('div');
            messageElement.className = `message-simple ${messageClass}`;
            messageElement.innerHTML = `
                <div class="message-bubble-simple">
                    ${message.message}
                </div>
                <div class="message-time-simple">
                    ${message.sender_name} • ${time}
                </div>
            `;
            
            chatMessages.appendChild(messageElement);
        }
        
        // Send message
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = chatInput.value.trim();
            if (!message || !currentConversation) return;
            
            // Disable input while sending
            chatInput.disabled = true;
            sendBtn.disabled = true;
            
            try {
                const response = await fetch('/chat/message/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        conversation_id: currentConversation.id,
                        message: message
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    chatInput.value = '';
                    // Message will be added via Pusher event
                }
                
            } catch (error) {
                console.error('Send message error:', error);
                showAlert('Failed to send message', 'danger');
            } finally {
                chatInput.disabled = false;
                sendBtn.disabled = false;
                chatInput.focus();
            }
        });
        
        // Show notification badge
        function showNotification() {
            if (notificationBadge) {
                notificationBadge.style.display = 'flex';
                notificationBadge.textContent = '1';
            }
        }
        
        // Hide notification badge
        function hideNotification() {
            if (notificationBadge) {
                notificationBadge.style.display = 'none';
            }
        }
        
        // Format time
        function formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
        
        // Scroll to bottom
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Show alert
        function showAlert(message, type = 'info') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show m-3`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            chatMessages.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
        
        // Auto focus input when modal opens
        document.getElementById('chatModal').addEventListener('shown.bs.modal', function() {
            if (!chatInput.disabled) {
                chatInput.focus();
            }
        });
        
        // Clean up Pusher when modal closes
        document.getElementById('chatModal').addEventListener('hidden.bs.modal', function() {
            if (pusher) {
                pusher.disconnect();
                pusher = null;
                channel = null;
            }
        });
    });
</script>