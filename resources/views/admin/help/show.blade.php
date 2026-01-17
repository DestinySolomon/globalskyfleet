@extends('layouts.admin')

@section('page-title', 'Help - ' . $topicData['title'])

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.help') }}">Help Center</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $topicData['title'] }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Topic Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">{{ $topicData['title'] }}</h4>
                </div>
                <div class="card-body">
                    {!! $topicData['content'] !!}
                    
                    <!-- Related Articles -->
                    <div class="mt-5 pt-4 border-top">
                        <h5 class="mb-3">Related Articles</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="#" class="text-decoration-none">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-2">How to Update Shipment Status</h6>
                                            <small class="text-muted">Learn how to track and update shipment status</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="#" class="text-decoration-none">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-2">Managing User Accounts</h6>
                                            <small class="text-muted">Guide to managing user roles and permissions</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Last updated: {{ now()->format('F d, Y') }}
                        </small>
                        <div>
                            <a href="{{ route('admin.help') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-2"></i> Back to Help Center
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Was This Helpful? -->
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="mb-3">Was this article helpful?</h5>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <button class="btn btn-outline-success">
                            <i class="ri-thumb-up-line me-2"></i> Yes
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="ri-thumb-down-line me-2"></i> No
                        </button>
                    </div>
                    <small class="text-muted">Your feedback helps us improve</small>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Table of Contents -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Table of Contents</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#section1" class="list-group-item list-group-item-action">
                            <i class="ri-file-text-line me-2"></i> Introduction
                        </a>
                        <a href="#section2" class="list-group-item list-group-item-action">
                            <i class="ri-settings-3-line me-2"></i> Getting Started
                        </a>
                        <a href="#section3" class="list-group-item list-group-item-action">
                            <i class="ri-user-settings-line me-2"></i> User Management
                        </a>
                        <a href="#section4" class="list-group-item list-group-item-action">
                            <i class="ri-ship-line me-2"></i> Shipment Management
                        </a>
                        <a href="#section5" class="list-group-item list-group-item-action">
                            <i class="ri-currency-line me-2"></i> Payment Processing
                        </a>
                    </div>
                </div>
            </div>

            <!-- Need More Help? -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Need More Help?</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:support@globalskyfleet.com" class="btn btn-primary">
                            <i class="ri-mail-line me-2"></i> Contact Support
                        </a>
                        <a href="{{ route('admin.help') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-go-back-line me-2"></i> Browse More Topics
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Quick Tips</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="ri-lightbulb-line me-2"></i>
                        Use keyboard shortcuts for faster navigation
                    </div>
                    <div class="alert alert-warning mb-3">
                        <i class="ri-alert-line me-2"></i>
                        Always verify payment details before confirming
                    </div>
                    <div class="alert alert-success mb-0">
                        <i class="ri-checkbox-circle-line me-2"></i>
                        Regular backups ensure data safety
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }
    
    .breadcrumb-item a {
        text-decoration: none;
        color: var(--bs-primary);
    }
    
    .breadcrumb-item.active {
        color: var(--bs-secondary);
    }
</style>
@endpush
@endsection