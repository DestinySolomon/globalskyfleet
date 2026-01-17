class PageLoader {
    constructor() {
        this.loadingOverlay = document.getElementById("loadingOverlay");
        this.minimumShowTime = 1000;
        this.startTime = null;
        this.init();
    }

    init() {
        if (!this.loadingOverlay) {
            console.warn("Loading overlay element not found");
            return;
        }

        // Initialize the overlay (hidden by default)
        this.loadingOverlay.style.display = "none";
        this.loadingOverlay.classList.add("hidden");

        // Show on initial page load
        this.showInitialLoader();

        // Bind event listeners
        this.bindPageEvents();

        // Auto-hide when page is fully loaded
        this.autoHideOnLoad();
    }

    showInitialLoader() {
        // Show loader immediately on initial page load
        this.show();
    }

    bindPageEvents() {
        // Show loader on link clicks (internal navigation only)
        document.addEventListener("click", (e) => {
            const link = e.target.closest("a");
            if (
                link &&
                link.href &&
                !link.href.includes("#") &&
                !link.classList.contains("no-loader") &&
                !link.hasAttribute("data-bs-toggle")
            ) {
                const currentHost = window.location.host;
                const targetHost = new URL(link.href).host;

                // Only show for internal navigation
                if (currentHost === targetHost && !link.target) {
                    e.preventDefault();
                    this.show();
                    setTimeout(() => {
                        window.location.href = link.href;
                    }, 300);
                }
            }
        });

        // Show loader on form submissions
        document.addEventListener("submit", (e) => {
            const form = e.target;
            if (form && !form.classList.contains("no-loader")) {
                this.show("fast");
            }
        });

        // Bind AJAX events
        this.bindAjaxEvents();
    }

    bindAjaxEvents() {
        // Override fetch to show loader for non-notification requests
        const originalFetch = window.fetch;
        window.fetch = (...args) => {
            const url =
                typeof args[0] === "string"
                    ? args[0]
                    : args[0] instanceof Request
                    ? args[0].url
                    : args[0];

            // Skip loader for notification polling and small requests
            if (
                url &&
                (url.includes("/notifications") ||
                    url.includes("/broadcasting/auth") ||
                    url.includes("/mark-read"))
            ) {
                return originalFetch(...args);
            }

            // Check if it's an AJAX request
            const isAjax =
                args[1] &&
                args[1].headers &&
                (args[1].headers["X-Requested-With"] === "XMLHttpRequest" ||
                    args[1].headers["x-requested-with"] === "XMLHttpRequest");

            // Show fast loader for AJAX requests that take more than 200ms
            if (isAjax && url && !url.includes("/count")) {
                setTimeout(() => {
                    if (!this.startTime) {
                        this.show("fast");
                    }
                }, 200);
            }

            return originalFetch(...args)
                .then((response) => {
                    if (isAjax) {
                        setTimeout(() => this.hide(), 300);
                    }
                    return response;
                })
                .catch((error) => {
                    if (isAjax) {
                        this.hide();
                    }
                    throw error;
                });
        };
    }

    show(speed = "normal") {
        if (this.startTime) {
            // If already showing, just restart the animation
            this.hide(() => {
                setTimeout(() => this._showInternal(speed), 50);
            });
            return;
        }

        this._showInternal(speed);
    }

    _showInternal(speed = "normal") {
        this.startTime = Date.now();

        // Show overlay
        this.loadingOverlay.style.display = "flex";
        this.loadingOverlay.classList.remove("hidden");
        document.body.style.overflow = "hidden";

        // Set animation speeds
        const animationDuration = speed === "fast" ? "1.5s" : "3s";
        const textDelay = speed === "fast" ? 1500 : 3000;
        const progressDelay = speed === "fast" ? 1700 : 3200;

        // Reset and start plane animation
        const planeIcon = this.loadingOverlay.querySelector(".plane-icon");
        const planeTrail = this.loadingOverlay.querySelector(".plane-trail");
        const loadingText = this.loadingOverlay.querySelector(".loading-text");
        const loadingProgress =
            this.loadingOverlay.querySelector(".loading-progress");
        const progressBar = loadingProgress?.querySelector(".progress-bar");

        // Reset all animations
        if (planeIcon) {
            planeIcon.style.animation = "none";
            setTimeout(() => {
                planeIcon.style.animation = `flyAcross ${animationDuration} cubic-bezier(0.4, 0, 0.2, 1) forwards`;
            }, 10);
        }

        if (planeTrail) {
            planeTrail.style.animation = "none";
            setTimeout(() => {
                planeTrail.style.animation = `trailAcross ${animationDuration} cubic-bezier(0.4, 0, 0.2, 1) forwards`;
            }, 10);
        }

        // Hide text and progress initially
        if (loadingText) {
            loadingText.style.opacity = "0";
            loadingText.style.animation = "none";
        }

        if (loadingProgress) {
            loadingProgress.style.opacity = "0";
            loadingProgress.style.animation = "none";
        }

        if (progressBar) {
            progressBar.style.animation = "none";
        }

        // Show text after plane animation completes
        if (loadingText) {
            setTimeout(() => {
                loadingText.style.animation =
                    "fadeInText 0.5s ease-out forwards";
            }, textDelay);
        }

        // Show progress bar after text
        if (loadingProgress) {
            setTimeout(() => {
                loadingProgress.style.animation =
                    "fadeInProgress 0.5s ease-out forwards";
            }, progressDelay);
        }

        if (progressBar) {
            setTimeout(() => {
                const barSpeed = speed === "fast" ? "1s" : "2s";
                progressBar.style.animation = `progressLoad ${barSpeed} ease-in-out infinite`;
            }, progressDelay);
        }

        // Auto-hide after plane animation completes (for page load)
        const hideDelay = speed === "fast" ? 2500 : 4000;
        setTimeout(() => {
            if (
                this.startTime &&
                Date.now() - this.startTime >= hideDelay - 500
            ) {
                this.hide();
            }
        }, hideDelay);
    }

    hide(callback) {
        if (!this.startTime) {
            if (callback) callback();
            return;
        }

        const elapsed = Date.now() - this.startTime;
        const remaining = this.minimumShowTime - elapsed;

        // Ensure loader shows for minimum time
        if (remaining > 0) {
            setTimeout(() => this._forceHide(callback), remaining);
        } else {
            this._forceHide(callback);
        }
    }

    _forceHide(callback) {
        // Add fade out animation
        this.loadingOverlay.classList.add("hidden");

        // Remove overlay after animation
        setTimeout(() => {
            this.loadingOverlay.style.display = "none";

            // Reset everything
            const planeIcon = this.loadingOverlay.querySelector(".plane-icon");
            const planeTrail =
                this.loadingOverlay.querySelector(".plane-trail");
            const loadingText =
                this.loadingOverlay.querySelector(".loading-text");
            const loadingProgress =
                this.loadingOverlay.querySelector(".loading-progress");
            const progressBar = loadingProgress?.querySelector(".progress-bar");

            // Reset styles
            if (planeIcon) planeIcon.style.animation = "";
            if (planeTrail) planeTrail.style.animation = "";
            if (loadingText) {
                loadingText.style.opacity = "";
                loadingText.style.animation = "";
            }
            if (loadingProgress) {
                loadingProgress.style.opacity = "";
                loadingProgress.style.animation = "";
            }
            if (progressBar) progressBar.style.animation = "";

            // Restore body scroll
            document.body.style.overflow = "";

            // Reset timer
            this.startTime = null;

            if (callback) callback();
        }, 300);
    }

    autoHideOnLoad() {
        const handleLoad = () => {
            // Ensure page is really loaded
            setTimeout(() => {
                // Hide the loader after page load
                setTimeout(() => {
                    this.hide();
                }, 500);
            }, 100);
        };

        // Check if page is already loaded
        if (document.readyState === "complete") {
            setTimeout(() => handleLoad(), 300);
        } else {
            window.addEventListener("load", () => {
                setTimeout(() => handleLoad(), 300);
            });
        }

        // Alternative hide for fast-loading pages
        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(() => {
                // If loader has been showing for more than 2 seconds, hide it
                if (this.startTime && Date.now() - this.startTime > 2000) {
                    this.hide();
                }
            }, 500);
        });
    }

    // Manual control methods
    showManual(speed = "normal") {
        this.show(speed);
    }

    hideManual() {
        this.hide();
    }

    // Quick show/hide for AJAX operations
    showAjax() {
        this.show("fast");
    }

    hideAjax() {
        setTimeout(() => this.hide(), 300);
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    window.pageLoader = new PageLoader();
});

// Global functions for manual control
window.showLoading = (speed = "normal") => {
    if (window.pageLoader) {
        window.pageLoader.showManual(speed);
    } else {
        // Fallback if PageLoader isn't initialized yet
        const overlay = document.getElementById("loadingOverlay");
        if (overlay) {
            overlay.style.display = "flex";
            overlay.classList.remove("hidden");
            document.body.style.overflow = "hidden";
        }
    }
};

window.hideLoading = () => {
    if (window.pageLoader) {
        window.pageLoader.hideManual();
    } else {
        // Fallback if PageLoader isn't initialized yet
        const overlay = document.getElementById("loadingOverlay");
        if (overlay) {
            overlay.classList.add("hidden");
            setTimeout(() => {
                overlay.style.display = "none";
                document.body.style.overflow = "";
            }, 300);
        }
    }
};

// Export for module usage
window.PageLoader = PageLoader;
