<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminHelpController extends Controller
{
    /**
     * Display help index page.
     */
    public function index()
    {
        $topics = [
            [
                'id' => 'getting-started',
                'title' => 'Getting Started',
                'description' => 'Learn the basics of the admin dashboard',
                'icon' => 'ri-rocket-line',
                'color' => 'primary',
            ],
            [
                'id' => 'shipment-management',
                'title' => 'Shipment Management',
                'description' => 'How to manage shipments and track status',
                'icon' => 'ri-ship-line',
                'color' => 'success',
            ],
            [
                'id' => 'user-management',
                'title' => 'User Management',
                'description' => 'Managing users, roles, and permissions',
                'icon' => 'ri-user-line',
                'color' => 'info',
            ],
            [
                'id' => 'payment-processing',
                'title' => 'Payment Processing',
                'description' => 'Handling payments and crypto transactions',
                'icon' => 'ri-currency-line',
                'color' => 'warning',
            ],
            [
                'id' => 'document-verification',
                'title' => 'Document Verification',
                'description' => 'Verifying user documents and uploads',
                'icon' => 'ri-file-text-line',
                'color' => 'danger',
            ],
            [
                'id' => 'analytics-reports',
                'title' => 'Analytics & Reports',
                'description' => 'Understanding analytics and generating reports',
                'icon' => 'ri-line-chart-line',
                'color' => 'purple',
            ],
        ];

        $faqs = [
            [
                'question' => 'How do I update shipment status?',
                'answer' => 'Navigate to the shipment details page and use the status update section to change the status and add location information.',
            ],
            [
                'question' => 'How do I verify a document?',
                'answer' => 'Go to Documents page, click on the document to view details, and use the verify/reject buttons with optional notes.',
            ],
            [
                'question' => 'How do I process crypto payments?',
                'answer' => 'Go to Crypto Payments page, view payment details, verify transaction, and update status accordingly.',
            ],
            [
                'question' => 'How do I add a new admin user?',
                'answer' => 'Go to User Management, edit the user, and change their role to "admin".',
            ],
            [
                'question' => 'How do I export data?',
                'answer' => 'Most listing pages have export buttons (CSV/Excel). Analytics page also has export options for reports.',
            ],
        ];

        return view('admin.help.index', compact('topics', 'faqs'));
    }

    /**
     * Display a specific help topic.
     */
    public function show($topic)
    {
        $topics = [
            'getting-started' => [
                'title' => 'Getting Started with Admin Dashboard',
                'content' => '
                    <h5>Welcome to the Admin Dashboard</h5>
                    <p>This dashboard provides complete control over your courier service platform.</p>
                    
                    <h6>Key Areas:</h6>
                    <ul>
                        <li><strong>Dashboard:</strong> Overview of system statistics and recent activity</li>
                        <li><strong>Shipments:</strong> Manage all shipments, track status, and update locations</li>
                        <li><strong>Users:</strong> View and manage user accounts and roles</li>
                        <li><strong>Documents:</strong> Verify user-uploaded documents</li>
                        <li><strong>Payments:</strong> Process crypto and other payments</li>
                        <li><strong>Analytics:</strong> View reports and system performance metrics</li>
                    </ul>
                ',
            ],
            'shipment-management' => [
                'title' => 'Shipment Management Guide',
                'content' => '
                    <h5>Managing Shipments</h5>
                    <p>Track and update shipment status through the admin interface.</p>
                    
                    <h6>Key Actions:</h6>
                    <ul>
                        <li><strong>View All Shipments:</strong> See all shipments with filtering options</li>
                        <li><strong>Update Status:</strong> Change status and add location updates</li>
                        <li><strong>View Details:</strong> See complete shipment information including addresses and history</li>
                        <li><strong>Generate Labels:</strong> Create shipping labels for shipments</li>
                    </ul>
                    
                    <h6>Status Flow:</h6>
                    <p>Pending → Confirmed → Picked Up → In Transit → Out for Delivery → Delivered</p>
                ',
            ],
        ];

        if (!isset($topics[$topic])) {
            abort(404);
        }

        $topicData = $topics[$topic];

        return view('admin.help.show', compact('topicData'));
    }
}