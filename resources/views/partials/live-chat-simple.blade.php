{{-- resources/views/partials/live-chat-simple.blade.php --}}
@auth
<!-- Live Chat Button (Only for logged-in users) -->
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
                            <input type="text" class="form-control" id="chatInputSimple" placeholder="Type your message...">
                            <button type="submit" class="btn btn-primary" id="sendBtnSimple">
                                <i class="ri-send-plane-2-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<!-- Login Prompt for Guests -->
<div class="login-prompt-chat" style="position: fixed; bottom: 90px; right: 20px; z-index: 1045;">
    <div class="card shadow-sm" style="width: 56px; height: 56px; border-radius: 50%; cursor: pointer; overflow: hidden; transition: all 0.3s ease; animation: floatAnimation 3s ease-in-out infinite; background: linear-gradient(135deg, #10b981, #059669); border: none;">
        <div class="card-body text-center p-0 d-flex align-items-center justify-content-center" onclick="window.location.href='{{ route('login') }}'">
            <i class="ri-chat-3-line text-white fs-4"></i>
        </div>
    </div>
    <div class="login-tooltip" style="position: absolute; bottom: 70px; right: 0; background: white; color: #333; padding: 8px 12px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-size: 0.8rem; white-space: nowrap; display: none; z-index: 1050; border: 1px solid #e5e7eb;">
        <div class="d-flex align-items-center gap-2">
            <i class="ri-information-line text-primary"></i>
            <span>Login to chat</span>
        </div>
        <div class="tooltip-arrow" style="position: absolute; bottom: -6px; right: 20px; width: 0; height: 0; border-left: 6px solid transparent; border-right: 6px solid transparent; border-top: 6px solid white;"></div>
    </div>
</div>
@endauth

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
    
    /* Floating animation */
    @keyframes floatAnimation {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-8px);
        }
    }
    
    /* Login prompt hover effect */
    .login-prompt-chat .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }
    
    .login-prompt-chat:hover .login-tooltip {
        display: block;
    }
    
    @media (max-width: 768px) {
        .live-chat-btn {
            bottom: 80px;
            right: 15px;
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
        
        .login-prompt-chat .card {
            width: 50px;
            height: 50px;
            bottom: 80px;
            right: 15px;
        }
        
        .login-prompt-chat .card-body i {
            font-size: 1.25rem;
        }
        
        .modal-dialog {
            margin: 0.5rem;
        }
        
        .chat-container-simple {
            height: 400px;
        }
        
        .login-tooltip {
            font-size: 0.7rem;
            padding: 6px 10px;
            bottom: 60px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize chat if user is logged in
    const chatBtn = document.getElementById('liveChatBtn');
    
    if (!chatBtn) {
        console.log('User not logged in - chat disabled');
        return;
    }
    
    const chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
    const chatMessages = document.getElementById('chatMessagesSimple');
    const chatForm = document.getElementById('chatFormSimple');
    const chatInput = document.getElementById('chatInputSimple');
    const sendBtn = document.getElementById('sendBtnSimple');
    const notificationBadge = document.querySelector('.chat-notification-badge');
    
    let pollInterval = null;
    let isChatOpen = false;
    let lastMessageId = 0;
    let currentConversation = null;
    
    // Open chat modal when button is clicked
    chatBtn.addEventListener('click', function() {
        isChatOpen = true;
        chatModal.show();
        initializeChat();
        startPolling();
        hideNotification();
    });
    
    // Close chat modal
    document.getElementById('chatModal').addEventListener('hidden.bs.modal', function() {
        isChatOpen = false;
        stopPolling();
    });
    
    // Initialize chat and get conversation
    async function initializeChat() {
        try {
            // Double-check authentication
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
            if (!isLoggedIn) {
                showAlert('Please login to use chat', 'warning');
                chatModal.hide();
                window.location.href = '{{ route("login") }}';
                return;
            }
            
            chatMessages.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <p class="text-muted">Loading chat...</p>
                </div>
            `;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const response = await fetch('/chat/conversation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})  // Empty object - server uses session
            });
            
            console.log('Chat init response status:', response.status);
            
            if (!response.ok) {
                if (response.status === 401) {
                    showAlert('Session expired. Please login again.', 'warning');
                    window.location.href = '{{ route("login") }}';
                    return;
                }
                
                console.error('Server error:', response.status, response.statusText);
                showAlert('Server error: ' + response.status, 'danger');
                return;
            }
            
            const data = await response.json();
            console.log('Chat response:', data);
            
            if (data.success) {
                currentConversation = data.conversation;
                console.log('Current conversation set:', currentConversation);
                displayMessages(data.messages);
                
                // Update last message ID
                if (data.messages.length > 0) {
                    lastMessageId = Math.max(...data.messages.map(m => m.id));
                }
            } else {
                showAlert('Failed to load chat: ' + (data.message || ''), 'danger');
            }
        } catch (error) {
            console.error('Error loading chat:', error);
            showAlert('Failed to load messages: ' + error.message, 'danger');
        }
    }
    
    // Load messages (for polling)
    async function loadMessages() {
        if (!currentConversation) return;
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const response = await fetch('/chat/conversation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})  // Empty object
            });
            
            if (!response.ok) {
                console.error('Load messages error:', response.status);
                return;
            }
            
            const data = await response.json();
            
            if (data.success && data.messages) {
                displayMessages(data.messages);
                
                // Update last message ID
                if (data.messages.length > 0) {
                    lastMessageId = Math.max(...data.messages.map(m => m.id));
                }
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }
    
    // Display messages
    function displayMessages(messages) {
        if (messages.length === 0) {
            chatMessages.innerHTML = `
                <div class="text-center py-5">
                    <i class="ri-chat-3-line text-muted mb-3" style="font-size: 3rem;"></i>
                    <p class="text-muted">No messages yet. Start the conversation!</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        messages.forEach(message => {
            const messageClass = message.is_admin ? 'message-admin-simple' : 'message-user-simple';
            const time = formatTime(message.created_at);
            
            html += `
                <div class="message-simple ${messageClass}">
                    <div class="message-bubble-simple">${escapeHtml(message.message)}</div>
                    <div class="message-time-simple">
                        ${escapeHtml(message.sender_name || 'User')} • ${time}
                    </div>
                </div>
            `;
        });
        
        chatMessages.innerHTML = html;
        scrollToBottom();
    }
    
    // Send message
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        
        // Validate input
        if (!message || message.length === 0) {
            showAlert('Please enter a message', 'warning');
            return;
        }
        
        // Validate conversation
        if (!currentConversation || !currentConversation.id) {
            showAlert('No active conversation. Please wait...', 'warning');
            // Try to initialize chat first
            await initializeChat();
            if (!currentConversation?.id) {
                showAlert('Failed to start conversation', 'danger');
                return;
            }
        }
        
        console.log('Sending message to conversation:', currentConversation.id);
        
        // Disable input while sending
        chatInput.disabled = true;
        sendBtn.disabled = true;
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const response = await fetch('/chat/message/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    conversation_id: Number(currentConversation.id),
                    message: message 
                })
            });
            
            console.log('Send response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Send error details:', errorText);
                
                // Parse error for better message
                try {
                    const errorData = JSON.parse(errorText);
                    if (errorData.errors) {
                        // Show specific validation errors
                        const errorMessages = Object.values(errorData.errors).flat();
                        showAlert('Error: ' + errorMessages.join(', '), 'danger');
                    } else if (errorData.message) {
                        showAlert(errorData.message, 'danger');
                    } else {
                        showAlert('Server error: ' + response.status, 'danger');
                    }
                } catch {
                    showAlert('Server error: ' + response.status, 'danger');
                }
                return;
            }
            
            const data = await response.json();
            console.log('Send success:', data);
            
            if (data.success) {
                chatInput.value = '';
                // Reload messages to show the new one
                loadMessages();
            } else {
                showAlert(data.message || 'Failed to send message', 'danger');
            }
        } catch (error) {
            console.error('Network error:', error);
            showAlert('Network error: ' + error.message, 'danger');
        } finally {
            chatInput.disabled = false;
            sendBtn.disabled = false;
            chatInput.focus();
        }
    });
    
    // Poll for new messages
    function startPolling() {
        stopPolling(); // Clear any existing interval
        pollInterval = setInterval(loadMessages, 30000); // Check every 30 seconds
    }
    
    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }
    
    // Check for new messages (for notification badge)
    async function checkForNewMessages() {
        if (isChatOpen || !currentConversation) return;
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const response = await fetch('/chat/conversation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})  // Empty object
            });
            
            if (!response.ok) {
                console.error('Check messages error:', response.status);
                return;
            }
            
            const data = await response.json();
            
            if (data.success && data.messages && data.messages.length > 0) {
                const latestId = Math.max(...data.messages.map(m => m.id));
                
                // Show notification if there are new messages
                if (latestId > lastMessageId) {
                    showNotification();
                    lastMessageId = latestId;
                }
            }
        } catch (error) {
            console.error('Error checking new messages:', error);
        }
    }
    
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
    
    // Helper functions
    function formatTime(dateString) {
        try {
            const date = new Date(dateString);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        } catch (e) {
            return 'Just now';
        }
    }
    
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
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
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Auto focus input when modal opens
    document.getElementById('chatModal').addEventListener('shown.bs.modal', function() {
        if (!chatInput.disabled) {
            chatInput.focus();
        }
    });
    
    // Check for new messages only when chat has been initialized and modal is closed
    let messageCheckInterval = null;
    
    // Only start checking for new messages after first initialization
    function startMessageCheck() {
        if (messageCheckInterval) return; // Already running
        if (isChatOpen) return; // Don't check while chat is open (polling handles that)
        
        messageCheckInterval = setInterval(() => {
            if (!isChatOpen && currentConversation) {
                checkForNewMessages();
            }
        }, 15000); // Check every 15 seconds instead of 5, and only if chat is closed
    }
    
    // Stop message checking when not needed
    function stopMessageCheck() {
        if (messageCheckInterval) {
            clearInterval(messageCheckInterval);
            messageCheckInterval = null;
        }
    }
    
    // Start message check after first chat initialization
    const originalInitialize = initializeChat;
    let hasInitialized = false;
    
    window.initializeChat = async function() {
        const result = await originalInitialize();
        if (!hasInitialized && currentConversation) {
            hasInitialized = true;
            startMessageCheck();
        }
        return result;
    };
    
    // Cleanup on page unload
    window.addEventListener('unload', function() {
        stopPolling();
        stopMessageCheck();
    });
});
</script>