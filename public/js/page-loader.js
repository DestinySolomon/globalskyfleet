class PageLoader {
    constructor() {
        this.loadingOverlay = document.getElementById('loadingOverlay');
        this.minimumShowTime = 800; // Minimum time to show loader (ms)
        this.startTime = null;
        this.init();
    }
    
    init() {
        if (!this.loadingOverlay) {
            console.warn('Loading overlay element not found');
            return;
        }
        
        // Show loader when page starts loading
        this.bindPageEvents();
        
        // Auto-hide when page is fully loaded
        this.autoHideOnLoad();
    }
    
    bindPageEvents() {
        // Show loader on link clicks
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && 
                link.href && 
                !link.href.includes('#') && 
                !link.classList.contains('no-loader') &&
                !link.hasAttribute('data-bs-toggle')) {
                this.show();
            }
        });
        
        // Show loader on form submissions
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form && !form.classList.contains('no-loader')) {
                this.show('fast');
            }
        });
        
        // Show loader on AJAX requests
        this.bindAjaxEvents();
    }
    
    bindAjaxEvents() {
        // Override fetch to show loader
        const originalFetch = window.fetch;
        window.fetch = (...args) => {
            // Don't show loader for notification polling
            const url = typeof args[0] === 'string' ? args[0] : args[0].url;
            if (url && url.includes('/notifications')) {
                return originalFetch(...args);
            }
            
            this.show('fast');
            return originalFetch(...args)
                .then(response => {
                    setTimeout(() => this.hide(), 300);
                    return response;
                })
                .catch(error => {
                    this.hide();
                    throw error;
                });
        };
    }
    
    show(speed = 'normal') {
        if (this.startTime) return; // Already showing
        
        this.startTime = Date.now();
        
        // Add speed class if fast
        if (speed === 'fast') {
            this.loadingOverlay.classList.add('fast');
        } else {
            this.loadingOverlay.classList.remove('fast');
        }
        
        // Show overlay
        setTimeout(() => {
            this.loadingOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }, 10);
    }
    
    hide() {
        if (!this.startTime) return;
        
        const elapsed = Date.now() - this.startTime;
        const remaining = this.minimumShowTime - elapsed;
        
        // Ensure loader shows for minimum time
        if (remaining > 0) {
            setTimeout(() => this.forceHide(), remaining);
        } else {
            this.forceHide();
        }
    }
    
    forceHide() {
        this.loadingOverlay.classList.remove('active');
        document.body.style.overflow = '';
        this.startTime = null;
        
        // Remove fast class after hiding
        setTimeout(() => {
            this.loadingOverlay.classList.remove('fast');
        }, 300);
    }
    
    autoHideOnLoad() {
        // Hide when page is fully loaded
        if (document.readyState === 'complete') {
            this.hide();
        } else {
            window.addEventListener('load', () => {
                setTimeout(() => this.hide(), 300);
            });
        }
        
        // Also hide when DOM is loaded (for faster pages)
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => this.hide(), 100);
        });
    }
    
    // Manual control methods
    showManual() {
        this.show();
    }
    
    hideManual() {
        this.hide();
    }
    
    // For form submissions with validation
    showForForm(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', () => this.show('fast'));
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.pageLoader = new PageLoader();
});

// Export for manual control
window.PageLoader = PageLoader;