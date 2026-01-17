<!-- Top Navigation (Sub Navbar) -->
<div class="top-nav d-none d-lg-block">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center">
            <div class="d-flex gap-4">
                @php
                    $currentRoute = Route::currentRouteName();
                @endphp
                
                <a href="{{ route('services.air-freight') }}" 
                   class="text-white text-decoration-none opacity-90 hover-opacity-100 {{ $currentRoute == 'services.air-freight' ? 'active fw-bold opacity-100' : '' }}">
                    Air Freight
                </a>
                <a href="{{ route('services.sea-freight') }}" 
                   class="text-white text-decoration-none opacity-90 hover-opacity-100 {{ $currentRoute == 'services.sea-freight' ? 'active fw-bold opacity-100' : '' }}">
                    Sea Freight
                </a>
                <a href="{{ route('services.road-courier') }}" 
                   class="text-white text-decoration-none opacity-90 hover-opacity-100 {{ $currentRoute == 'services.road-courier' ? 'active fw-bold opacity-100' : '' }}">
                    Road Courier
                </a>
                <a href="{{ route('services.express-delivery') }}" 
                   class="text-white text-decoration-none opacity-90 hover-opacity-100 {{ $currentRoute == 'services.express-delivery' ? 'active fw-bold opacity-100' : '' }}">
                    Express Delivery
                </a>
                <a href="{{ route('services.warehousing') }}" 
                   class="text-white text-decoration-none opacity-90 hover-opacity-100 {{ $currentRoute == 'services.warehousing' ? 'active fw-bold opacity-100' : '' }}">
                    Warehousing
                </a>
            </div>
        </div>
    </div>
</div>