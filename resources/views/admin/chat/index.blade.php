@extends('layouts.admin')

@section('page-title', 'Live Chat Support')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Active Conversations</h5>
                    <small class="text-muted" id="lastRefreshTime">Never refreshed</small>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" onclick="loadConversations()" id="refreshBtn">
                        <i class="ri-refresh-line"></i> Refresh
                        <span class="badge bg-danger ms-2" id="newMessageBadge" style="display: none;">0</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="chatTable">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Last Message</th>
                                <th>Unread</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chat Modal for Admin -->
<div class="modal fade" id="adminChatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="adminChatUserName">Chat with User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="chat-container-admin d-flex flex-column" style="height: 500px;">
                    <div class="chat-messages-admin flex-grow-1 p-3" id="adminChatMessages" style="overflow-y: auto;"></div>
                    <div class="chat-input-admin p-3 border-top">
                        <form id="adminChatForm" class="d-flex gap-2">
                            <input type="hidden" id="adminConversationId">
                            <input type="text" class="form-control" id="adminChatInput" placeholder="Type your message...">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-send-plane-2-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chat-container-admin {
        height: 500px;
    }
    
    .admin-message {
        margin-bottom: 1rem;
        max-width: 80%;
    }
    
    .admin-message-user {
        margin-right: auto;
        background: #f1f5f9;
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
    }
    
    .admin-message-admin {
        margin-left: auto;
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 1rem;
    }
    
    /* Pulsing notification dot */
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .pulse-notification {
        animation: pulse 2s infinite;
    }
    
    /* Refresh button animation */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .refreshing {
        animation: spin 1s linear infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatTable = document.querySelector('#chatTable tbody');
        const adminChatModal = new bootstrap.Modal(document.getElementById('adminChatModal'));
        const adminChatMessages = document.getElementById('adminChatMessages');
        const adminChatForm = document.getElementById('adminChatForm');
        const adminChatInput = document.getElementById('adminChatInput');
        const adminConversationId = document.getElementById('adminConversationId');
        const adminChatUserName = document.getElementById('adminChatUserName');
        const refreshBtn = document.getElementById('refreshBtn');
        const lastRefreshTime = document.getElementById('lastRefreshTime');
        const newMessageBadge = document.getElementById('newMessageBadge');
        
        let currentAdminConversation = null;
        let adminPusher = null;
        let adminChannel = null;
        let adminMessagePollInterval = null;
        let totalUnreadMessages = 0;
        let lastRefresh = null;
        let isRefreshing = false;
        let previousUnreadCount = 0;
        
        // Request browser notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
        
        // Format time for display
        function formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
        
        function formatDateTime(date) {
            return date.toLocaleTimeString([], { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
        }
        
        // Update refresh time display
        function updateRefreshTime() {
            const now = new Date();
            lastRefresh = now;
            lastRefreshTime.textContent = 'Last refreshed: ' + formatDateTime(now);
        }
        
        // Show refreshing animation
        function showRefreshing() {
            if (isRefreshing) return;
            isRefreshing = true;
            refreshBtn.classList.add('refreshing');
            refreshBtn.disabled = true;
        }
        
        // Hide refreshing animation
        function hideRefreshing() {
            isRefreshing = false;
            refreshBtn.classList.remove('refreshing');
            refreshBtn.disabled = false;
        }
        
        // Update sidebar notification badge
        function updateSidebarBadge(count) {
            const sidebarBadge = document.getElementById('sidebarChatBadge');
            const sidebarPulse = document.getElementById('sidebarChatPulse');
            
            if (sidebarBadge) {
                if (count > 0) {
                    sidebarBadge.textContent = count;
                    sidebarBadge.style.display = 'inline-block';
                } else {
                    sidebarBadge.style.display = 'none';
                }
            }
            
            if (sidebarPulse) {
                if (count > 0) {
                    sidebarPulse.classList.add('pulse-notification');
                    sidebarPulse.style.display = 'block';
                } else {
                    sidebarPulse.classList.remove('pulse-notification');
                    sidebarPulse.style.display = 'none';
                }
            }
            
            // Update page title if there are new messages
            if (count > 0) {
                document.title = `(${count}) Live Chat - Admin`;
            } else {
                document.title = 'Live Chat - Admin';
            }
        }
        
        // Show browser notification for new messages
        function showBrowserNotification(newCount) {
            if ('Notification' in window && Notification.permission === 'granted' && newCount > 0) {
                // Only show notification if window is not focused
                if (!document.hasFocus()) {
                    const notification = new Notification('New Chat Messages', {
                        icon: '/favicon.ico',
                        body: `You have ${newCount} new unread message${newCount > 1 ? 's' : ''}`,
                        tag: 'chat-notification'
                    });
                    
                    // Close notification after 5 seconds
                    setTimeout(() => notification.close(), 5000);
                    
                    // Focus window when notification is clicked
                    notification.onclick = function() {
                        window.focus();
                        this.close();
                    };
                }
            }
        }
        
        // Load conversations
        async function loadConversations() {
            showRefreshing();
            
            try {
                const response = await fetch('/admin/chat/conversations', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    console.error('Load conversations error:', response.status);
                    throw new Error('Failed to load');
                }
                
                const data = await response.json();
                
                // Check for new messages
                if (data.conversations && data.conversations.length > 0) {
                    const newTotalUnread = data.conversations.reduce((sum, conv) => sum + (conv.unread_count || 0), 0);
                    
                    // Calculate new messages since last refresh
                    const newMessages = newTotalUnread - previousUnreadCount;
                    
                    // Update badge if there are new messages
                    if (newMessages > 0) {
                        newMessageBadge.textContent = newMessages;
                        newMessageBadge.style.display = 'inline-block';
                        
                        // Show browser notification
                        showBrowserNotification(newMessages);
                    } else {
                        newMessageBadge.style.display = 'none';
                    }
                    
                    // Update sidebar badge with total unread
                    updateSidebarBadge(newTotalUnread);
                    
                    // Store for next comparison
                    previousUnreadCount = newTotalUnread;
                    totalUnreadMessages = newTotalUnread;
                } else {
                    // No conversations, hide badges
                    newMessageBadge.style.display = 'none';
                    updateSidebarBadge(0);
                    previousUnreadCount = 0;
                    totalUnreadMessages = 0;
                }
                
                chatTable.innerHTML = '';
                
                if (data.conversations.length === 0) {
                    chatTable.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="ri-chat-3-line text-muted mb-3 d-block" style="font-size: 2rem;"></i>
                                No active conversations
                            </td>
                        </tr>
                    `;
                    updateRefreshTime();
                    hideRefreshing();
                    return;
                }
                
                data.conversations.forEach(conversation => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <strong>${conversation.user_name}</strong>
                            ${conversation.user_email ? `<br><small class="text-muted">${conversation.user_email}</small>` : ''}
                        </td>
                        <td>
                            <small>${conversation.last_message?.message || 'No messages'}</small>
                            <br>
                            <small class="text-muted">${conversation.last_message?.created_at || ''}</small>
                        </td>
                        <td>
                            ${conversation.unread_count > 0 ? 
                                `<span class="badge bg-danger">${conversation.unread_count}</span>` : 
                                '<span class="badge bg-secondary">0</span>'}
                        </td>
                        <td>
                            <span class="badge bg-${getStatusColor(conversation.status)}">
                                ${conversation.status}
                            </span>
                        </td>
                        <td>${conversation.created_at}</td>
                        <td>
                            <button class="btn btn-sm btn-primary view-chat" data-id="${conversation.id}">
                                <i class="ri-chat-3-line"></i> View
                            </button>
                        </td>
                    `;
                    
                    chatTable.appendChild(row);
                });
                
                // Add event listeners to view buttons
                document.querySelectorAll('.view-chat').forEach(button => {
                    button.addEventListener('click', function() {
                        const conversationId = this.getAttribute('data-id');
                        openAdminChat(conversationId);
                    });
                });
                
                updateRefreshTime();
                
            } catch (error) {
                console.error('Error loading conversations:', error);
                chatTable.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-danger py-3">
                            Failed to load conversations
                        </td>
                    </tr>
                `;
            } finally {
                hideRefreshing();
            }
        }
        
        // Open admin chat - NO AUTO-REFRESH/PULLING
        async function openAdminChat(conversationId) {
            try {
                // Show loading
                adminChatMessages.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p class="text-muted">Loading conversation...</p>
                    </div>
                `;
                
                // Load conversation details
                const response = await fetch(`/admin/chat/conversation/${conversationId}/json`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    console.error('Open chat error:', response.status);
                    throw new Error('Failed to load conversation');
                }
                
                const data = await response.json();
                currentAdminConversation = data.conversation;
                
                // Update UI
                adminChatUserName.textContent = `Chat with ${currentAdminConversation.user_name || 'User'}`;
                adminConversationId.value = currentAdminConversation.id;
                
                // Display messages
                displayAdminMessages(data.messages);
                
                // NO AUTO-POLLING - Admin will manually refresh or rely on WebSockets
                // startAdminMessagePolling(); // REMOVED
                
                // Initialize Pusher for admin (optional - if you want real-time)
                // initializeAdminPusher(); // Comment out if not using Pusher
                
                // Show modal
                adminChatModal.show();
                
            } catch (error) {
                console.error('Error opening chat:', error);
                adminChatMessages.innerHTML = `
                    <div class="alert alert-danger m-3">
                        Failed to load conversation
                    </div>
                `;
            }
        }
        
        // REMOVED: Start polling for admin messages
        // function startAdminMessagePolling() {
        //     // Remove this entire function
        // }
        
        // REMOVED: Stop polling for admin messages
        // function stopAdminMessagePolling() {
        //     // Remove this entire function
        // }
        
        // Display admin messages
        function displayAdminMessages(messages) {
            adminChatMessages.innerHTML = '';
            
            if (messages.length === 0) {
                adminChatMessages.innerHTML = `
                    <div class="text-center py-5">
                        <p class="text-muted">No messages yet</p>
                    </div>
                `;
                return;
            }
            
            messages.forEach(message => {
                addAdminMessage(message);
            });
            
            scrollAdminToBottom();
        }
        
        // Add admin message
        function addAdminMessage(message) {
            const messageClass = message.is_admin ? 'admin-message-admin' : 'admin-message-user';
            const time = formatTime(message.created_at);
            
            const messageElement = document.createElement('div');
            messageElement.className = `admin-message ${messageClass}`;
            messageElement.innerHTML = `
                <div>${message.message}</div>
                <small class="d-block mt-1 ${message.is_admin ? 'text-white-50' : 'text-muted'}">
                    ${message.sender_name} • ${time}
                </small>
            `;
            
            adminChatMessages.appendChild(messageElement);
        }
        
        // Initialize Pusher for admin (optional)
        function initializeAdminPusher() {
            if (!window.PUSHER_APP_KEY || !currentAdminConversation) return;
            
            // Disconnect existing
            if (adminPusher) {
                adminPusher.disconnect();
            }
            
            // Initialize Pusher
            adminPusher = new Pusher(window.PUSHER_APP_KEY, {
                cluster: window.PUSHER_APP_CLUSTER,
                encrypted: true
            });
            
            // Subscribe to channel
            adminChannel = adminPusher.subscribe('private-chat.' + currentAdminConversation.id);
            
            // Listen for messages
            adminChannel.bind('message.sent', function(data) {
                addAdminMessage({
                    message: data.message,
                    sender_type: data.sender_type,
                    sender_name: data.sender_name,
                    is_admin: data.is_admin,
                    created_at: data.created_at
                });
                
                scrollAdminToBottom();
                // Refresh conversations list to update unread counts
                loadConversations();
            });
        }
        
        // Send message from admin
        adminChatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = adminChatInput.value.trim();
            if (!message || !currentAdminConversation) return;
            
            // Disable input while sending
            adminChatInput.disabled = true;
            
            try {
                const response = await fetch('/admin/chat/reply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        conversation_id: currentAdminConversation.id,
                        message: message
                    })
                });
                
                if (!response.ok) {
                    console.error('Reply error:', response.status, response.statusText);
                    alert('Failed to send message: ' + response.status);
                    return;
                }
                
                const data = await response.json();
                console.log('Reply response:', data);
                
                if (data.success) {
                    adminChatInput.value = '';
                    // Add the sent message to the chat
                    if (data.message) {
                        addAdminMessage(data.message);
                        scrollAdminToBottom();
                    }
                    // Refresh conversations list
                    loadConversations();
                } else {
                    alert('Failed to send: ' + (data.message || 'Unknown error'));
                }
                
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Error sending message: ' + error.message);
            } finally {
                adminChatInput.disabled = false;
                adminChatInput.focus();
            }
        });
        
        // Helper functions
        function getStatusColor(status) {
            const colors = {
                'active': 'success',
                'pending': 'warning',
                'resolved': 'info',
                'closed': 'secondary'
            };
            return colors[status] || 'secondary';
        }
        
        function scrollAdminToBottom() {
            setTimeout(() => {
                adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
            }, 100);
        }
        
        // Initial load
        loadConversations();
        
        // Clean up when modal closes
        document.getElementById('adminChatModal').addEventListener('hidden.bs.modal', function() {
            // NO POLLING TO STOP
            currentAdminConversation = null;
            
            // Disconnect Pusher if using it
            if (adminPusher) {
                adminPusher.disconnect();
                adminPusher = null;
                adminChannel = null;
            }
        });
        
        // Keyboard shortcut: Ctrl+R or Cmd+R to refresh
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                loadConversations();
            }
        });
        
        // Add refresh button to chat modal for manual refresh
        function addChatRefreshButton() {
            // Add refresh button to chat modal header
            const modalHeader = document.querySelector('#adminChatModal .modal-header');
            if (modalHeader && !modalHeader.querySelector('.chat-refresh-btn')) {
                const refreshBtn = document.createElement('button');
                refreshBtn.className = 'btn btn-sm btn-outline-light chat-refresh-btn ms-2';
                refreshBtn.innerHTML = '<i class="ri-refresh-line"></i>';
                refreshBtn.title = 'Refresh messages';
                refreshBtn.onclick = refreshCurrentChat;
                modalHeader.appendChild(refreshBtn);
            }
        }
        
        // Refresh current chat messages
        async function refreshCurrentChat() {
            if (!currentAdminConversation) return;
            
            try {
                const response = await fetch(`/admin/chat/conversation/${currentAdminConversation.id}/json`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) return;
                
                const data = await response.json();
                if (data.success && data.messages) {
                    displayAdminMessages(data.messages);
                }
            } catch (error) {
                console.error('Error refreshing chat:', error);
            }
        }
        
        // Add refresh button when modal is shown
        document.getElementById('adminChatModal').addEventListener('shown.bs.modal', function() {
            addChatRefreshButton();
        });
    });
</script>
@endsection