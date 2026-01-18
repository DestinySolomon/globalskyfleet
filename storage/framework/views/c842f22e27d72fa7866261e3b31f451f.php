

<?php $__env->startSection('page-title', 'Live Chat Support'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Active Conversations</h5>
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
        
        let currentAdminConversation = null;
        let adminPusher = null;
        let adminChannel = null;
        
        // Load conversations
        async function loadConversations() {
            try {
                const response = await fetch('/chat/admin/conversations', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
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
                
            } catch (error) {
                console.error('Error loading conversations:', error);
                chatTable.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-danger py-3">
                            Failed to load conversations
                        </td>
                    </tr>
                `;
            }
        }
        
        // Open admin chat
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
                const response = await fetch(`/chat/admin/conversation/${conversationId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                currentAdminConversation = data.conversation;
                
                // Update UI
                adminChatUserName.textContent = `Chat with ${currentAdminConversation.user_name}`;
                adminConversationId.value = currentAdminConversation.id;
                
                // Display messages
                displayAdminMessages(data.messages);
                
                // Initialize Pusher for admin
                initializeAdminPusher();
                
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
        
        // Initialize Pusher for admin
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
                loadConversations(); // Refresh table
            });
        }
        
        // Send message from admin
        adminChatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = adminChatInput.value.trim();
            if (!message || !currentAdminConversation) return;
            
            try {
                const response = await fetch('/chat/message/send', {
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
                
                const data = await response.json();
                
                if (data.success) {
                    adminChatInput.value = '';
                }
                
            } catch (error) {
                console.error('Error sending message:', error);
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
        
        function formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
        
        function scrollAdminToBottom() {
            adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
        }
        
        // Initial load
        loadConversations();
        
        // Auto-refresh every 30 seconds
        setInterval(loadConversations, 30000);
        
        // Clean up Pusher when modal closes
        document.getElementById('adminChatModal').addEventListener('hidden.bs.modal', function() {
            if (adminPusher) {
                adminPusher.disconnect();
                adminPusher = null;
                adminChannel = null;
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views\admin\chat-simple.blade.php ENDPATH**/ ?>