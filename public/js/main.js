// Scroll effect for main navbar
window.addEventListener("scroll", function () {
    const mainNav = document.querySelector(".main-nav");
    const scrolled = window.scrollY > 100;

    if (scrolled) {
        mainNav.classList.add("scrolled");
    } else {
        mainNav.classList.remove("scrolled");
    }
});

// Mobile menu icon color change
const navbarToggler = document.querySelector(".navbar-toggler");
const mainNav = document.querySelector(".main-nav");

if (navbarToggler && mainNav) {
    navbarToggler.addEventListener("click", function () {
        const icon = this.querySelector("i");
        if (mainNav.classList.contains("scrolled")) {
            icon.classList.remove("text-white");
            icon.classList.add("text-navy");
        } else {
            icon.classList.remove("text-navy");
            icon.classList.add("text-white");
        }
    });

    // Update mobile menu icon on scroll
    window.addEventListener("scroll", function () {
        const icon = navbarToggler.querySelector("i");
        if (mainNav.classList.contains("scrolled")) {
            icon.classList.remove("text-white");
            icon.classList.add("text-navy");
        } else {
            icon.classList.remove("text-navy");
            icon.classList.add("text-white");
        }
    });
}

// Simple hover effects
document.addEventListener("DOMContentLoaded", function () {
    // Add hover classes
    const hoverTextNavy = document.querySelectorAll(".hover-text-navy");
    hoverTextNavy.forEach((el) => {
        el.addEventListener("mouseenter", function () {
            this.classList.add("text-navy");
        });
        el.addEventListener("mouseleave", function () {
            const mainNav = document.querySelector(".main-nav");
            if (!mainNav.classList.contains("scrolled")) {
                this.classList.remove("text-navy");
            }
        });
    });

    const hoverOpacity = document.querySelectorAll(".hover-opacity-100");
    hoverOpacity.forEach((el) => {
        el.addEventListener("mouseenter", function () {
            this.classList.remove("opacity-60", "opacity-80");
            this.classList.add("opacity-100");
        });
        el.addEventListener("mouseleave", function () {
            this.classList.add("opacity-60", "opacity-80");
            this.classList.remove("opacity-100");
        });
    });
});

// Counter animation function
function animateCounter(element, target, suffix, duration = 2000) {
    const start = 0;
    const increment = target / (duration / 16); // 60fps
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target + suffix;
            clearInterval(timer);
        } else {
            // Format numbers appropriately
            if (target === 99.8) {
                element.textContent = current.toFixed(1) + suffix;
            } else {
                element.textContent = Math.floor(current) + suffix;
            }
        }
    }, 16);
}

// Check if element is in viewport
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top <= window.innerHeight * 0.8 && // 80% of viewport height
        rect.bottom >= window.innerHeight * 0.2 // 20% from top
    );
}

// Counter observer setup
function setupCounters() {
    const counters = document.querySelectorAll(".counter");
    let animated = false;

    // Check on scroll if counters should animate
    function checkCounters() {
        const globalSection = document.getElementById("global-reach");

        if (globalSection && isInViewport(globalSection) && !animated) {
            animated = true;

            counters.forEach((counter) => {
                const target = parseFloat(counter.getAttribute("data-target"));
                const suffix = counter.getAttribute("data-suffix") || "";

                animateCounter(counter, target, suffix, 2000);
            });
        }
    }

    // Initial check
    checkCounters();

    // Check on scroll
    window.addEventListener("scroll", checkCounters);
}

// Initialize counters when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    // Add your existing hover effects code here...

    // Setup counters after existing code
    setupCounters();
});
