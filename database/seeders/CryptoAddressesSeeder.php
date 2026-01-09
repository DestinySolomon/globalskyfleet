<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CryptoAddress;
use App\Models\User;

class CryptoAddressesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@globalskyfleet.com')->first();
        
        if (!$admin) {
            $admin = User::first();
        }

        $addresses = [
            [
                'crypto_type' => 'BTC',
                'address' => 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh',
                'label' => 'Primary BTC Wallet',
                'is_active' => true,
                'created_by' => $admin->id,
                'notes' => 'Main Bitcoin wallet for receiving payments',
            ],
            [
                'crypto_type' => 'USDT_ERC20',
                'address' => '0x742d35Cc6634C0532925a3b844Bc9e0BBE0F5E1F',
                'label' => 'USDT ERC-20 Wallet',
                'is_active' => true,
                'created_by' => $admin->id,
                'notes' => 'USDT on Ethereum network',
            ],
            [
                'crypto_type' => 'USDT_TRC20',
                'address' => 'TXYZq1JxF7y3z9H7Y1P2s6M3n4K5L6P7Q8R9',
                'label' => 'USDT TRC-20 Wallet',
                'is_active' => true,
                'created_by' => $admin->id,
                'notes' => 'USDT on Tron network',
            ],
        ];

        foreach ($addresses as $address) {
            CryptoAddress::create($address);
        }
    }
}