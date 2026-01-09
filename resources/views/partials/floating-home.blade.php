@auth
    @if(request()->routeIs('dashboard*') || request()->routeIs('shipments*') || request()->routeIs('addresses*') || request()->routeIs('documents*') || request()->routeIs('billing*') || request()->routeIs('user.*'))
    <!-- Floating Home Button - Only shows on dashboard pages -->
    <a href="{{ route('home') }}" 
       class="floating-home-btn" 
       id="floatingHomeBtn" 
       title="Return to Homepage" 
       aria-label="Return to homepage">
        <i class="ri-home-4-line"></i>
        <span class="floating-home-tooltip">Home</span>
    </a>
    @endif
@endauth