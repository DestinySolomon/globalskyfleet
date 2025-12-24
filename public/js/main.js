// Scroll effect for main navbar
window.addEventListener('scroll', function() {
    const mainNav = document.querySelector('.main-nav');
    const scrolled = window.scrollY > 100;
    
    if (scrolled) {
        mainNav.classList.add('scrolled');
    } else {
        mainNav.classList.remove('scrolled');
    }
});

// Mobile menu icon color change
const navbarToggler = document.querySelector('.navbar-toggler');
const mainNav = document.querySelector('.main-nav');

if (navbarToggler && mainNav) {
    navbarToggler.addEventListener('click', function() {
        const icon = this.querySelector('i');
        if (mainNav.classList.contains('scrolled')) {
            icon.classList.remove('text-white');
            icon.classList.add('text-navy');
        } else {
            icon.classList.remove('text-navy');
            icon.classList.add('text-white');
        }
    });

    // Update mobile menu icon on scroll
    window.addEventListener('scroll', function() {
        const icon = navbarToggler.querySelector('i');
        if (mainNav.classList.contains('scrolled')) {
            icon.classList.remove('text-white');
            icon.classList.add('text-navy');
        } else {
            icon.classList.remove('text-navy');
            icon.classList.add('text-white');
        }
    });
}

// Simple hover effects
document.addEventListener('DOMContentLoaded', function() {
    // Add hover classes
    const hoverTextNavy = document.querySelectorAll('.hover-text-navy');
    hoverTextNavy.forEach(el => {
        el.addEventListener('mouseenter', function() {
            this.classList.add('text-navy');
        });
        el.addEventListener('mouseleave', function() {
            const mainNav = document.querySelector('.main-nav');
            if (!mainNav.classList.contains('scrolled')) {
                this.classList.remove('text-navy');
            }
        });
    });
    
    const hoverOpacity = document.querySelectorAll('.hover-opacity-100');
    hoverOpacity.forEach(el => {
        el.addEventListener('mouseenter', function() {
            this.classList.remove('opacity-60', 'opacity-80');
            this.classList.add('opacity-100');
        });
        el.addEventListener('mouseleave', function() {
            this.classList.add('opacity-60', 'opacity-80');
            this.classList.remove('opacity-100');
        });
    });
});

