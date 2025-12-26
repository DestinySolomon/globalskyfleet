@extends('layouts.app')

@section('title', 'Dashboard | GlobalSkyFleet')

@section('content')
<div class="container py-5" style="padding-top: 100px !important;">
    <div class="row">
        <div class="col-12">
            <h1 class="display-5 fw-bold text-navy mb-4">Welcome, {{ Auth::user()->name }}!</h1>
            <p class="text-muted fs-5">Your GlobalSkyFleet Dashboard</p>
            
            <div class="row mt-5">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-4">
                            <i class="ri-ship-fill text-skyblue fs-1 mb-3"></i>
                            <h5 class="card-title">My Shipments</h5>
                            <p class="text-muted">View and manage all your shipments</p>
                            <a href="#" class="btn btn-outline-skyblue">View Shipments</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-4">
                            <i class="ri-file-text-fill text-skyblue fs-1 mb-3"></i>
                            <h5 class="card-title">Create Shipment</h5>
                            <p class="text-muted">Create a new shipping request</p>
                            <a href="#" class="btn btn-outline-skyblue">New Shipment</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-4">
                            <i class="ri-user-fill text-skyblue fs-1 mb-3"></i>
                            <h5 class="card-title">My Profile</h5>
                            <p class="text-muted">Update your account information</p>
                            <a href="#" class="btn btn-outline-skyblue">Edit Profile</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-5">
                <h4 class="mb-4">Quick Actions</h4>
                <div class="d-flex gap-3">
                    <a href="{{ route('tracking') }}" class="btn btn-orange">Track Shipment</a>
                    <a href="{{ route('quote') }}" class="btn btn-outline-orange">Get Quote</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-navy">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection