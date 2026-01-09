<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\User;
use App\Models\CryptoPayment;
use App\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvoicesSeeder extends Seeder
{
    public function run(): void
    {
        // Get a user to assign invoices to
        $user = User::first();
        
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
        
        // Create some exchange rates
        ExchangeRate::create([
            'from_currency' => 'USD',
            'to_currency' => 'BTC',
            'rate' => 45000.00,
            'fetched_at' => now(),
        ]);
        
        ExchangeRate::create([
            'from_currency' => 'USD',
            'to_currency' => 'USDT',
            'rate' => 1.00,
            'fetched_at' => now(),
        ]);
        
        // Create sample invoices
        $invoices = [
            [
                'user_id' => $user->id,
                'invoice_number' => 'INV-' . date('Y') . '-0001',
                'amount' => 150.00,
                'currency' => 'USD',
                'description' => 'International Shipping - Package to Germany',
                'invoice_date' => now()->subDays(30),
                'due_date' => now()->subDays(15),
                'status' => 'paid',
                'items' => json_encode([
                    ['description' => 'Express Shipping', 'amount' => 120.00],
                    ['description' => 'Insurance', 'amount' => 30.00],
                ]),
                'notes' => 'Thank you for your business!',
            ],
            [
                'user_id' => $user->id,
                'invoice_number' => 'INV-' . date('Y') . '-0002',
                'amount' => 89.50,
                'currency' => 'USD',
                'description' => 'Domestic Delivery - New York to Los Angeles',
                'invoice_date' => now()->subDays(20),
                'due_date' => now()->subDays(5),
                'status' => 'pending',
                'items' => json_encode([
                    ['description' => 'Standard Shipping', 'amount' => 75.00],
                    ['description' => 'Packaging', 'amount' => 14.50],
                ]),
                'notes' => 'Payment due within 15 days',
            ],
            [
                'user_id' => $user->id,
                'invoice_number' => 'INV-' . date('Y') . '-0003',
                'amount' => 245.75,
                'currency' => 'USD',
                'description' => 'Bulk Shipment - 5 packages to UK',
                'invoice_date' => now()->subDays(10),
                'due_date' => now()->addDays(5),
                'status' => 'pending',
                'items' => json_encode([
                    ['description' => 'Bulk Shipping Discount', 'amount' => 200.00],
                    ['description' => 'Customs Handling', 'amount' => 45.75],
                ]),
                'notes' => 'Special bulk rate applied',
            ],
            [
                'user_id' => $user->id,
                'invoice_number' => 'INV-' . date('Y') . '-0004',
                'amount' => 65.25,
                'currency' => 'USD',
                'description' => 'Document Courier Service',
                'invoice_date' => now()->subDays(5),
                'due_date' => now()->addDays(10),
                'status' => 'pending',
                'items' => json_encode([
                    ['description' => 'Document Delivery', 'amount' => 50.00],
                    ['description' => 'Tracking Service', 'amount' => 15.25],
                ]),
                'notes' => 'Urgent documents delivery',
            ],
            [
                'user_id' => $user->id,
                'invoice_number' => 'INV-' . date('Y') . '-0005',
                'amount' => 320.00,
                'currency' => 'USD',
                'description' => 'Fragile Items Shipping',
                'invoice_date' => now(),
                'due_date' => now()->addDays(15),
                'status' => 'draft',
                'items' => json_encode([
                    ['description' => 'Special Handling', 'amount' => 250.00],
                    ['description' => 'Fragile Packaging', 'amount' => 70.00],
                ]),
                'notes' => 'Extra care required for fragile items',
            ],
        ];
        
        foreach ($invoices as $invoiceData) {
            $invoice = Invoice::create($invoiceData);
            
            // Create a payment for the paid invoice
            if ($invoice->status === 'paid') {
                CryptoPayment::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => $user->id,
                    'crypto_type' => 'BTC',
                    'crypto_amount' => 150.00 / 45000, // 0.00333333 BTC
                    'payment_address' => 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh',
                    'transaction_id' => 'txid_' . Str::random(10),
                    'status' => 'completed',
                    'exchange_rate' => 45000.00,
                    'paid_at' => now()->subDays(10),
                    'confirmed_at' => now()->subDays(9),
                ]);
            }
            
            // Create a pending payment for one invoice
            if ($invoice->invoice_number === 'INV-' . date('Y') . '-0002') {
                CryptoPayment::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => $user->id,
                    'crypto_type' => 'USDT_ERC20',
                    'usdt_amount' => 89.50,
                    'payment_address' => '0x742d35Cc6634C0532925a3b844Bc9e0BBE0F5E1F',
                    'transaction_id' => 'txid_' . Str::random(10),
                    'status' => 'pending',
                    'exchange_rate' => 1.00,
                    'paid_at' => now()->subDays(2),
                ]);
            }
        }
        
        $this->command->info('✅ 5 sample invoices created for user: ' . $user->email);
        $this->command->info('✅ 2 sample payments created');
        $this->command->info('✅ Exchange rates created');
    }
}