@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    @include('components.hero')
    
    <!-- Services Section -->
    @include('components.services')
    
    <!-- Why Choose Us Section -->
    @include('components.why-choose-us')
    
    <!-- Global Reach Section -->
    @include('components.global-reach')
    
    <!-- Testimonials Section -->
    @include('components.testimonials')
    
    <!-- CTA Section -->
    @include('components.cta')
@endsection

@push('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
@endpush