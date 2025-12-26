<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomsDeclaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'hs_code',
        'purpose_of_export',
        'invoice_number',
        'certificate_of_origin',
        'export_license_required',
        'export_license_number',
    ];

    protected $casts = [
        'certificate_of_origin' => 'boolean',
        'export_license_required' => 'boolean',
    ];

    // Relationship: Customs declaration belongs to a shipment
    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    // Relationship: Customs declaration has many items
    public function items()
    {
        return $this->hasMany(CustomsItem::class);
    }
}