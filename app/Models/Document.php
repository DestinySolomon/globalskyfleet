<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipment_id',
        'type',
        'name',
        'file_path',
        'file_name',
        'original_name',
        'file_size',
        'mime_type',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
          'shipment_id' => 'string',
    ];

    // Relationship: Document belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Document belongs to a Shipment
   public function shipment()
{
    return $this->belongsTo(Shipment::class, 'shipment_id', 'id');
}

    // Helper: Get document type label
    public function getTypeLabelAttribute()
    {
        $types = [
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

        return $types[$this->type] ?? $this->type;
    }

    // Helper: Get file icon based on type
    public function getFileIconAttribute()
    {
        switch ($this->mime_type) {
            case 'application/pdf':
                return 'ri-file-pdf-line';
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
                return 'ri-image-line';
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return 'ri-file-word-line';
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return 'ri-file-excel-line';
            default:
                return 'ri-file-line';
        }
    }

    // Helper: Format file size
    public function getFormattedSizeAttribute()
    {
        $size = $this->file_size;
        
        if ($size >= 1073741824) {
            return number_format($size / 1073741824, 2) . ' GB';
        } elseif ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            return number_format($size / 1024, 2) . ' KB';
        } else {
            return $size . ' bytes';
        }
    }

    // Helper: Check if document is an image
    public function getIsImageAttribute()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    // Helper: Check if document is PDF
    public function getIsPdfAttribute()
    {
        return $this->mime_type === 'application/pdf';
    }
}