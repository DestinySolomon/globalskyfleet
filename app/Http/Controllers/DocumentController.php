<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Check if current user is admin
     */
    private function isAdmin()
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    /**
     * Get base query for documents based on user role
     */
    private function getDocumentsQuery()
    {
        if ($this->isAdmin()) {
            // Admin sees all documents
            return Document::query();
        } else {
            // Regular user sees only their documents
            return Auth::user()->documents();
        }
    }

    /**
     * Get base query for shipments based on user role
     */
    private function getShipmentsQuery()
    {
        if ($this->isAdmin()) {
            // Admin sees all shipments
            return Shipment::query();
        } else {
            // Regular user sees only their shipments
            return Auth::user()->shipments();
        }
    }

    /**
     * Display a listing of documents
     */
    public function index(Request $request)
    {
        // Get shipments that have documents
        $shipments = $this->getShipmentsQuery()
            ->whereHas('documents')
            ->with(['documents' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get document statistics
        $documentsQuery = $this->getDocumentsQuery();
        $stats = [
            'total' => $documentsQuery->count(),
            'shipping_labels' => $documentsQuery->where('type', 'shipping_label')->count(),
            'invoices' => $documentsQuery->where('type', 'invoice')->count(),
            'customs' => $documentsQuery->where('type', 'customs_document')->count(),
            'delivery_proof' => $documentsQuery->where('type', 'delivery_proof')->count(),
        ];

        // Get recent documents
        $recentDocuments = $documentsQuery
            ->with(['shipment', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('documents.index', compact('shipments', 'stats', 'recentDocuments'));
    }

    /**
     * Show the form for uploading a new document
     */
    public function create()
    {
        $shipments = Auth::user()->shipments()
            ->select('id', 'tracking_number', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $documentTypes = [
            'shipping_label' => 'Shipping Label',
            'commercial_invoice' => 'Commercial Invoice',
            'packing_list' => 'Packing List',
            'certificate_of_origin' => 'Certificate of Origin',
            'customs_declaration' => 'Customs Declaration',
            'delivery_proof' => 'Proof of Delivery',
            'invoice' => 'Shipping Invoice',
            'receipt' => 'Payment Receipt',
            'other' => 'Other Document',
        ];

        return view('documents.create', compact('shipments', 'documentTypes'));
    }

    /**
     * Store a newly uploaded document
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipment_id' => ['required', 'exists:shipments,id'],
            'document_type' => ['required', 'string'],
            'document_name' => ['required', 'string', 'max:255'],
            'document_file' => ['required', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify the shipment belongs to the user (unless admin)
        $shipmentQuery = $this->isAdmin() ? Shipment::query() : Auth::user()->shipments();
        $shipment = $shipmentQuery->find($request->shipment_id);
        
        if (!$shipment) {
            return redirect()->back()
                ->with('error', 'Invalid shipment selected.')
                ->withInput();
        }

        try {
            // Handle file upload
            $file = $request->file('document_file');
            $fileName = 'DOC_' . Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents/' . Auth::id(), $fileName, 'private');

            // Create document record
            $document = new Document();
            $document->user_id = Auth::id();
            $document->shipment_id = $request->shipment_id;
            $document->type = $request->document_type;
            $document->name = $request->document_name;
            $document->file_path = $filePath;
            $document->file_name = $fileName;
            $document->original_name = $file->getClientOriginalName();
            $document->file_size = $file->getSize();
            $document->mime_type = $file->getMimeType();
            $document->description = $request->description;
            $document->save();

            return redirect()->route('documents.index')
                ->with('success', 'Document uploaded successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to upload document: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display/download a specific document
     */
    public function show(Document $document)
    {
        // Authorization - user can only view their own documents unless admin
        if ($document->user_id !== Auth::id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if file exists
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'Document not found.');
        }

        // Return file download
        return Storage::disk('private')->download($document->file_path, $document->original_name);
    }

    /**
     * View document in browser
     */
    public function view(Document $document)
    {
        // Authorization - user can only view their own documents unless admin
        if ($document->user_id !== Auth::id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if file exists
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'Document not found.');
        }

        // Get file content
        $file = Storage::disk('private')->get($document->file_path);
        
        // Return file with appropriate headers
        return response($file, 200)
            ->header('Content-Type', $document->mime_type)
            ->header('Content-Disposition', 'inline; filename="' . $document->original_name . '"');
    }

    /**
     * Remove the specified document
     */
    public function destroy(Document $document)
    {
        // Authorization - user can only delete their own documents unless admin
        if ($document->user_id !== Auth::id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        try {
            // Delete file from storage
            if (Storage::disk('private')->exists($document->file_path)) {
                Storage::disk('private')->delete($document->file_path);
            }

            // Delete record from database
            $document->delete();

            return redirect()->route('documents.index')
                ->with('success', 'Document deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete document.');
        }
    }

    /**
     * Generate shipping label for a shipment
     */
    public function generateShippingLabel(Shipment $shipment)
    {
        // Authorization - user can only generate labels for their own shipments unless admin
        if ($shipment->user_id !== Auth::id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if label already exists
        $existingLabel = $shipment->documents()
            ->where('type', 'shipping_label')
            ->first();

        if ($existingLabel) {
            return redirect()->route('documents.view', $existingLabel)
                ->with('info', 'Shipping label already exists.');
        }

        // TODO: Implement label generation logic
        // This would typically generate a PDF label using a library like DomPDF

        return redirect()->route('shipments.show', $shipment)
            ->with('info', 'Label generation feature coming soon.');
    }

    /**
     * Generate commercial invoice for a shipment
     */
    public function generateInvoice(Shipment $shipment)
    {
        // Authorization - user can only generate invoices for their own shipments unless admin
        if ($shipment->user_id !== Auth::id() && !$this->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // TODO: Implement invoice generation logic

        return redirect()->route('shipments.show', $shipment)
            ->with('info', 'Invoice generation feature coming soon.');
    }

    /**
     * Search documents
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('documents.index')
                ->withErrors($validator);
        }

        $query = $this->getDocumentsQuery()
            ->with(['shipment', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('shipment', function($q) use ($search) {
                      $q->where('tracking_number', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $documents = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $documentTypes = [
            'shipping_label' => 'Shipping Label',
            'commercial_invoice' => 'Commercial Invoice',
            'packing_list' => 'Packing List',
            'certificate_of_origin' => 'Certificate of Origin',
            'customs_declaration' => 'Customs Declaration',
            'delivery_proof' => 'Proof of Delivery',
            'invoice' => 'Shipping Invoice',
            'receipt' => 'Payment Receipt',
            'other' => 'Other Document',
        ];

        return view('documents.search', compact('documents', 'documentTypes'));
    }
}