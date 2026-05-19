<?php

namespace Database\Seeders;

use App\Models\PaymentSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSettingsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            // Bank Transfers
            [
                'type' => 'bank_transfer',
                'name' => 'Bank BCA',
                'account_number' => '1234567890',
                'account_name' => 'PT Wedding Invitation Indonesia',
                'logo' => null,
                'instructions' => 'Transfer ke nomor rekening di atas. Pastikan nama penerima sesuai.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'type' => 'bank_transfer',
                'name' => 'Bank Mandiri',
                'account_number' => '0987654321',
                'account_name' => 'PT Wedding Invitation Indonesia',
                'logo' => null,
                'instructions' => 'Transfer ke nomor rekening di atas. Pastikan nama penerima sesuai.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'type' => 'bank_transfer',
                'name' => 'Bank BNI',
                'account_number' => '1122334455',
                'account_name' => 'PT Wedding Invitation Indonesia',
                'logo' => null,
                'instructions' => 'Transfer ke nomor rekening di atas. Pastikan nama penerima sesuai.',
                'is_active' => true,
                'sort_order' => 3,
            ],

            // E-Wallets
            [
                'type' => 'e_wallet',
                'name' => 'GoPay',
                'account_number' => '081234567890',
                'account_name' => 'Wedding Invitation',
                'logo' => null,
                'instructions' => 'Transfer ke nomor GoPay di atas.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'type' => 'e_wallet',
                'name' => 'OVO',
                'account_number' => '081234567890',
                'account_name' => 'Wedding Invitation',
                'logo' => null,
                'instructions' => 'Transfer ke nomor OVO di atas.',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'type' => 'e_wallet',
                'name' => 'DANA',
                'account_number' => '081234567890',
                'account_name' => 'Wedding Invitation',
                'logo' => null,
                'instructions' => 'Transfer ke nomor DANA di atas.',
                'is_active' => true,
                'sort_order' => 6,
            ],

            // QRIS
            [
                'type' => 'qris',
                'name' => 'QRIS',
                'account_number' => null,
                'account_name' => 'Wedding Invitation Indonesia',
                'logo' => null,
                'qr_code_image' => null,
                'instructions' => 'Scan QR code dengan aplikasi e-wallet atau mobile banking Anda.',
                'is_active' => true,
                'sort_order' => 7,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentSetting::updateOrCreate(
                ['type' => $method['type'], 'name' => $method['name']],
                $method
            );
        }

        $this->command->info('Payment settings seeded successfully!');
    }
}
