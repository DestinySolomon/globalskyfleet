
<!-- Back to Top Button -->
<button type="button" class="btn btn-primary btn-floating back-to-top" id="backToTop">
    <i class="ri-arrow-up-line"></i>
</button>

<style>
    /* Back to Top Button Styles */
    .back-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        z-index: 1050;
        box-shadow: 0 6px 15px rgba(10, 36, 99, 0.3);
        transition: all 0.3s ease;
        border: none;
        opacity: 0;
        transform: translateY(20px);
        animation: floatAnimation 3s ease-in-out infinite;
        background: linear-gradient(135deg, #1e40af, #3b82f6);
    }
    
    .back-to-top.show {
        display: flex;
        opacity: 1;
        transform: translateY(0);
    }
    
    .back-to-top:hover {
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        transform: translateY(-5px) !important;
        box-shadow: 0 8px 25px rgba(10, 36, 99, 0.4);
        animation-play-state: paused;
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
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .back-to-top {
            bottom: 10px; /* Above mobile browser UI */
            right: 15px;
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
    }
    
    /* For tablets */
    @media (min-width: 769px) and (max-width: 1024px) {
        .back-to-top {
            bottom: 25px;
            right: 25px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('backToTop');
        const scrollThreshold = 300; // Show after scrolling 300px
        
        // Function to check scroll position
        function toggleBackToTop() {
            if (window.pageYOffset > scrollThreshold) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        }
        
        // Show/hide button on scroll
        window.addEventListener('scroll', toggleBackToTop);
        
        // Back to Top functionality
        backToTopButton.addEventListener('click', function() {
            // Smooth scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            
            // Optional: Add click animation
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
        });
        
        // Initial check
        toggleBackToTop();
    });
</script><?php /**PATH C:\Users\Dee\Desktop\General\globalskyfleet\resources\views/partials/back-to-top.blade.php ENDPATH**/ ?>