<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomsItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'customs_declaration_id',
        'description',
        'quantity',
        'weight',
        'value',
        'currency',
        'country_of_origin',
        'hs_code',
    ];

    protected $casts = [
        'weight' => 'decimal:3',
        'value' => 'decimal:2',
    ];

    // Relationship: Item belongs to a customs declaration
    public function customsDeclaration()
    {
        return $this->belongsTo(CustomsDeclaration::class);
    }
}