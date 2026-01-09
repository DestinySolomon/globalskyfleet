class NotificationManager {
    constructor() {
        this.pollingInterval = 30000; // 30 seconds
        this.notificationBell = document.querySelector(".notification-btn");
        this.notificationBadge = document.querySelector(".notification-badge");
        this.dropdownMenu = null;
        this.pollingTimer = null;
        this.isDropdownOpen = false;
        this.init();
    }

    init() {
        if (!this.notificationBell) {
            console.warn("Notification bell element not found");
            return;
        }

        // Load initial count
        this.fetchUnreadCount();

        // Start polling
        this.startPolling();

        // Setup dropdown
        this.setupDropdown();

        // Setup click handlers
        this.setupClickHandlers();

        // Listen for page visibility changes
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                this.stopPolling();
            } else {
                this.startPolling();
                this.fetchUnreadCount();
            }
        });
    }

    async fetchUnreadCount() {
        try {
            const response = await fetch("/notifications/count", {
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.getCsrfToken(),
                },
                credentials: "same-origin",
            });

            if (response.ok) {
                const data = await response.json();
                this.updateBadge(data.count);
            }
        } catch (error) {
            console.error("Failed to fetch notification count:", error);
        }
    }

    async fetchNotifications() {
        try {
            const response = await fetch("/notifications?per_page=10", {
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.getCsrfToken(),
                },
                credentials: "same-origin",
            });

            if (response.ok) {
                const data = await response.json();
                return data.notifications;
            }
        } catch (error) {
            console.error("Failed to fetch notifications:", error);
        }
        return null;
    }

    updateBadge(count) {
        if (!this.notificationBadge) return;

        if (count > 0) {
            this.notificationBadge.textContent = count > 99 ? "99+" : count;
            this.notificationBadge.style.display = "flex";
        } else {
            this.notificationBadge.style.display = "none";
        }
    }

    setupDropdown() {
        // Create dropdown menu
        this.dropdownMenu = document.createElement("div");
        this.dropdownMenu.className = "notification-dropdown";
        this.dropdownMenu.style.display = "none";
        this.dropdownMenu.innerHTML = `
            <div class="notification-dropdown-header">
                <h6>Notifications</h6>
                <div class="dropdown-actions">
                    <button class="btn-mark-all-read" title="Mark all as read">
                        <i class="ri-check-double-line"></i>
                    </button>
                    <button class="btn-clear-all" title="Clear all">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
            <div class="notification-list">
                <div class="loading-notifications">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span>Loading notifications...</span>
                </div>
            </div>
            <div class="notification-dropdown-footer">
                <a href="/notifications" class="view-all-link">
                    <i class="ri-notification-line"></i>
                    View All Notifications
                </a>
            </div>
        `;

        // Style the dropdown to match your theme
        this.addDropdownStyles();

        // Add to DOM
        document.body.appendChild(this.dropdownMenu);
    }

    addDropdownStyles() {
        const style = document.createElement("style");
        style.textContent = `
            .notification-dropdown {
                position: absolute;
                top: 100%;
                right: 0;
                width: 380px;
                max-width: 90vw;
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.1);
                border: 1px solid #e5e7eb;
                z-index: 1060;
                display: none;
                margin-top: 10px;
            }
            
            .notification-dropdown.show {
                display: block;
            }
            
            .notification-dropdown-header {
                padding: 1rem 1.25rem;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .notification-dropdown-header h6 {
                margin: 0;
                font-weight: 600;
                color: #1e293b;
                font-size: 1rem;
            }
            
            .dropdown-actions {
                display: flex;
                gap: 0.5rem;
            }
            
            .dropdown-actions button {
                background: none;
                border: none;
                color: #64748b;
                cursor: pointer;
                padding: 0.25rem;
                border-radius: 4px;
                transition: all 0.2s;
                font-size: 1.1rem;
            }
            
            .dropdown-actions button:hover {
                background: #f1f5f9;
                color: #1e40af;
            }
            
            .notification-list {
                max-height: 400px;
                overflow-y: auto;
            }
            
            .loading-notifications {
                padding: 2rem;
                text-align: center;
                color: #64748b;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }
            
            .notification-item {
                padding: 1rem 1.25rem;
                border-bottom: 1px solid #f1f5f9;
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                cursor: pointer;
                transition: background-color 0.2s;
            }
            
            .notification-item:hover {
                background-color: #f8fafc;
            }
            
            .notification-item.unread {
                background-color: #f0f9ff;
            }
            
            .notification-icon {
                width: 36px;
                height: 36px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 1.25rem;
            }
            
            .notification-icon.primary { background: #dbeafe; color: #1e40af; }
            .notification-icon.success { background: #d1fae5; color: #065f46; }
            .notification-icon.warning { background: #fef3c7; color: #92400e; }
            .notification-icon.danger { background: #fee2e2; color: #991b1b; }
            .notification-icon.info { background: #e0f2fe; color: #0369a1; }
            
            .notification-content {
                flex: 1;
                min-width: 0;
            }
            
            .notification-title {
                font-weight: 600;
                color: #1e293b;
                margin-bottom: 0.25rem;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                font-size: 0.95rem;
            }
            
            .notification-time {
                font-size: 0.75rem;
                color: #64748b;
                white-space: nowrap;
                margin-left: 0.5rem;
                font-weight: normal;
            }
            
            .notification-message {
                font-size: 0.875rem;
                color: #475569;
                line-height: 1.4;
                margin-bottom: 0.25rem;
            }
            
            .notification-meta {
                font-size: 0.75rem;
                color: #64748b;
            }
            
            .notification-dropdown-footer {
                padding: 0.75rem 1.25rem;
                border-top: 1px solid #e5e7eb;
                text-align: center;
            }
            
            .view-all-link {
                color: #1e40af;
                text-decoration: none;
                font-size: 0.875rem;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                transition: color 0.2s;
                font-weight: 500;
            }
            
            .view-all-link:hover {
                color: #1e3a8a;
                text-decoration: none;
            }
            
            .no-notifications {
                padding: 3rem 1.25rem;
                text-align: center;
                color: #64748b;
            }
            
            .no-notifications i {
                font-size: 2.5rem;
                margin-bottom: 1rem;
                color: #cbd5e1;
            }
            
            .no-notifications p {
                margin: 0;
                font-size: 0.95rem;
            }
            
            .no-notifications small {
                display: block;
                margin-top: 0.5rem;
                font-size: 0.85rem;
            }
            
            @media (max-width: 576px) {
                .notification-dropdown {
                    width: 320px;
                    right: -10px;
                }
                
                .notification-dropdown-header h6 {
                    font-size: 0.95rem;
                }
                
                .notification-item {
                    padding: 0.875rem 1rem;
                }
                
                .notification-icon {
                    width: 32px;
                    height: 32px;
                    font-size: 1.1rem;
                }
            }
        `;
        document.head.appendChild(style);
    }

    setupClickHandlers() {
        // Toggle dropdown on bell click
        this.notificationBell.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggleDropdown();
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", (e) => {
            if (
                this.isDropdownOpen &&
                !this.dropdownMenu.contains(e.target) &&
                !this.notificationBell.contains(e.target)
            ) {
                this.closeDropdown();
            }
        });

        // Close on escape key
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && this.isDropdownOpen) {
                this.closeDropdown();
            }
        });
    }

    async toggleDropdown() {
        if (!this.isDropdownOpen) {
            this.showDropdown();
            await this.loadNotifications();
        } else {
            this.closeDropdown();
        }
    }

    showDropdown() {
        this.dropdownMenu.classList.add("show");
        this.dropdownMenu.style.display = "block";
        this.isDropdownOpen = true;

        // Position dropdown
        const bellRect = this.notificationBell.getBoundingClientRect();
        this.dropdownMenu.style.top = `${
            bellRect.bottom + window.scrollY + 5
        }px`;
        this.dropdownMenu.style.right = `${
            window.innerWidth - bellRect.right
        }px`;
    }

    closeDropdown() {
        this.dropdownMenu.classList.remove("show");
        this.dropdownMenu.style.display = "none";
        this.isDropdownOpen = false;
    }

    async loadNotifications() {
        const listContainer =
            this.dropdownMenu.querySelector(".notification-list");

        // Show loading state immediately
        listContainer.innerHTML = `
            <div class="loading-notifications">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span>Loading notifications...</span>
            </div>
        `;

        try {
            const notifications = await this.fetchNotifications();

            if (!notifications || notifications.data.length === 0) {
                listContainer.innerHTML = `
                    <div class="no-notifications">
                        <i class="ri-notification-off-line"></i>
                        <p>No notifications yet</p>
                        <small class="text-muted">When you have notifications, they'll appear here</small>
                    </div>
                `;
                return;
            }

            let html = "";
            notifications.data.forEach((notification) => {
                html += this.renderNotificationItem(notification);
            });

            listContainer.innerHTML = html;

            // Add click handlers to notification items
            this.addNotificationItemHandlers();

            // Add handlers for mark all read and clear all buttons
            this.addActionButtonHandlers();
        } catch (error) {
            console.error("Failed to load notifications:", error);
            listContainer.innerHTML = `
                <div class="no-notifications">
                    <i class="ri-error-warning-line"></i>
                    <p>Failed to load notifications</p>
                    <small class="text-muted">Please try again later</small>
                </div>
            `;
        }
    }

    renderNotificationItem(notification) {
        const timeAgo = this.formatTimeAgo(notification.created_at);
        const readClass = notification.read ? "" : "unread";

        return `
            <div class="notification-item ${readClass}" data-id="${
            notification.id
        }">
                <div class="notification-icon ${notification.color}">
                    <i class="${notification.icon}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">
                        <span>${notification.title}</span>
                        <span class="notification-time">${timeAgo}</span>
                    </div>
                    <div class="notification-message">${
                        notification.message
                    }</div>
                    ${
                        notification.tracking_number
                            ? `<div class="notification-meta">Tracking: ${notification.tracking_number}</div>`
                            : ""
                    }
                </div>
            </div>
        `;
    }

    formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return "Just now";
        if (seconds < 3600) return Math.floor(seconds / 60) + "m ago";
        if (seconds < 86400) return Math.floor(seconds / 3600) + "h ago";
        if (seconds < 604800) return Math.floor(seconds / 86400) + "d ago";

        return date.toLocaleDateString("en-US", {
            month: "short",
            day: "numeric",
        });
    }

    addNotificationItemHandlers() {
        const items = this.dropdownMenu.querySelectorAll(".notification-item");

        items.forEach((item) => {
            item.addEventListener("click", async (e) => {
                const notificationId = item.dataset.id;

                // Mark as read if unread
                if (item.classList.contains("unread")) {
                    await this.markAsRead(notificationId);
                    item.classList.remove("unread");
                    // Update badge count
                    this.fetchUnreadCount();
                }

                // Navigate to URL if exists
                const notification = await this.getNotificationData(
                    notificationId
                );
                if (notification && notification.url) {
                    window.location.href = notification.url;
                }
            });
        });
    }

    addActionButtonHandlers() {
        const markAllBtn =
            this.dropdownMenu.querySelector(".btn-mark-all-read");
        const clearAllBtn = this.dropdownMenu.querySelector(".btn-clear-all");

        if (markAllBtn) {
            markAllBtn.addEventListener("click", async (e) => {
                e.stopPropagation();
                await this.markAllAsRead();
                this.fetchUnreadCount();
                this.loadNotifications();
            });
        }

        if (clearAllBtn) {
            clearAllBtn.addEventListener("click", async (e) => {
                e.stopPropagation();
                if (
                    confirm("Are you sure you want to clear all notifications?")
                ) {
                    await this.clearAllNotifications();
                    this.fetchUnreadCount();
                    this.loadNotifications();
                }
            });
        }
    }

    async markAsRead(notificationId) {
        try {
            await fetch(`/notifications/${notificationId}/read`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": this.getCsrfToken(),
                    Accept: "application/json",
                },
                credentials: "same-origin",
            });
        } catch (error) {
            console.error("Failed to mark as read:", error);
        }
    }

    async markAllAsRead() {
        try {
            await fetch("/notifications/read-all", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": this.getCsrfToken(),
                    Accept: "application/json",
                },
                credentials: "same-origin",
            });
        } catch (error) {
            console.error("Failed to mark all as read:", error);
        }
    }

    async clearAllNotifications() {
        try {
            await fetch("/notifications/clear-all", {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": this.getCsrfToken(),
                    Accept: "application/json",
                },
                credentials: "same-origin",
            });
        } catch (error) {
            console.error("Failed to clear all:", error);
        }
    }

    async getNotificationData(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}`, {
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.getCsrfToken(),
                },
                credentials: "same-origin",
            });

            if (response.ok) {
                return await response.json();
            }
        } catch (error) {
            console.error("Failed to get notification data:", error);
        }
        return null;
    }

    startPolling() {
        if (this.pollingTimer) clearInterval(this.pollingTimer);

        this.pollingTimer = setInterval(() => {
            this.fetchUnreadCount();
        }, this.pollingInterval);
    }

    stopPolling() {
        if (this.pollingTimer) {
            clearInterval(this.pollingTimer);
            this.pollingTimer = null;
        }
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || "";
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    // Wait a bit for the page to fully load
    setTimeout(() => {
        new NotificationManager();
    }, 500);
});
