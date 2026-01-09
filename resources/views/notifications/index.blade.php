@extends('layouts.dashboard')

@section('page-title', 'Notifications')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifications</h5>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm" id="markAllReadBtn">
                            <i class="ri-check-double-line"></i> Mark All as Read
                        </button>
                        <button class="btn btn-outline-danger btn-sm" id="clearAllBtn">
                            <i class="ri-delete-bin-line"></i> Clear All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="notificationsContainer">
                        <!-- Notifications will be loaded here via JavaScript -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading notifications...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-page-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s;
    }
    
    .notification-page-item:hover {
        background-color: #f8fafc;
    }
    
    .notification-page-item.unread {
        background-color: #f0f9ff;
        border-left: 3px solid #3b82f6;
    }
    
    .notification-content {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .notification-details {
        flex: 1;
    }
    
    .notification-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #1e293b;
    }
    
    .notification-message {
        color: #475569;
        margin-bottom: 0.5rem;
    }
    
    .notification-meta {
        font-size: 0.875rem;
        color: #64748b;
        display: flex;
        gap: 1rem;
    }
    
    .notification-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .no-notifications {
        text-align: center;
        padding: 3rem;
        color: #64748b;
    }
    
    .no-notifications i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #cbd5e1;
    }
</style>

<script>
class NotificationsPage {
    constructor() {
        this.container = document.getElementById('notificationsContainer');
        this.markAllBtn = document.getElementById('markAllReadBtn');
        this.clearAllBtn = document.getElementById('clearAllBtn');
        this.init();
    }
    
    init() {
        this.loadNotifications();
        
        if (this.markAllBtn) {
            this.markAllBtn.addEventListener('click', () => this.markAllAsRead());
        }
        
        if (this.clearAllBtn) {
            this.clearAllBtn.addEventListener('click', () => this.clearAll());
        }
    }
    
    async loadNotifications() {
        try {
            const response = await fetch('/notifications?per_page=50', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.renderNotifications(data.notifications);
            }
        } catch (error) {
            console.error('Failed to load notifications:', error);
            this.container.innerHTML = `
                <div class="no-notifications">
                    <i class="ri-error-warning-line"></i>
                    <p>Failed to load notifications. Please try again.</p>
                </div>
            `;
        }
    }
    
    renderNotifications(notifications) {
        if (!notifications.data.length) {
            this.container.innerHTML = `
                <div class="no-notifications">
                    <i class="ri-notification-off-line"></i>
                    <p>No notifications yet</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        notifications.data.forEach(notification => {
            html += this.renderNotificationItem(notification);
        });
        
        // Add pagination if needed
        if (notifications.links) {
            html += this.renderPagination(notifications.links);
        }
        
        this.container.innerHTML = html;
        
        // Add click handlers
        this.addEventListeners();
    }
    
    renderNotificationItem(notification) {
        const timeAgo = this.formatTimeAgo(notification.created_at);
        const readClass = notification.read ? '' : 'unread';
        
        return `
            <div class="notification-page-item ${readClass}" data-id="${notification.id}">
                <div class="notification-content">
                    <div class="notification-icon ${notification.color}">
                        <i class="${notification.icon}"></i>
                    </div>
                    <div class="notification-details">
                        <div class="notification-title">${notification.title}</div>
                        <div class="notification-message">${notification.message}</div>
                        <div class="notification-meta">
                            <span>${timeAgo}</span>
                            ${notification.tracking_number ? 
                                `<span>Tracking: ${notification.tracking_number}</span>` : 
                                ''}
                            ${notification.category ? 
                                `<span class="badge bg-light text-dark">${notification.category}</span>` : 
                                ''}
                        </div>
                    </div>
                    <div class="notification-actions">
                        ${!notification.read ? `
                            <button class="btn btn-sm btn-outline-primary mark-read-btn" data-id="${notification.id}">
                                <i class="ri-check-line"></i> Mark Read
                            </button>
                        ` : ''}
                        ${notification.url ? `
                            <a href="${notification.url}" class="btn btn-sm btn-outline-secondary">
                                <i class="ri-external-link-line"></i> View
                            </a>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }
    
    renderPagination(links) {
        return `
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    ${links.map(link => `
                        <li class="page-item ${link.active ? 'active' : ''} ${link.url ? '' : 'disabled'}">
                            <a class="page-link" href="${link.url || '#'}">${link.label}</a>
                        </li>
                    `).join('')}
                </ul>
            </nav>
        `;
    }
    
    formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'Just now';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
        if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';
        
        return date.toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric' 
        });
    }
    
    addEventListeners() {
        // Mark as read buttons
        document.querySelectorAll('.mark-read-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const notificationId = btn.dataset.id;
                await this.markAsRead(notificationId);
                btn.closest('.notification-page-item').classList.remove('unread');
                btn.remove();
            });
        });
        
        // Notification item clicks
        document.querySelectorAll('.notification-page-item').forEach(item => {
            item.addEventListener('click', (e) => {
                if (!e.target.closest('.notification-actions')) {
                    const notificationId = item.dataset.id;
                    this.markAsRead(notificationId);
                    item.classList.remove('unread');
                    const markBtn = item.querySelector('.mark-read-btn');
                    if (markBtn) markBtn.remove();
                }
            });
        });
    }
    
    async markAsRead(notificationId) {
        try {
            await fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
        } catch (error) {
            console.error('Failed to mark as read:', error);
        }
    }
    
    async markAllAsRead() {
        try {
            await fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            // Reload notifications
            this.loadNotifications();
        } catch (error) {
            console.error('Failed to mark all as read:', error);
        }
    }
    
    async clearAll() {
        if (!confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
            return;
        }
        
        try {
            await fetch('/notifications/clear-all', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            // Reload notifications
            this.loadNotifications();
        } catch (error) {
            console.error('Failed to clear all:', error);
        }
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    new NotificationsPage();
});
</script>
@endsection