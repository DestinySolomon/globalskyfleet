@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-navy">Contact Messages</h1>
        <div>
            <span class="badge bg-danger">{{ $unreadCount }} Unread</span>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                        <tr class="{{ $message->status === 'unread' ? 'table-warning' : '' }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($message->status === 'unread')
                                    <span class="badge bg-danger me-2">NEW</span>
                                    @endif
                                    <!-- SECURE: Use safe_name accessor -->
                                    <strong>{{ $message->safe_name }}</strong>
                                </div>
                            </td>
                            <td>
                                <!-- SECURE: Use safe_email accessor -->
                                <a href="mailto:{{ $message->safe_email }}" class="text-decoration-none">
                                    {{ $message->safe_email }}
                                </a>
                            </td>
                            <td>{{ $message->subject_display }}</td>
                            <td>
                                <span class="{{ $message->status_badge_class }}">
                                    {{ ucfirst($message->status) }}
                                </span>
                            </td>
                            <td>{{ $message->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.contact-messages.show', $message->id) }}" 
                                   class="btn btn-sm btn-outline-primary" title="View Message">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $message->id }}"
                                        title="Delete Message">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                                
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $message->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Message</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- SECURE: Use safe_name accessor -->
                                                Are you sure you want to delete this message from {{ $message->safe_name }}?
                                                <div class="mt-2 small text-muted">
                                                    <strong>Email:</strong> {{ $message->safe_email }}<br>
                                                    <strong>Subject:</strong> {{ $message->subject_display }}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-mail-line fs-1"></i>
                                    <p class="mt-2">No contact messages yet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($messages->hasPages())
        <div class="card-footer">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection